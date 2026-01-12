<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickUp;
use App\Models\Bookings;
use App\Models\ReturnCar;
use Illuminate\Support\Facades\File;

class PickUpController extends Controller
{
    public function show($bookingID)
    {
        $booking = Bookings::with(['vehicle', 'returnCar'])->findOrFail($bookingID);
        $return = $booking->returnCar;
        $onlyDepositPaid = ($booking->pay_amount_type === 'deposit');

        $pickup = PickUp::firstOrCreate(
            ['bookingID' => $bookingID], 
            [
                'pickupDate'     => $booking->startDate,
                'pickupLocation' => $booking->pickup_location ?? '', // Default to booking location
                'agreementForm'  => 0,
            ]
        );

        return view('pickupform', [
            'booking' => $booking,
            'vehicle' => $booking->vehicle,
            'pickup' => $pickup,
            'return' => $return,
            'onlyDepositPaid' => $onlyDepositPaid
        ]);
    }

    public function store(Request $request)
    {
        // UNCOMMENT THE LINE BELOW TO DEBUG IF IT FAILS
        // dd($request->all()); 

        $request->validate([
            'bookingID'    => 'required',
            'photo_front'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_back'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_left'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_right'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'manual_signature_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Custom check: Must have Canvas OR File
        if (!$request->filled('signature') && !$request->hasFile('manual_signature_photo')) {
            return redirect()->back()->withErrors(['signature' => 'Agreement is required (Sign or Upload).']);
        }

        $pickup = PickUp::where('bookingID', $request->bookingID)->firstOrFail();

        // Save Car Photos
        $sides = ['front', 'back', 'left', 'right'];
        foreach ($sides as $side) {
            $inputName = 'photo_' . $side;
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $fileName = time() . "_{$side}_" . $request->bookingID . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/pickups'), $fileName);
                $pickup->{"photo_$side"} = 'uploads/pickups/' . $fileName;
            }
        }

        // Save Signature (Priority to File Upload if both exist, or use Canvas)
        if ($request->hasFile('manual_signature_photo')) {
            $file = $request->file('manual_signature_photo');
            $fileName = time() . '_manual_sig_' . $request->bookingID . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/signatures'), $fileName);
            $pickup->signature_path = 'uploads/signatures/' . $fileName;
        } 
        elseif ($request->filled('signature')) {
            $image = str_replace('data:image/png;base64,', '', $request->signature);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '_sig_' . $request->bookingID . '.png';
            \File::ensureDirectoryExists(public_path('uploads/signatures/'));
            \File::put(public_path('uploads/signatures/') . $imageName, base64_decode($image));
            $pickup->signature_path = 'uploads/signatures/' . $imageName;
        }

        if ($request->hasFile('trafficTicketPhoto')) {
        $paths = [];
        foreach ($request->file('trafficTicketPhoto') as $photo) {
                // Save photo in public/uploads/tickets
                $name = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/tickets'), $name);
                $paths[] = 'uploads/tickets/' + $name;
            }
            // Save as a JSON string: ["path1.jpg", "path2.jpg"]
            $return->trafficTicketPhoto = json_encode($paths);
        }

        $pickup->save();
       return redirect('/customer/customer/bookings')->with('showModal', true);
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'bookingID'              => 'required',
            'return_photo_front'     => 'required|image|max:2048',
            'return_photo_back'      => 'required|image|max:2048',
            'return_photo_left'      => 'required|image|max:2048',
            'return_photo_right'     => 'required|image|max:2048',
            'return_photo_dashboard' => 'required|image|max:2048',
            'return_photo_keys'      => 'required|image|max:2048',
            'trafficTicketPhoto.*'   => 'nullable|image|max:2048', // Validate each file in array
            'isFined'                => 'required'
        ]);

        $return = ReturnCar::where('bookingID', $request->bookingID)->firstOrFail();

        // 1. Handle Single Vehicle Photos
        $fields = [
            'return_photo_front'     => 'photo_front', 
            'return_photo_back'      => 'photo_back', 
            'return_photo_left'      => 'photo_left', 
            'return_photo_right'     => 'photo_right',
            'return_photo_dashboard' => 'photo_dashboard',
            'return_photo_keys'      => 'photo_keys',
        ];

        foreach ($fields as $inputName => $dbColumn) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $fileName = time() . "_{$inputName}_" . $request->bookingID . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/returns'), $fileName);
                $return->{$dbColumn} = 'uploads/returns/' . $fileName;
            }
        }

        // 2. Handle Multiple Traffic Tickets
        if ($request->hasFile('trafficTicketPhoto')) {
            $ticketPaths = [];
            foreach ($request->file('trafficTicketPhoto') as $photo) {
                $name = time() . '_ticket_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/tickets'), $name);
                $ticketPaths[] = 'uploads/tickets/' . $name;
            }
            // Because of the 'array' cast in the Model, we just assign the array
            $return->trafficTicketPhoto = $ticketPaths; 
        }

        // 3. Handle Text and Radio Fields
        $return->isfined = ($request->isFined === 'yes') ? 1 : 0;
        $return->fuelAmount = $request->fuelAmount;
        $return->feedback = $request->feedback;

        $return->actual_return_time = $request->acRetTime;
        $return->late_fee = $request->late_fee ?? 0;
        $return->fuel_fee = $request->fuel_fee ?? 0;
        $return->total_fee = $request->total_fee ?? 0;
        
        $return->save();

        return redirect()->back()->with('success', 'Vehicle returned successfully!');
    }
}
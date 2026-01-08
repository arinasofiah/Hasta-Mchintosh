<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickUp;
use App\Models\Bookings;

class PickUpController extends Controller
{
    public function form($bookingID)
    {
        $booking = Bookings::with('vehicle')->findOrFail($bookingID);
        $onlyDepositPaid = ($booking->pay_amount_type === 'deposit');

        // We pull the location from the booking or existing record
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
            'onlyDepositPaid' => $onlyDepositPaid
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validate all 4 photos
        $request->validate([
            'bookingID'    => 'required',
            'photo_front'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_back'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_left'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'photo_right'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'signature' => 'required'
        ]);
        
        $pickup = PickUp::where('bookingID', $request->bookingID)->firstOrFail();

        // 2. Process and save the 4 photos
        $photoPaths = [];
        $sides = ['front', 'back', 'left', 'right'];

        foreach ($sides as $side) {
            $inputName = 'photo_' . $side;
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $fileName = time() . "_{$side}_" . $request->bookingID . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/pickups'), $fileName);
                
                $path = 'uploads/pickups/' . $fileName;
                $photoPaths[] = $path;

                $pickup->{"photo_$side"} = $path;
            }
        }

        $signatureData = $request->signature;
        $image = str_replace('data:image/png;base64,', '', $signatureData);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '_sig_' . $request->bookingID . '.png';
        \File::put(public_path('uploads/signatures/') . $imageName, base64_decode($image));

        $pickup->signature_path = 'uploads/signatures/' . $imageName;
        
        $pickup->save();

        return redirect()->back()->with('showModal', true);
    }
}
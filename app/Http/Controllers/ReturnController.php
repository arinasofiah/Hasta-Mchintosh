<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;
use App\Models\ReturnCar;
use App\Models\Bookings;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function show($bookingID)
    {
        $booking = Bookings::with('vehicle')->findOrFail($bookingID);

        // Auto-create return record if it doesn't exist
        $returnCar = ReturnCar::firstOrCreate(
            ['bookingID' => $bookingID],
            [
                'returnDate'     => $booking->endDate,
                'returnLocation' => '', // User will input this manually
                'returnPhoto'    => '',
                'fuelAmount'     => 0,
                'isfined'        => 0,
                'feedback'       => '',
            ]
        );
        
        return view('returnform', [
            'booking' => $booking,
            'vehicle' => $booking->vehicle,
            'returnCar' => $returnCar,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bookingID'          => 'required|exists:bookings,bookingID',
            'returnLocation'     => 'required|string|max:255',
            'isFined'            => 'required|in:yes,no',
            'returnPhoto'        => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'trafficTicketPhoto' => 'image|mimes:jpeg,png,jpg|max:2048',
            'feedback'           => 'required|min:20',
            'fuelAmount'         => 'required',
        ]);

        // Find by bookingID to ensure we update the record we just created/found
        $returnCar = ReturnCar::where('bookingID', $request->bookingID)->firstOrFail();

        // Handle Photos
        if ($request->hasFile('returnPhoto')) {
            $file = $request->file('returnPhoto');
            $fileName = time() . '_return_' . $request->bookingID . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/returns'), $fileName);
            $returnCar->returnPhoto = 'uploads/returns/' . $fileName;
        }

        if ($request->hasFile('trafficTicketPhoto')) {
            $file = $request->file('trafficTicketPhoto');
            $fileName = time() . '_ticket_' . $request->bookingID . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets'), $fileName);
            $returnCar->trafficTicketPhoto = 'uploads/tickets/' . $fileName;
        }

        // Calculation logic
        $scheduledDeadline = Carbon::parse($returnCar->returnDate . ' ' . $returnCar->returnTime, 'Asia/Kuala_Lumpur');
        $actualReturn = Carbon::now('Asia/Kuala_Lumpur');

        $hoursLate = 0;
        if ($actualReturn->gt($scheduledDeadline)) {
            $hoursLate = $actualReturn->diffInHours($scheduledDeadline);
        }

        $returnCar->returnLocation = $request->returnLocation;
        $returnCar->fuelAmount = $request->fuelAmount;
        $returnCar->isfined = ($request->isFined === 'yes') ? 1 : 0;
        $returnCar->feedback = $request->feedback;
        $returnCar->lateHours = $hoursLate;
        $returnCar->save();

        return redirect('/')->with('success', 'Return completed!');
    }
}
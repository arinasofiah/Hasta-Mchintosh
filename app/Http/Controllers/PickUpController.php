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

        // Initial creation with placeholders
        $pickup = PickUp::firstOrCreate(
            ['bookingID' => $bookingID], 
            [
                'pickupDate'     => $booking->startDate,
                'pickupLocation' => '', // Start empty, user will fill this in
                'pickupPhoto'    => '', 
                'agreementForm'  => 0,
                'status'         => 'pending'
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
    $request->validate([
        'bookingID'      => 'required',
        'pickupLocation' => 'required|string|max:255',
        'pickupPhoto'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'agreementForm'  => 'required|in:yes'
    ]);
    
    $pickup = PickUp::where('bookingID', $request->bookingID)->firstOrFail();

    $pickup->pickupLocation = $request->pickupLocation;

    if ($request->hasFile('pickupPhoto')) {
        $file = $request->file('pickupPhoto');
        $fileName = time() . '_pickup_' . $request->bookingID . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/pickups'), $fileName);
        $pickup->pickupPhoto = 'uploads/pickups/' . $fileName;
    }

    $pickup->agreementForm = 1;
    // REMOVED: $pickup->status = 'completed'; <-- This was causing your error
    $pickup->save();

    return redirect()->back()->with('showModal', true);
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;
use App\Models\PickUp;
use App\Models\Bookings;

class PickUpController extends Controller
{
    public function show($bookingID)
    {
        $booking = Bookings::with('vehicle')->findOrFail($bookingID);
        
        $onlyDepositPaid = ($booking->pay_amount_type === 'deposit');

        $pickup = PickUp::where('bookingID', $bookingID)->first();

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
            'pickupPhoto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'agreementForm' => 'required|in:yes'
        ]);
        
        $pickup = PickUp::findOrFail($request->pickupID);

        if ($request->hasFile('pickupPhoto')) {
        $file = $request->file('pickupPhoto');
        $fileName = time() . '_vehicle.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/pickups'), $fileName);
        $pickup->pickupPhoto = 'uploads/pickups/' . $fileName;
    }

        $pickup->bookingID = $request->bookingID;
        $pickup->agreementForm = $request->agreementForm === 'yes' ? 1 : 0;
        $pickup->save();

        return redirect()->back()->with('showModal', true);
    }

    public function form($bookingID)
{
    $booking = Bookings::with('vehicle')->findOrFail($bookingID);
    
    $onlyDepositPaid = ($booking->pay_amount_type === 'deposit');

    $pickup = PickUp::where('bookingID', $bookingID)->first();

    return view('pickupform', [
        'booking' => $booking,
        'vehicle' => $booking->vehicle,
        'pickup' => $pickup,
        'onlyDepositPaid' => $onlyDepositPaid
    ]);

    return redirect()->route('pickup.form', ['bookingID' => $booking->bookingID]);
}
}
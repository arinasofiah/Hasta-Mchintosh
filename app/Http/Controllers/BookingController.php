<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Show the booking form
    public function showForm($vehicleID)
    {
        $vehicle = Vehicles::findOrFail($vehicleID); // Corrected class name
        return view('bookingform', compact('vehicle'));
    }

    // Store booking after clicking "Book"
    public function store(Request $request, $vehicleID)
    {
        $vehicle = Vehicles::findOrFail($vehicleID);

        // 1. Check if vehicle is still available
        if ($vehicle->status !== 'available') {
            return back()->with('error', 'Sorry, this vehicle is no longer available.');
        }

        // 2. Create the booking
        $booking = new Bookings();
        $booking->vehicleID = $vehicleID;
        $booking->user_id = auth()->user()->userID;
        $booking->start_date = $request->start_date;
        $booking->start_time = $request->start_time;
        $booking->end_date = $request->end_date;
        $booking->end_time = $request->end_time;
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addMinutes(10); // 10-minute window
        $booking->save();

        // 3. Temporarily mark vehicle as unavailable
        $vehicle->status = 'unavailable';
        $vehicle->save();

        return redirect()->route('customer.bookingPayment', $booking->bookingID)
                         ->with('success', 'Booking created! Please complete payment within 10 minutes.');
    }
}

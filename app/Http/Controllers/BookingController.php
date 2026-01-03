<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PickUp;
use App\Models\ReturnTrip;

class BookingController extends Controller
{
    // Show the booking form
    public function showForm($vehicleID, Request $request)
    {
        $vehicle = Vehicles::findOrFail($vehicleID); 

        $pickupDate = $request->query('pickup_date', now()->toDateString());
        $pickupTime = $request->query('pickup_time', '08:00');
        $returnDate = $request->query('return_date', now()->addDay()->toDateString());
        $returnTime = $request->query('return_time', '08:00');

        // Calculate duration and total price
        $start = Carbon::parse("$pickupDate $pickupTime");
        $end = Carbon::parse("$returnDate $returnTime");
        $durationHours = $end->diffInHours($start);
        $durationDays = ceil($durationHours / 24);

        if ($durationDays < 1) {
        $durationDays = 1;
        }
        
        $totalPrice = $durationDays * $vehicle->pricePerDay;

        return view('bookingform', [
        'vehicle' => $vehicle,
        'pickupDate' => old('pickup_date', $pickupDate),
        'pickupTime' => old('pickup_time', $pickupTime),
        'returnDate' => old('return_date', $returnDate),
        'returnTime' => old('return_time', $returnTime),
        'durationDays' => $durationDays,
        'durationHours' => $durationHours,
        'totalPrice' => $totalPrice
    ]);
    }

    public function start($vehicleID, Request $request)
{
    session([
        'pickup_date'  => $request->pickup_date,
        'pickup_time'  => $request->pickup_time,
        'return_date'  => $request->return_date,
        'return_time'  => $request->return_time,
        'durationDays' => $durationDays,
        'durationHours' => $durationHours,
        'totalPrice' => $totalPrice
    ]);

    return redirect()->route('booking.form', $vehicleID);
}


    // Store booking after clicking "Book"
    public function store(Request $request, $vehicleID)
    {
        $vehicle = Vehicles::findOrFail($vehicleID);

        // 1. Check if vehicle is still available
        if ($vehicle->status !== 'available') {
            return back()->with('error', 'Sorry, this vehicle is no longer available.');
        }

        $request->validate([
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'pickup_location' => 'required|string|max:255',
            'return_date' => 'required|date',
            'return_time' => 'required',
            'return_location' => 'required|string|max:255',
        ]);

        // Build Carbon datetime objects
        $start = Carbon::parse($request->pickup_date.' '.$request->pickup_time);
        $end   = Carbon::parse($request->return_date.' '.$request->return_time);

        // Calculate duration in days (rounded up)
        $durationDays = ceil($start->diffInHours($end) / 24);
        if ($durationDays < 1) {
            $durationDays = 1;
        }
        $hours = ceil($start->diffInMinutes($end) / 60);
        $totalPrice = $hours * $vehicle->pricePerHour;

        /*$totalPrice = $durationDays * $vehicle->pricePerDay;*/

        // Create the booking
        $booking = new Bookings();
        $booking->vehicleID = $vehicleID;
        $booking->customerID = auth()->user()->userID; // Assumes User model has userID
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $end->diffInHours($start);
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addMinutes(10);
        $booking->totalPrice = $booking->bookingDuration * $vehicle->pricePerDay;
        $booking->save();

        // Save pickup info
        PickUp::create([
            'bookingID' => $booking->bookingID,
            'pickupDate' => $request->pickup_date,
            'pickupTime' => $request->pickup_time,
            'location' => $request->pickup_location,
        ]);

        //  Save return info
        ReturnTrip::create([
            'bookingID' => $booking->bookingID,
            'returnDate' => $request->return_date,
            'returnTime' => $request->return_time,
            'location' => $request->return_location,
        ]);

        // Lock vehicle during payment window
        $vehicle->status = 'unavailable';
        $vehicle->save();

        return redirect()
            ->route('customer.bookingPayment', $booking->bookingID)
            ->with('success', 'Booking created — please complete payment within 10 minutes.');

        }

    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicleID', 'vehicleID');
    }

    public function pickup()
    {
        return $this->hasOne(PickUp::class, 'bookingID', 'bookingID');
    }

    public function returnTrip()
    {
        return $this->hasOne(ReturnTrip::class, 'bookingID', 'bookingID');
    }

}


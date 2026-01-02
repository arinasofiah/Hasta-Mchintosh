<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function showForm($vehicleID)
    {
        // Fetch the vehicle details
        $vehicle = Vehicle::findOrFail($vehicleID);
        
        // Return the booking form view
        return view('bookingform', compact('vehicle'));
    }
}
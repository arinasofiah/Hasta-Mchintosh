<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard.
     */
    public function index()
    {
        // Check if user is customer
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        // Fetch available vehicles from database
        $vehicles = DB::table('vehicles')
            ->where('status', 'available') // Use 'status' column instead of 'availability'
            ->select('vehicleID', 'vehicleType', 'model', 'plateNumber', 'fuelLevel', 
                     'fuelType', 'ac', 'seat', 'status', 'pricePerDay', 'pricePerHour')
            ->get();
        
        // Pass vehicles to the view
        return view('customer.dashboard', compact('vehicles'));
    }
}
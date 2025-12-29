<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            ->where('status', 'available')
            ->select('vehicleID', 'vehicleType', 'model', 'plateNumber', 'fuelLevel', 
                     'fuelType', 'ac', 'seat', 'status', 'pricePerDay', 'pricePerHour')
            ->get();
        
        // Pass vehicles to the view
        return view('customer.dashboard', compact('vehicles'));
    }
    
    /**
     * Display customer profile.
     */
    public function profile()
    {
        // Check if user is customer
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        // Get user data
        $user = Auth::user();
        
        // Get customer details from customer table
        $customer = DB::table('customer')
            ->where('userID', $user->userID)
            ->first();
        
        return view('customer.profile', compact('user', 'customer'));
    }

/**
 * Show the form for editing the profile.
 */
public function edit()
{
    if (auth()->user()->userType !== 'customer') {
        abort(403, 'Unauthorized. Customer access only.');
    }
    
    $user = Auth::user();
    $customer = DB::table('customer')->where('userID', $user->userID)->first();
    
    return view('customer.edit-profile', compact('user', 'customer'));
}

/**
 * Update the user's profile.
 */
public function update(Request $request)
{
    if (auth()->user()->userType !== 'customer') {
        abort(403, 'Unauthorized. Customer access only.');
    }
    
    $user = Auth::user();
    
    // Validation rules
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->userID . ',userID',
        'matricNumber' => 'nullable|string|unique:customer,matricNumber,' . $user->userID . ',userID',
        'college' => 'nullable|string|max:255',
        'faculty' => 'nullable|string|max:255',
        'licenseNumber' => 'nullable|string|max:255',
    ];
    
    // Only validate password if provided
    if ($request->filled('password')) {
        $rules['password'] = 'required|string|min:8|confirmed';
    }
    
    $validated = $request->validate($rules);
    
    // Update user table
    DB::table('users')->where('userID', $user->userID)->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'updated_at' => now(),
    ]);
    
    // Update password if provided
    if ($request->filled('password')) {
        DB::table('users')->where('userID', $user->userID)->update([
            'password' => Hash::make($validated['password']),
        ]);
    }
    
    // Update customer table
    DB::table('customer')->where('userID', $user->userID)->update([
        'matricNumber' => $validated['matricNumber'] ?? null,
        'college' => $validated['college'] ?? null,
        'faculty' => $validated['faculty'] ?? null,
        'licenseNumber' => $validated['licenseNumber'] ?? null,
        'updated_at' => now(),
    ]);
    
    return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
}
    /**
     * Display customer bookings.
     */
    public function bookings()
    {
        // Check if user is customer
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        // Get user ID
        $userId = Auth::user()->userID;
        
        // Get customer bookings (assuming you have a bookings table)
        $bookings = DB::table('bookings')
            ->where('customerID', $userId)
            ->join('vehicles', 'bookings.vehicleID', '=', 'vehicles.vehicleID')
            ->select('bookings.*', 'vehicles.model', 'vehicles.vehicleType', 'vehicles.plateNumber')
            ->orderBy('bookings.created_at', 'desc')
            ->get();
        
        return view('customer.bookings', compact('bookings'));
    }
    
    /**
     * Show booking form for a specific vehicle.
     */
    public function bookingForm($vehicleId)
    {
        // Check if user is customer
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        // Get vehicle details
        $vehicle = DB::table('vehicles')
            ->where('vehicleID', $vehicleId)
            ->where('status', 'available')
            ->first();
        
        if (!$vehicle) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Vehicle not available for booking.');
        }
        
        return view('customer.booking-form', compact('vehicle'));
    }
}
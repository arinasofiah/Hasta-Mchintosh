<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
    
    $user = Auth::user();
    
    // Get customer details using DB query (not Eloquent)
    $customer = \DB::table('customer')
        ->where('userID', $user->userID)
        ->first();
    
    // Phone number is already in $user->phoneNumber
    // No need to load telephone relationship
    
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
    $user = Auth::user();
    
    // Basic validation
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->userID . ',userID',
        'password' => 'nullable|min:8|confirmed',
    ];
    
    $request->validate($rules);
    
    // Custom phone validation
    if ($request->phone != $user->phoneNumber) {
        $phoneExists = DB::table('telephone')
            ->where('phoneNumber', $request->phone)
            ->exists();
            
        if ($phoneExists) {
            return back()->withErrors(['phone' => 'This phone number is already registered.'])->withInput();
        }
    }
    
    // Validate customer fields
    $customerExists = DB::table('customer')
        ->where('userID', $user->userID)
        ->exists();
    
    $customerRules = [];
    if ($customerExists) {
        $customerRules = [
            'matricNumber' => 'nullable|string|max:20|unique:customer,matricNumber,' . $user->userID . ',userID',
            'college' => 'nullable|string|max:100',
            'faculty' => 'nullable|string|max:100',
            'licenseNumber' => 'nullable|string|max:20',
        ];
        
        $request->validate($customerRules);
    }
    
    DB::transaction(function () use ($request, $user, $customerExists) {
        // Handle phone number update
        if ($request->phone != $user->phoneNumber) {
            // Update existing record instead of delete/insert
            DB::table('telephone')
                ->where('phoneNumber', $user->phoneNumber)
                ->update([
                    'phoneNumber' => $request->phone,
                    'userID' => $user->userID,
                    'updated_at' => now(),
                ]);
        }
        
        // Update user
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phoneNumber' => $request->phone,
            'updated_at' => now(),
        ];
        
        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        DB::table('users')
            ->where('userID', $user->userID)
            ->update($updateData);
        
        // Update customer if exists
        if ($customerExists) {
            DB::table('customer')
                ->where('userID', $user->userID)
                ->update([
                    'matricNumber' => $request->matricNumber,
                    'college' => $request->college,
                    'faculty' => $request->faculty,
                    'licenseNumber' => $request->licenseNumber,
                    'updated_at' => now(),
                ]);
        }
    });
    
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

   public function adminIndex(Request $request)
{
    // 1. Get status from URL (default to 'active')
    $status = $request->get('status', 'active'); 
    
    // 2. Map 'active'/'blacklisted' strings to boolean 0/1 for the database
    $isBlacklisted = ($status === 'blacklisted') ? true : false;

    // 3. Build query joining with 'users' for the name and email
    $query = DB::table('customer')
        ->join('users', 'customer.userID', '=', 'users.userID')
        ->where('customer.isBlacklisted', $isBlacklisted)
        ->select('customer.*', 'users.name', 'users.email');

    // 4. Handle Search
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('users.name', 'LIKE', "%{$search}%")
              ->orWhere('customer.userID', 'LIKE', "%{$search}%");
        });
    }

    // 5. Handle Faculty/College Filter
    if ($request->filled('filter')) {
        $filter = $request->input('filter');
        $query->where(function($q) use ($filter) {
            $q->where('customer.faculty', $filter)
              ->orWhere('customer.college', $filter);
        });
    }

    $customers = $query->get();
    $totalCount = $customers->count();

    // 6. Calculate outstanding payments from bookings table
    foreach ($customers as $customer) {
        $customer->outstanding_payment = DB::table('bookings')
            ->where('customerID', $customer->userID)
            ->where('paymentStatus', 'unpaid')
            ->sum('totalPrice');
    }

    return view('admin.customers', compact('customers', 'totalCount', 'status'));
}
public function adminUpdate(Request $request, $id)
{
    $request->validate([
        'isBlacklisted' => 'required|boolean',
        'blacklistReason' => 'nullable|string|max:255'
    ]);

    DB::table('customer')->where('userID', $id)->update([
        'isBlacklisted' => $request->isBlacklisted,
        'blacklistReason' => $request->blacklistReason,
        'updated_at' => now(),
    ]);

    $message = $request->isBlacklisted ? 'Customer blacklisted.' : 'Customer activated.';
    return back()->with('success', $message);
}


}

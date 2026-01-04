<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Telephone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard.
     */
   public function index(Request $request)
{
    // Check if user is customer
    if (auth()->user()->userType !== 'customer') {
        abort(403, 'Unauthorized. Customer access only.');
    }
    
    // Fetch available vehicles from database with filters
    $query = DB::table('vehicles')
        ->where('status', 'available');
    
    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('model', 'LIKE', "%{$search}%")
              ->orWhere('vehicleType', 'LIKE', "%{$search}%")
              ->orWhere('plateNumber', 'LIKE', "%{$search}%");
        });
    }
    
    // Apply category filter
    if ($request->filled('category') && $request->category !== 'All') {
        $query->where('vehicleType', $request->category);
    }
    
    $vehicles = $query->select('vehicleID', 'vehicleType', 'model', 'plateNumber', 'fuelLevel', 
                     'fuelType', 'ac', 'seat', 'status', 'pricePerDay', 'pricePerHour', 'vehiclePhoto', 'transmission')
            ->get();
    
    // Pass vehicles to the view
    return view('customer.dashboard', compact('vehicles'));
}
    
    /**
     * Display customer profile.
     */
    public function profile()
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $user = Auth::user();
        $user->load('telephone'); // Load telephone relationship
        
        $customer = DB::table('customer')
            ->where('userID', $user->userID)
            ->first();
        
        return view('customer.profile', compact('user', 'customer'));
    }

    public function edit()
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $user = Auth::user();
        $user->load('telephone');
        
        $customer = DB::table('customer')
            ->where('userID', $user->userID)
            ->first();
        
        return view('customer.edit-profile', compact('user', 'customer'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->userID . ',userID',
            'password' => 'nullable|min:8|confirmed',
            'phone' => [
                'required',
                'string',
                'max:15',
                function ($attribute, $value, $fail) use ($user) {
                    // Check if phone exists for another user
                    $exists = DB::table('telephone')
                        ->where('phoneNumber', $value)
                        ->where('userID', '!=', $user->userID)
                        ->exists();
                        
                    if ($exists) {
                        $fail('This phone number is already registered by another user.');
                    }
                }
            ],
            // Emergency contact validation rules
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
        ];
        
        $request->validate($rules);
        
        // Customer validation
        $customerExists = DB::table('customer')
            ->where('userID', $user->userID)
            ->exists();
        
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
            // Update phone
            $telephone = Telephone::where('userID', $user->userID)->first();
            
            if ($telephone) {
                if ($telephone->phoneNumber != $request->phone) {
                    $telephone->update(['phoneNumber' => $request->phone]);
                }
            } else {
                Telephone::create([
                    'phoneNumber' => $request->phone,
                    'userID' => $user->userID,
                ]);
            }
            
            // Update user
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'updated_at' => now(),
            ];
            
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            DB::table('users')
                ->where('userID', $user->userID)
                ->update($updateData);
            
            // Update or create customer
            $customerData = [
                'matricNumber' => $request->matricNumber ?? null,
                'college' => $request->college ?? null,
                'faculty' => $request->faculty ?? null,
                'licenseNumber' => $request->licenseNumber ?? null,
                // Emergency contact data
                'emergency_contact_name' => $request->emergency_contact_name ?? null,
                'emergency_contact_phone' => $request->emergency_contact_phone ?? null,
                'emergency_contact_relationship' => $request->emergency_contact_relationship ?? null,
                'updated_at' => now(),
            ];
            
            if ($customerExists) {
                DB::table('customer')
                    ->where('userID', $user->userID)
                    ->update($customerData);
            } else {
                $customerData['userID'] = $user->userID;
                DB::table('customer')->insert($customerData);
            }
        });
        
        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Display customer bookings - CORRECTED VERSION
     */
    public function bookings()
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $userId = Auth::user()->userID;
        
        // Get all bookings with vehicle details
        $bookings = DB::table('booking')
            ->where('customerID', $userId)
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select('booking.*', 'vehicles.model', 'vehicles.vehicleType', 'vehicles.plateNumber')
            ->orderBy('booking.created_at', 'desc')
            ->get();
        
        // Categorize by bookingStatus (based on your database schema)
        // Active/Ongoing: approved status AND current date between pickup and return
        $active = $bookings->filter(function($booking) {
            $now = Carbon::now();
            try {
                $pickupDate = Carbon::parse($booking->pickupDate);
                $returnDate = Carbon::parse($booking->returnDate);
                
                return $booking->bookingStatus === 'approved' && 
                       $now->between($pickupDate, $returnDate);
            } catch (\Exception $e) {
                return false;
            }
        })->values();
        
        // Upcoming: confirmed or approved with future pickup date
        $upcoming = $bookings->filter(function($booking) {
            try {
                $pickupDate = Carbon::parse($booking->pickupDate);
                return ($booking->bookingStatus === 'confirmed' || $booking->bookingStatus === 'approved') && 
                       $pickupDate->isFuture();
            } catch (\Exception $e) {
                return false;
            }
        })->values();
        
        // Completed: completed status
        $completed = $bookings->filter(function($booking) {
            return $booking->bookingStatus === 'completed';
        })->values();
        
        // Pending: pending status
        $pending = $bookings->filter(function($booking) {
            return $booking->bookingStatus === 'pending';
        })->values();
        
        // Cancelled: cancelled status
        $cancelled = $bookings->filter(function($booking) {
            return $booking->bookingStatus === 'cancelled';
        })->values();
        
        return view('bookingHistory', compact(
            'bookings', 'active', 'upcoming', 'completed', 'pending', 'cancelled'
        ));
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
                  ->orWhere('customer.userID', 'LIKE', "%{$search}%")
                  ->orWhere('customer.matricNumber', 'LIKE', "%{$search}%")
                  ->orWhere('customer.emergency_contact_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer.emergency_contact_phone', 'LIKE', "%{$search}%");
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
            $customer->outstanding_payment = DB::table('booking')
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
    
    /**
     * Get booking details for modal (optional)
     */
    public function getBookingDetails($id)
    {
        if (auth()->user()->userType !== 'customer') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $userId = Auth::user()->userID;
        
        $booking = DB::table('booking')
            ->where('id', $id)
            ->where('customerID', $userId)
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select('booking.*', 'vehicles.model', 'vehicles.vehicleType', 'vehicles.plateNumber')
            ->first();
        
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
        
        return response()->json($booking);
    }
}
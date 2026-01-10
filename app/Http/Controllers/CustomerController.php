<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Telephone;
use App\Models\LoyaltyCard;
use App\Models\Voucher;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        
        // Get all bookings with vehicle details - FIXED: Changed customerID to userID
        $bookings = DB::table('booking')
            ->where('userID', $userId) // Changed from customerID to userID
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select(
                'booking.bookingID',
                'booking.userID', // Changed from customerID
                'booking.vehicleID',
                'booking.startDate',
                'booking.endDate',
                'booking.bookingStatus',
                'booking.totalPrice',
                'booking.depositAmount',
                'booking.bankNum',
                'booking.penamaBank',
                'booking.bookingDuration',
                'booking.rewardApplied',
                'booking.created_at',
                'vehicles.model',
                'vehicles.vehicleType',
                'vehicles.plateNumber',
                'vehicles.vehiclePhoto'
            )
            ->orderBy('booking.created_at', 'desc')
            ->get();
        
        // Categorize by bookingStatus
        $active = $bookings->filter(function($booking) {
            $now = Carbon::now();
            try {
                $startDate = Carbon::parse($booking->startDate);
                $endDate = Carbon::parse($booking->endDate);
                
                return $booking->bookingStatus === 'approved' && 
                       $now->between($startDate, $endDate);
            } catch (\Exception $e) {
                return false;
            }
        })->values();
        
        // Upcoming: confirmed or approved with future pickup date
        $upcoming = $bookings->filter(function($booking) {
            try {
                $startDate = Carbon::parse($booking->startDate);
                return ($booking->bookingStatus === 'confirmed' || $booking->bookingStatus === 'approved') && 
                       $startDate->isFuture();
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
        
        return view('bookingform', compact('vehicle'));
    }
    
    /**
     * Show pickup form for a booking.
     */
    public function showPickupForm($bookingId)
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $userId = Auth::user()->userID;
        
        $booking = DB::table('booking')
            ->where('bookingID', $bookingId)
            ->where('userID', $userId) // Changed from customerID to userID
            ->where('bookingStatus', 'approved')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select('booking.*', 'vehicles.model', 'vehicles.vehicleType', 'vehicles.plateNumber')
            ->first();
        
        if (!$booking) {
            return redirect()->route('bookingHistory')
                ->with('error', 'Booking not found or not available for pickup.');
        }
        
        // Check if pickup date is today or in the past
        $startDate = Carbon::parse($booking->startDate);
        $today = Carbon::today();
        
        if ($startDate->greaterThan($today)) {
            return redirect()->route('bookingHistory')
                ->with('error', 'Pickup is only allowed on or after the start date.');
        }
        
        return view('customer.pickup-form', compact('booking'));
    }
    
    /**
     * Show return form for a booking.
     */
    public function showReturnForm($bookingId)
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $userId = Auth::user()->userID;
        
        $booking = DB::table('booking')
            ->where('bookingID', $bookingId)
            ->where('userID', $userId) // Changed from customerID to userID
            ->where('bookingStatus', 'approved')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select('booking.*', 'vehicles.model', 'vehicles.vehicleType', 'vehicles.plateNumber')
            ->first();
        
        if (!$booking) {
            return redirect()->route('bookingHistory')
                ->with('error', 'Booking not found or not available for return.');
        }
        
        // Check if end date is today or in the past
        $endDate = Carbon::parse($booking->endDate);
        $today = Carbon::today();
        
        if ($endDate->greaterThan($today)) {
            return redirect()->route('bookingHistory')
                ->with('error', 'Return is only allowed on or after the end date.');
        }
        
        return view('customer.return-form', compact('booking'));
    }
    
    /**
     * Process pickup form submission.
     */
    public function processPickup(Request $request, $bookingId)
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $userId = Auth::user()->userID;
        
        $request->validate([
            'pickup_notes' => 'nullable|string|max:500',
            'pickup_condition' => 'required|string|in:good,fair,poor',
            'fuel_level' => 'required|numeric|min:0|max:100',
            'odometer_reading' => 'required|numeric|min:0',
        ]);
        
        DB::table('booking')
            ->where('bookingID', $bookingId)
            ->where('userID', $userId) // Changed from customerID to userID
            ->update([
                'pickup_notes' => $request->pickup_notes,
                'pickup_condition' => $request->pickup_condition,
                'pickup_fuel_level' => $request->fuel_level,
                'pickup_odometer' => $request->odometer_reading,
                'pickup_completed_at' => now(),
                'updated_at' => now(),
            ]);
        
        // Update vehicle status to "in use"
        $booking = DB::table('booking')->where('bookingID', $bookingId)->first();
        if ($booking) {
            DB::table('vehicles')
                ->where('vehicleID', $booking->vehicleID)
                ->update(['status' => 'in use']);
        }
        
        return redirect()->route('bookingHistory')
            ->with('success', 'Pickup completed successfully!');
    }
    
    /**
     * Process return form submission.
     */
    public function processReturn(Request $request, $bookingId)
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $userId = Auth::user()->userID;
        
        $request->validate([
            'return_notes' => 'nullable|string|max:500',
            'return_condition' => 'required|string|in:good,fair,poor',
            'fuel_level' => 'required|numeric|min:0|max:100',
            'odometer_reading' => 'required|numeric|min:0',
            'damage_description' => 'nullable|string|max:500',
            'damage_images' => 'nullable|array',
            'damage_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        DB::transaction(function () use ($request, $bookingId, $userId) {
            // Update booking with return information
            DB::table('booking')
                ->where('bookingID', $bookingId)
                ->where('userID', $userId) // Changed from customerID to userID
                ->update([
                    'return_notes' => $request->return_notes,
                    'return_condition' => $request->return_condition,
                    'return_fuel_level' => $request->fuel_level,
                    'return_odometer' => $request->odometer_reading,
                    'damage_description' => $request->damage_description,
                    'return_completed_at' => now(),
                    'bookingStatus' => 'completed',
                    'updated_at' => now(),
                ]);
            
            
            // Update vehicle status back to "available"
            $booking = DB::table('booking')->where('bookingID', $bookingId)->first();
            if ($booking) {
                DB::table('vehicles')
                    ->where('vehicleID', $booking->vehicleID)
                    ->update(['status' => 'available']);
            }
            
            $booking = DB::table('booking')->where('bookingID', $bookingId)->first();
                        $startTime = Carbon::parse($booking->startDate);
            $returnTime = Carbon::parse($request->return_completed_at ?? now());
            $hoursRented = $returnTime->diffInHours($startTime);

            if ($hoursRented >= 9) {
                $customerProfile = DB::table('customer')->where('userID', $userId)->first();
                
                if ($customerProfile && $customerProfile->matricNumber) {
                    $card = LoyaltyCard::firstOrCreate(
                        ['matricNumber' => $customerProfile->matricNumber],
                        ['stampCount' => 0]
                    );

                    $card->stampCount += 1;
                    $card->save();

                    $newVoucherCode = 'LOYAL-' . strtoupper(Str::random(6));

                    Voucher::create([
                        'voucherCode' => $newVoucherCode,
                        'voucherType' => 'cash_reward', 
                        'value' => 10.00, 
                        'expiryTime' => now()->addMonths(1)->timestamp, // Valid for 1 month
                        'isUsed' => 0,
                        'userID' => $userId // Bind to this specific user
                    ]);
                }
            }

            // Handle damage images upload if any
            if ($request->hasFile('damage_images')) {
                foreach ($request->file('damage_images') as $image) {
                    $path = $image->store('damage_images', 'public');
                    DB::table('damage_reports')->insert([
                        'bookingID' => $bookingId,
                        'image_path' => $path,
                        'description' => $request->damage_description,
                        'created_at' => now(),
                    ]);
                }
            }
            
            // Calculate any additional charges (fuel, damage, late return, etc.)
            $this->calculateFinalCharges($bookingId);
        });
        
        return redirect()->route('bookingHistory')
            ->with('success', 'Vehicle returned successfully! Final charges have been calculated.');
    }
    
    /**
     * Calculate final charges for a booking.
     */
    private function calculateFinalCharges($bookingId)
    {
        $booking = DB::table('booking')->where('bookingID', $bookingId)->first();
        
        if (!$booking) return;
        
        $additionalCharges = 0;
        
        // Calculate fuel charge if return fuel is less than pickup fuel
        if ($booking->pickup_fuel_level && $booking->return_fuel_level) {
            $fuelDifference = $booking->pickup_fuel_level - $booking->return_fuel_level;
            if ($fuelDifference > 10) { // If fuel level dropped more than 10%
                $additionalCharges += ($fuelDifference / 100) * 50; // Example: RM50 per full tank
            }
        }
        
        // Calculate late return charge
        $endDate = Carbon::parse($booking->endDate);
        $returnDate = Carbon::parse($booking->return_completed_at);
        
        if ($returnDate->greaterThan($endDate)) {
            $hoursLate = $returnDate->diffInHours($endDate);
            $additionalCharges += $hoursLate * ($booking->totalPrice / 24); // Hourly rate
        }
        
        // Add damage charges if condition is poor
        if ($booking->return_condition === 'poor') {
            $additionalCharges += 200; // Example damage charge
        }
        
        // Update booking with additional charges
        DB::table('booking')
            ->where('bookingID', $bookingId)
            ->update([
                'additional_charges' => $additionalCharges,
                'final_total' => $booking->totalPrice + $additionalCharges,
                'updated_at' => now(),
            ]);
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
                ->where('userID', $customer->userID) // Changed from customerID to userID
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
            ->where('bookingID', $id)
            ->where('userID', $userId) // Changed from customerID to userID
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select(
                'booking.bookingID',
                'booking.startDate',
                'booking.endDate',
                'booking.bookingStatus',
                'booking.totalPrice',
                'booking.depositAmount',
                'booking.bankNum',
                'booking.penamaBank',
                'booking.bookingDuration',
                'booking.rewardApplied',
                'vehicles.model',
                'vehicles.vehicleType',
                'vehicles.plateNumber'
            )
            ->first();
        
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
        
        return response()->json($booking);
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $userId = Auth::user()->userID;
        
        // Find the booking
        $booking = DB::table('booking')
            ->where('bookingID', $bookingId)
            ->where('userID', $userId)
            ->first();
        
        if (!$booking) {
            return redirect()->route('bookingHistory')
                ->with('error', 'Booking not found.');
        }
        
        // Check if booking can be cancelled
        if (!in_array($booking->bookingStatus, ['pending', 'confirmed'])) {
            return redirect()->route('bookingHistory')
                ->with('error', 'Only pending or confirmed bookings can be cancelled.');
        }
        
        // Update booking status
        DB::table('booking')
            ->where('bookingID', $bookingId)
            ->update([
                'bookingStatus' => 'cancelled',
                'updated_at' => now(),
            ]);
        
        // ALWAYS update vehicle status back to available when cancelled
        // This ensures the vehicle can be booked by other customers immediately
        DB::table('vehicles')
            ->where('vehicleID', $booking->vehicleID)
            ->update(['status' => 'available']);
        
        return redirect()->route('bookingHistory')
            ->with('success', 'Booking cancelled successfully. Vehicle is now available for booking.');
    }

    /**
     * Show documents upload page
     */
    public function showDocuments()
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $user = Auth::user();
        $customer = Customer::where('userID', $user->userID)->first();
        
        // Get uploaded files info for display
        $uploadedFiles = [];
        if ($customer) {
            $uploadedFiles = [
                'ic_passport' => $customer->ic_passport_path ? [
                    'path' => $customer->ic_passport_path,
                    'url' => Storage::url($customer->ic_passport_path),
                    'name' => basename($customer->ic_passport_path)
                ] : null,
                'driving_license' => $customer->driving_license_path ? [
                    'path' => $customer->driving_license_path,
                    'url' => Storage::url($customer->driving_license_path),
                    'name' => basename($customer->driving_license_path)
                ] : null,
                'matric_card' => $customer->matric_card_path ? [
                    'path' => $customer->matric_card_path,
                    'url' => Storage::url($customer->matric_card_path),
                    'name' => basename($customer->matric_card_path)
                ] : null,
            ];
        }
        
        return view('customer.documents', compact('user', 'customer', 'uploadedFiles'));
    }

    /**
     * Handle document upload
     */
    public function uploadDocuments(Request $request)
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $user = Auth::user();
        $customer = Customer::where('userID', $user->userID)->firstOrFail();
        
        $request->validate([
            'ic_passport' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
            'driving_license' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'matric_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
        
        // Upload IC/Passport
        if ($request->hasFile('ic_passport')) {
            $icFile = $request->file('ic_passport');
            $icPath = $icFile->store('documents/' . $user->userID, 'public');
            $customer->ic_passport_path = $icPath;
        }
        
        // Upload Driving License
        if ($request->hasFile('driving_license')) {
            $licenseFile = $request->file('driving_license');
            $licensePath = $licenseFile->store('documents/' . $user->userID, 'public');
            $customer->driving_license_path = $licensePath;
        }
        
        // Upload Matric Card
        if ($request->hasFile('matric_card')) {
            $matricFile = $request->file('matric_card');
            $matricPath = $matricFile->store('documents/' . $user->userID, 'public');
            $customer->matric_card_path = $matricPath;
        }
        
        // Save once
        $customer->save();
        
        return redirect()->route('customer.documents')
            ->with('success', 'Documents uploaded successfully!');
    }
    
    /**
     * Delete a specific document
     */
    public function deleteDocument($type)
    {
        if (auth()->user()->userType !== 'customer') {
            abort(403, 'Unauthorized. Customer access only.');
        }
        
        $user = Auth::user();
        $customer = Customer::where('userID', $user->userID)->firstOrFail();
        
        $validTypes = ['ic_passport', 'driving_license', 'matric_card'];
        
        if (!in_array($type, $validTypes)) {
            return redirect()->back()->with('error', 'Invalid document type.');
        }
        
        // Delete file from storage
        $pathField = $type . '_path';
        if ($customer->$pathField && Storage::disk('public')->exists($customer->$pathField)) {
            Storage::disk('public')->delete($customer->$pathField);
        }
        
        // Clear the path
        $customer->$pathField = null;
        $customer->save();
        
        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

}
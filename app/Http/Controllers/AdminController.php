<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicles;
use App\Models\Bookings;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffInvitationMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Dashboard
    public function index()
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        // Update vehicle statuses first
        $this->updateVehicleStatuses();
        
        // 1. Existing Stats
        $totalVehicles = DB::table('vehicles')->count();
        $availableCount = DB::table('vehicles')->where('status', 'available')->count();
        $onRentCount = DB::table('vehicles')->where('status', 'rented')->count();
        $maintenanceCount = DB::table('vehicles')->where('status', 'maintenance')->count();
        $reservedCount = DB::table('vehicles')->where('status', 'reserved')->count();

        // 2. Get available vehicles (both available and reserved status)
        $availableVehicles = DB::table('vehicles')
            ->whereIn('status', ['available', 'reserved'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // 3. Get vehicles currently on rent
        $onRentVehicles = DB::table('vehicles')
            ->where('status', 'rented')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 4. Pie Chart Data
        $usageData = DB::table('vehicles')
            ->select('model', DB::raw('count(*) as count'))
            ->where('status', 'rented')
            ->groupBy('model')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // 5. Feedback Data
        $feedback = DB::table('return as r')
            ->join('booking as b', 'r.bookingID', '=', 'b.bookingID')
            ->join('users as u', 'b.customerID', '=', 'u.userID')
            ->select('u.name', 'r.feedback', 'r.returnDate', 'r.returnID')
            ->whereNotNull('r.feedback')
            ->where('r.feedback', '!=', '')
            ->orderBy('r.returnDate', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalVehicles', 
            'availableCount', 
            'onRentCount', 
            'maintenanceCount',
            'reservedCount',
            'availableVehicles',
            'onRentVehicles',
            'usageData',
            'feedback'
        ));
    }

    public function getVehicleAvailability(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        
        if ($vehicleId) {
            // Get bookings for specific vehicle
            $bookings = DB::table('booking')
                ->where('vehicleID', $vehicleId)
                ->whereIn('bookingStatus', ['confirmed', 'approved', 'active'])
                ->orderBy('startDate')
                ->get();
            
            // Get vehicle info
            $vehicle = DB::table('vehicles')->where('vehicleID', $vehicleId)->first();
            
            $events = [];
            
            // Add current status as an event starting from today
            $events[] = [
                'vehicle_id' => $vehicleId,
                'model' => $vehicle->model,
                'status' => $vehicle->status,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(), // Show 30 days ahead
                'customer_name' => null
            ];
            
            // Add booking events
            foreach ($bookings as $booking) {
                $customer = DB::table('users')->where('userID', $booking->customerID)->first();
                
                $status = ($booking->startDate <= now() && $booking->endDate >= now()) ? 'rented' : 'reserved';
                
                $events[] = [
                    'vehicle_id' => $vehicleId,
                    'model' => $vehicle->model,
                    'status' => $status,
                    'start_date' => $booking->startDate,
                    'end_date' => $booking->endDate,
                    'customer_name' => $customer ? $customer->name : 'Unknown'
                ];
            }
            
            return response()->json($events);
        }
        
        // Get all vehicles with their next 30 days availability
        $vehicles = DB::table('vehicles')->get();
        $events = [];
        
        foreach ($vehicles as $vehicle) {
            // Get upcoming bookings for this vehicle
            $bookings = DB::table('booking')
                ->where('vehicleID', $vehicle->vehicleID)
                ->where('endDate', '>=', now())
                ->whereIn('bookingStatus', ['confirmed', 'approved', 'active'])
                ->orderBy('startDate')
                ->limit(5) // Limit to 5 upcoming bookings per vehicle
                ->get();
            
            if ($bookings->isEmpty()) {
                // No bookings - vehicle is available
                $events[] = [
                    'vehicle_id' => $vehicle->vehicleID,
                    'model' => $vehicle->model,
                    'status' => 'available',
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(30)->toDateString(),
                    'customer_name' => null
                ];
            } else {
                // Add booking periods
                $lastDate = now();
                
                foreach ($bookings as $booking) {
                    $customer = DB::table('users')->where('userID', $booking->customerID)->first();
                    
                    // Add available period before booking if there's a gap
                    if ($lastDate->lt($booking->startDate)) {
                        $events[] = [
                            'vehicle_id' => $vehicle->vehicleID,
                            'model' => $vehicle->model,
                            'status' => 'available',
                            'start_date' => $lastDate->toDateString(),
                            'end_date' => $booking->startDate,
                            'customer_name' => null
                        ];
                    }
                    
                    // Add booking period
                    $status = ($booking->startDate <= now() && $booking->endDate >= now()) ? 'rented' : 'reserved';
                    
                    $events[] = [
                        'vehicle_id' => $vehicle->vehicleID,
                        'model' => $vehicle->model,
                        'status' => $status,
                        'start_date' => $booking->startDate,
                        'end_date' => $booking->endDate,
                        'customer_name' => $customer ? $customer->name : 'Unknown'
                    ];
                    
                    $lastDate = $booking->endDate;
                }
                
                // Add available period after last booking (next 30 days)
                if ($lastDate->lt(now()->addDays(30))) {
                    $events[] = [
                        'vehicle_id' => $vehicle->vehicleID,
                        'model' => $vehicle->model,
                        'status' => 'available',
                        'start_date' => $lastDate->toDateString(),
                        'end_date' => now()->addDays(30)->toDateString(),
                        'customer_name' => null
                    ];
                }
            }
        }
        
        return response()->json($events);
    }

    // Fleet Management
    public function fleet(Request $request)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        // Update vehicle statuses based on current bookings
        $this->updateVehicleStatuses();
        
        $status = $request->get('status', 'available');
        
        // Special handling: For "available" tab, show BOTH available AND reserved vehicles
        if ($status == 'available') {
            $vehicles = Vehicles::whereIn('status', ['available', 'reserved'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            // For other tabs, show only that specific status
            $vehicles = Vehicles::where('status', $status)
                        ->orderBy('created_at', 'desc')
                        ->get();
        }
        
        // Counts for display
        $totalCount = Vehicles::count();
        $availableCount = Vehicles::whereIn('status', ['available', 'reserved'])->count(); // Count both
        $onRentCount = Vehicles::where('status', 'rented')->count();
        $maintenanceCount = Vehicles::where('status', 'maintenance')->count();
        
        // Optional: Count reserved separately for info display
        $reservedCount = Vehicles::where('status', 'reserved')->count();

        return view('admin.fleet', compact(
            'vehicles', 
            'totalCount', 
            'availableCount', 
            'onRentCount', 
            'maintenanceCount',
            'reservedCount',
            'status'
        ));
    }

    // Show create vehicle form
    public function createVehicle()
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        return view('admin.fleet_create');
    }

    // Store new vehicle
    public function storeVehicle(Request $request)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'vehicleType' => 'required',
            'plateNumber' => 'required|unique:vehicles',
            'pricePerDay' => 'required|numeric',
            'seat' => 'required|integer',
            'transmission' => 'required|in:Manual,Automatic',
            'ac' => 'required|in:1,0',
            'fuelType' => 'nullable|string|max:50',
            'fuelLevel' => 'nullable|integer|min:0|max:100',
            'vehiclePhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'model' => $validated['model'],
            'vehicleType' => $validated['vehicleType'],
            'plateNumber' => $validated['plateNumber'],
            'pricePerDay' => $validated['pricePerDay'],
            'seat' => $validated['seat'],
            'transmission' => $validated['transmission'],
            'ac' => $validated['ac'] == '1' ? 1 : 0,
            'fuelType' => $request->fuelType ?? 'Petrol',
            'fuelLevel' => $request->fuelLevel ?? 100,
            'pricePerHour' => $validated['pricePerDay'] / 10,
            'status' => 'available',
        ];

        // Handle photo upload
        if ($request->hasFile('vehiclePhoto')) {
            $photo = $request->file('vehiclePhoto');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $path = $photo->storeAs('vehicle_photos', $filename, 'public');
            $data['vehiclePhoto'] = $path;
        }

        Vehicles::create($data);

        return redirect()->route('admin.fleet')->with('success', 'Vehicle added to fleet successfully!');
    }

    // Update vehicle
    public function updateVehicle(Request $request, $vehicleID)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        $vehicle = Vehicles::findOrFail($vehicleID);
        
        $validated = $request->validate([
            'model' => 'required|string',
            'plateNumber' => 'required|unique:vehicles,plateNumber,' . $vehicleID . ',vehicleID',
            'status' => 'required',
            'pricePerDay' => 'required|numeric',
            'transmission' => 'nullable|in:Manual,Automatic',
            'ac' => 'nullable|in:1,0',
            'fuelType' => 'nullable|string|max:50',
            'fuelLevel' => 'nullable|integer|min:0|max:100',
            'seat' => 'required|integer',
            'vehicleType' => 'required|string',
            'vehiclePhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('vehiclePhoto')) {
            // Delete old photo if exists
            if ($vehicle->vehiclePhoto) {
                Storage::delete($vehicle->vehiclePhoto);
            }
            
            // Store new photo
            $path = $request->file('vehiclePhoto')->store('vehicle-photos', 'public');
            $validated['vehiclePhoto'] = $path;
        } else {
            // Keep existing photo
            $validated['vehiclePhoto'] = $vehicle->vehiclePhoto;
        }

        // Convert AC value to integer if provided
        if ($request->has('ac')) {
            $validated['ac'] = $request->ac == '1' ? 1 : 0;
        }

        $vehicle->update($validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully!');
    }

    // Delete vehicle
    public function destroyVehicle($vehicleID)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        $vehicle = Vehicles::findOrFail($vehicleID);
        $vehicle->delete();
        
        return redirect()->back()->with('success', 'Vehicle deleted successfully!');
    }

    // Update vehicle statuses based on bookings
    private function updateVehicleStatuses()
    {
        $vehicles = Vehicles::all();
        $now = Carbon::now();
        
        foreach ($vehicles as $vehicle) {
            // Check for ACTIVE bookings (currently rented)
            $hasActiveBooking = Bookings::where('vehicleID', $vehicle->vehicleID)
                ->where('bookingStatus', 'approved')
                ->where('startDate', '<=', $now)
                ->where('endDate', '>=', $now)
                ->exists();
            
            // Check for FUTURE bookings (reserved)
            $hasFutureBooking = Bookings::where('vehicleID', $vehicle->vehicleID)
                ->where('bookingStatus', 'approved')
                ->where('startDate', '>', $now)
                ->exists();
            
            // Update status based on bookings
            if ($hasActiveBooking && $vehicle->status !== 'rented') {
                $vehicle->status = 'rented';
                $vehicle->save();
            } elseif ($hasFutureBooking && $vehicle->status !== 'reserved') {
                $vehicle->status = 'reserved';
                $vehicle->save();
            } elseif (!$hasActiveBooking && $vehicle->status === 'rented') {
                $vehicle->status = 'available';
                $vehicle->save();
            } elseif (!$hasFutureBooking && $vehicle->status === 'reserved' && !$hasActiveBooking) {
                $vehicle->status = 'available';
                $vehicle->save();
            }
        }
    }

    // Customers Management
    public function customers(Request $request)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        $status = $request->get('status', 'active');
        $isBlacklisted = ($status === 'blacklisted') ? 1 : 0;
        
        // Join with telephone table to get phone numbers
        $customers = DB::table('customer')
            ->join('users', 'customer.userID', '=', 'users.userID')
            ->leftJoin('telephone', 'customer.userID', '=', 'telephone.userID')
            ->where('customer.isBlacklisted', $isBlacklisted)
            ->where('users.userType', 'customer')
            ->select(
                'customer.*', 
                'users.name', 
                'users.email',
                'users.created_at as user_created_at',
                'telephone.phoneNumber'
            )
            ->orderBy('users.created_at', 'desc')
            ->get();
        
        $totalCount = $customers->count();
        
        return view('admin.customers', compact('customers', 'totalCount', 'status'));
    }

    public function updateCustomer(Request $request, $id)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
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

    // ============================================
    // UPDATED STAFF MANAGEMENT SECTION
    // ============================================

    // Staff Management - Show all staff and pending invitations
    public function staff()
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        // Get staff users with invitation status
        $staffs = User::with(['telephone', 'staff', 'inviter'])
            ->whereIn('userType', ['staff', 'admin'])
            ->whereNotIn('invitation_status', ['cancelled']) 
            ->orderByRaw("FIELD(invitation_status, 'pending', 'accepted', 'expired', 'cancelled', 'none')")
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.staff', compact('staffs'));
    }

    // Show invitation form (instead of direct registration form)
    public function createStaff()
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        return view('admin.staff_create');
    }

public function storeStaff(Request $request)
{
    if (auth()->user()->userType !== 'admin') {
        abort(403, 'Unauthorized. Admin access only.');
    }
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'icNumber' => 'required|string|unique:users,icNumber',
        'phoneNumber' => 'required|string|unique:telephone,phoneNumber',
        'password' => 'required|string|min:8|confirmed',
        'position' => 'required|string|max:100',
        'userType' => 'required|in:staff,admin',
    ]);
    
    \DB::beginTransaction();
    
    try {
        // Create user
        $userID = \DB::table('users')->insertGetId([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'icNumber' => $validated['icNumber'],
            'userType' => $validated['userType'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create phone record in telephone table
        \DB::table('telephone')->insert([
            'phoneNumber' => $validated['phoneNumber'],
            'userID' => $userID,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create staff record
        \DB::table('staff')->insert([
            'userID' => $userID,
            'position' => $validated['position'],
            'commissionCount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \DB::commit();
        
        return redirect()->route('admin.staff')
            ->with('success', 'Staff member added successfully!');
            
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Staff creation failed: ' . $e->getMessage());
        
        return back()->withInput()->with('error', 'Failed to create staff member. Please try again.');
    }
}

    // Update existing staff member (for admin to edit staff details)
    public function updateStaff(Request $request, $id)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id . ',userID',
            'position' => 'required|string|max:100',
            'phoneNumber' => 'required|string',
            'commissionCount' => 'required|integer|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update users table
            DB::table('users')->where('userID', $id)->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'updated_at' => now(),
            ]);
            
            // Update telephone table
            $existingPhone = DB::table('telephone')->where('userID', $id)->first();
            
            if ($existingPhone) {
                // Update existing phone if number changed
                if ($existingPhone->phoneNumber != $validated['phoneNumber']) {
                    // Check if new phone exists for another user
                    $phoneExists = DB::table('telephone')
                        ->where('phoneNumber', $validated['phoneNumber'])
                        ->where('userID', '!=', $id)
                        ->exists();
                    
                    if ($phoneExists) {
                        throw new \Exception('Phone number already exists for another user.');
                    }
                    
                    DB::table('telephone')
                        ->where('userID', $id)
                        ->update([
                            'phoneNumber' => $validated['phoneNumber'],
                            'updated_at' => now(),
                        ]);
                }
            } else {
                // Insert new phone record
                DB::table('telephone')->insert([
                    'phoneNumber' => $validated['phoneNumber'],
                    'userID' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Update staff table
            DB::table('staff')->where('userID', $id)->update([
                'position' => $validated['position'],
                'commissionCount' => $validated['commissionCount'],
                'updated_at' => now(),
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Staff member updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error', $e->getMessage()]);
        }
    }

    // Delete staff member
    public function destroyStaff($id)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        DB::beginTransaction();
        
        try {
            // Delete telephone record
            DB::table('telephone')->where('userID', $id)->delete();
            
            // Delete staff record
            DB::table('staff')->where('userID', $id)->delete();
            
            // Delete user record
            DB::table('users')->where('userID', $id)->delete();
            
            DB::commit();
            
            return back()->with('success', 'Staff member deleted successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error', 'Failed to delete staff member. Please try again.']);
        }
    }

    // Reporting
    public function reporting()
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        return view('admin.reporting');
    }

    // Promotions
    public function promotions()
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }
        
        return view('admin.promotions');
    }

    public function bookings()
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $pendingPayments = Bookings::where('bookingStatus', 'pending')->get();

        $pendingApprovals = Bookings::whereIn('bookingStatus', ['paid', 'pending'])->get(); 

        $upcomingPickups = Bookings::where('bookingStatus', 'approved') 
                                  ->whereDate('startDate', '>=', Carbon::today())
                                  ->orderBy('startDate', 'asc')
                                  ->get();

        $pendingReturns = Bookings::whereIn('bookingStatus', ['active', 'rented'])->get();

        $bookingHistory = Bookings::whereIn('bookingStatus', ['completed', 'cancelled', 'rejected'])
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10); 

        return view('admin.bookings', compact(
            'pendingPayments', 
            'pendingApprovals', 
            'upcomingPickups', 
            'pendingReturns', 
            'bookingHistory'
        ));
    }

    public function showBooking($id)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $booking = Bookings::with('customer', 'vehicle', 'payment', 'pickup', 'returnDetail')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }


    public function approvePayment($id)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $booking = Bookings::findOrFail($id);
        $booking->update(['bookingStatus' => 'paid']); 
        
        return redirect()->back()->with('success', 'Payment verified successfully!');
    }

    public function approveBooking($id)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $booking = Bookings::findOrFail($id);
        $booking->update(['bookingStatus' => 'approved']);
        

        return redirect()->back()->with('success', 'Booking approved successfully!');
    }

    public function rejectBooking($id)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $booking = Bookings::findOrFail($id);
        $booking->update(['bookingStatus' => 'rejected']);
                
        return redirect()->route('admin.bookings')->with('success', 'Booking rejected.');
    }

    public function completeReturn($id)
    {
        if (!in_array(auth()->user()->userType, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $booking = Bookings::findOrFail($id);
        $booking->update(['bookingStatus' => 'completed']);
        
        if($booking->vehicle) {
            $booking->vehicle->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Vehicle returned successfully!');
    }

  public function profile()
{
    $user = auth()->user();
    
    // Initialize variables for all user types
    $commissions = collect(); // Empty collection
    $totalCommissions = 0;
    $monthlyCommissions = 0;
    $latestCommissionDate = null;
    
    if ($user->userType === 'staff') {
        // Check if commission table exists
        if (Schema::hasTable('commission')) {
            // First, let's check if we need to add the staff_id column
            if (!Schema::hasColumn('commission', 'staff_id')) {
                // Add staff_id column to the commission table
                Schema::table('commission', function ($table) {
                    $table->unsignedBigInteger('staff_id')->nullable()->after('commissionID');
                });
            }
            
            // Now get commissions for this staff member
            $commissions = DB::table('commission')
                ->where('staff_id', $user->userID)
                ->orderBy('commissionDate', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Calculate statistics
            $totalCommissions = $commissions->count();
            
            // Calculate monthly commissions
            $monthStart = now()->startOfMonth()->format('Y-m-d');
            $monthEnd = now()->endOfMonth()->format('Y-m-d');
            $monthlyCommissions = DB::table('commission')
                ->where('staff_id', $user->userID)
                ->whereBetween('commissionDate', [$monthStart, $monthEnd])
                ->count();
            
            $latestCommissionDate = $commissions->first()->commissionDate ?? null;
        } else {
            // Commission table doesn't exist, create it
            Schema::create('commission', function ($table) {
                $table->id('commissionID');
                $table->unsignedBigInteger('staff_id');
                $table->string('commissionType');
                $table->date('commissionDate');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->foreign('staff_id')->references('userID')->on('users')->onDelete('cascade');
            });
        }
    }
    
    // Also get staff details if available
    $staffDetails = null;
    if ($user->userType === 'staff') {
        $staffDetails = DB::table('staff')->where('userID', $user->userID)->first();
    }
    
    return view('admin.profile', compact(
        'user',
        'commissions',
        'totalCommissions',
        'monthlyCommissions',
        'latestCommissionDate',
        'staffDetails'
    ));
}

/**
 * Add new commission record
 */
public function addCommission(Request $request)
{
    if (auth()->user()->userType !== 'staff') {
        return redirect()->back()->with('error', 'Only staff can add commission records.');
    }
    
    $request->validate([
        'commissionType' => 'required|string|in:booking,referral,upsell,special,corporate,group,other',
        'commissionDate' => 'required|date|before_or_equal:today',
        'notes' => 'nullable|string|max:500',
    ]);
    
    // Make sure commission table has staff_id column
    if (!Schema::hasColumn('commission', 'staff_id')) {
        Schema::table('commission', function ($table) {
            $table->unsignedBigInteger('staff_id')->nullable()->after('commissionID');
        });
    }
    
    DB::table('commission')->insert([
        'staff_id' => auth()->id(),
        'commissionType' => $request->commissionType,
        'commissionDate' => $request->commissionDate,
        'notes' => $request->notes,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect()->back()->with('success', 'Commission record added successfully!');
}

/**
 * Get commission for editing
 */
public function editCommission($id)
{
    $commission = DB::table('commission')->where('commissionID', $id)->first();
    
    if (!$commission) {
        return response()->json([
            'success' => false,
            'message' => 'Commission not found'
        ], 404);
    }
    
    // Check ownership
    if ($commission->staff_id != auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
    
    return response()->json([
        'success' => true,
        'commission' => $commission
    ]);
}

/**
 * Update commission record
 */
public function updateCommission(Request $request, $id)
{
    $commission = DB::table('commission')->where('commissionID', $id)->first();
    
    if (!$commission) {
        return redirect()->back()->with('error', 'Commission record not found.');
    }
    
    // Check ownership
    if ($commission->staff_id != auth()->id()) {
        return redirect()->back()->with('error', 'Unauthorized.');
    }
    
    $request->validate([
        'commissionType' => 'required|string|in:booking,referral,upsell,special,corporate,group,other',
        'commissionDate' => 'required|date|before_or_equal:today',
        'notes' => 'nullable|string|max:500',
    ]);
    
    DB::table('commission')
        ->where('commissionID', $id)
        ->update([
            'commissionType' => $request->commissionType,
            'commissionDate' => $request->commissionDate,
            'notes' => $request->notes,
            'updated_at' => now(),
        ]);
    
    return redirect()->back()->with('success', 'Commission record updated successfully!');
}

/**
 * Delete commission record
 */
public function destroyCommission($id)
{
    $commission = DB::table('commission')->where('commissionID', $id)->first();
    
    if (!$commission) {
        return response()->json([
            'success' => false,
            'message' => 'Commission not found'
        ], 404);
    }
    
    // Check ownership
    if ($commission->staff_id != auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
    
    DB::table('commission')->where('commissionID', $id)->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Commission record deleted'
    ]);
}

/**
 * Update staff bank details
 */
public function updateBankDetails(Request $request)
{
    if (auth()->user()->userType !== 'staff') {
        return redirect()->back()->with('error', 'Only staff can update bank details.');
    }
    
    $request->validate([
        'bank_name' => 'required|string|max:100',
        'bank_account_number' => 'required|string|max:50',
    ]);
    
    $staff = DB::table('staff')->where('userID', auth()->id())->first();
    
    if (!$staff) {
        // Create staff record if it doesn't exist
        DB::table('staff')->insert([
            'userID' => auth()->id(),
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'commissionCount' => 0,
            'position' => 'Staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        // Update existing staff record
        DB::table('staff')
            ->where('userID', auth()->id())
            ->update([
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'updated_at' => now(),
            ]);
    }
    
    return redirect()->back()->with('success', 'Bank details updated successfully!');
}

/**
 * Request commission redemption
 */
public function requestRedemption(Request $request)
{
    if (auth()->user()->userType !== 'staff') {
        return redirect()->back()->with('error', 'Only staff can request redemption.');
    }
    
    $staff = DB::table('staff')->where('userID', auth()->id())->first();
    
    if (!$staff) {
        return redirect()->back()->with('error', 'Staff record not found.');
    }
    
    // Get total commission value (you might need to calculate this differently)
    $totalCommissionValue = DB::table('commission')
        ->where('staff_id', auth()->id())
        ->count(); // This is just count, you might want to sum amounts if you add an amount column
    
    if ($totalCommissionValue <= 0) {
        return redirect()->back()->with('error', 'No commission available for redemption.');
    }
    
    // Create redemption request (you need a redemption_requests table)
    if (!Schema::hasTable('redemption_requests')) {
        Schema::create('redemption_requests', function ($table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('commission_count')->default(0);
            $table->string('status')->default('pending'); // pending, approved, rejected, paid
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('staff_id')->references('userID')->on('users')->onDelete('cascade');
        });
    }
    
    DB::table('redemption_requests')->insert([
        'staff_id' => auth()->id(),
        'commission_count' => $totalCommissionValue,
        'amount' => $totalCommissionValue * 10, // Example: RM10 per commission
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect()->back()->with('success', 'Redemption request submitted! Admin will process it shortly.');
}


}
<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicles;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Dashboard
    public function index()
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }

        // Existing Stats
        $totalVehicles = DB::table('vehicles')->count();
        $availableCount = DB::table('vehicles')->where('status', 'available')->count();
        $onRentCount = DB::table('vehicles')->where('status', 'rented')->count();
        $maintenanceCount = DB::table('vehicles')->where('status', 'maintenance')->count();

        // NEW: Get usage data for the Pie Chart (Top 5 most used models currently on rent)
        $usageData = DB::table('vehicles')
            ->select('model', DB::raw('count(*) as count'))
            ->where('status', 'rented')
            ->groupBy('model')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalVehicles', 
            'availableCount', 
            'onRentCount', 
            'maintenanceCount',
            'usageData'
        ));
    }

    // Fleet Management
    public function fleet(Request $request)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        $status = $request->get('status', 'available');
        
        $vehicles = Vehicles::where('status', $status)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $totalCount = Vehicles::count(); // Total all vehicles
        $availableCount = Vehicles::where('status', 'available')->count();
        $onRentCount = Vehicles::where('status', 'rented')->count();
        $maintenanceCount = Vehicles::where('status', 'maintenance')->count();

        return view('admin.fleet', compact('vehicles', 'totalCount', 'availableCount', 'onRentCount', 'maintenanceCount', 'status'));
    }

    // Show create vehicle form
    public function createVehicle()
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        return view('admin.fleet_create');
    }

    // Store new vehicle
    public function storeVehicle(Request $request)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
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
        ]);

        Vehicles::create($validated + [
            'status' => 'available',
            'fuelType' => $request->fuelType ?? 'Petrol',
            'fuelLevel' => $request->fuelLevel ?? 100,
            'pricePerHour' => $request->pricePerDay / 10,
            'ac' => $request->ac == '1' ? 1 : 0,
        ]);

        return redirect()->route('admin.fleet')->with('success', 'Vehicle added to fleet successfully!');
    }

    // Update vehicle
    public function updateVehicle(Request $request, $vehicleID)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
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
        ]);

        if ($request->has('ac')) {
            $validated['ac'] = $request->ac == '1' ? 1 : 0;
        }

        $vehicle->update($validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully!');
    }

    // Delete vehicle
    public function destroyVehicle($vehicleID)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        $vehicle = Vehicles::findOrFail($vehicleID);
        $vehicle->delete();
        
        return redirect()->back()->with('success', 'Vehicle deleted successfully!');
    }

    // Customers Management
    public function customers(Request $request)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
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
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
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

    // Staff Management
    public function staff()
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        // Use Eloquent with relationship
        $staffs = User::with('telephone')
            ->where('userType', 'staff')
            ->with('staff') // Load staff details too
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.staff', compact('staffs'));
    }

    public function createStaff()
    {
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
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create user WITHOUT phoneNumber in users table
            $userID = DB::table('users')->insertGetId([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'icNumber' => $validated['icNumber'],
                'userType' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create phone record in telephone table
            DB::table('telephone')->insert([
                'phoneNumber' => $validated['phoneNumber'],
                'userID' => $userID,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create staff record
            DB::table('staff')->insert([
                'userID' => $userID,
                'position' => $validated['position'],
                'commissionCount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.staff')->with('success', 'Staff member added successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error', 'Failed to create staff member. Please try again.']);
        }
    }

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

    public function destroyStaff($id)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        try {
            // Delete staff record first (due to foreign key constraint)
            DB::table('staff')->where('userID', $id)->delete();
            
            // Delete user record
            DB::table('users')->where('userID', $id)->delete();
            
            return back()->with('success', 'Staff member deleted successfully!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error', 'Failed to delete staff member. Please try again.']);
        }
    }

    // Reporting
    public function reporting()
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        return view('admin.reporting');
    }

    // Promotions
    public function promotions()
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        return view('admin.promotions');
    }
}
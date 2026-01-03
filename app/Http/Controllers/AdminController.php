<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        return view('admin.dashboard');
    }

   public function customers(Request $request)
    {
        if (auth()->user()->userType !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }
        
        $status = $request->get('status', 'active');
        $isBlacklisted = ($status === 'blacklisted') ? 1 : 0;
        
        $customers = DB::table('customer')
            ->join('users', 'customer.userID', '=', 'users.userID')
            ->where('customer.isBlacklisted', $isBlacklisted)
            ->where('users.userType', 'customer')
            ->select('customer.*', 'users.name', 'users.email')
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
            'phoneNumber' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string|max:100',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Check if phone exists in telephone table
            $phoneExists = DB::table('telephone')->where('phoneNumber', $validated['phoneNumber'])->exists();
            
            if (!$phoneExists) {
                DB::table('telephone')->insert([
                    'phoneNumber' => $validated['phoneNumber'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Create user
            $userID = DB::table('users')->insertGetId([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'icNumber' => $validated['icNumber'],
                'phoneNumber' => $validated['phoneNumber'],
                'userType' => 'staff',
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
            return back()->withInput()->withErrors(['error' => 'Failed to create staff member. Please try again.']);
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
        // Update users table - only update phoneNumber if it's a column
        $userUpdateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'updated_at' => now(),
        ];
        
        // Check if users table has phoneNumber column
        if (Schema::hasColumn('users', 'phoneNumber')) {
            $userUpdateData['phoneNumber'] = $validated['phoneNumber'];
        }
        
        DB::table('users')->where('userID', $id)->update($userUpdateData);
        
        // Also update telephone table if phone number changed
        if ($validated['phoneNumber']) {
            $existingPhone = DB::table('telephone')
                ->where('phoneNumber', $validated['phoneNumber'])
                ->exists();
            
            if (!$existingPhone) {
                DB::table('telephone')->updateOrInsert(
                    ['phoneNumber' => $validated['phoneNumber']],
                    [
                        'phoneNumber' => $validated['phoneNumber'],
                        'updated_at' => now(),
                    ]
                );
            }
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
        return back()->withInput()->withErrors(['error' => 'Failed to update staff member. Please try again.']);
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
            return back()->withErrors(['error' => 'Failed to delete staff member. Please try again.']);
        }
    }
}



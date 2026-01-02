<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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

public function staff()
{
    if (auth()->user()->userType !== 'admin') {
        abort(403, 'Unauthorized. Admin access only.');
    }
    
    $staff = User::where('userType', 'staff')->get(); // or 'role', 'staff' depending on your column
    return view('admin.staff', compact('staff'));
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


}


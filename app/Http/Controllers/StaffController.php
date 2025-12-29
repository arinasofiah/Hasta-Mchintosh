<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        // Check if user is staff
        if (auth()->user()->userType !== 'staff') {
            abort(403, 'Unauthorized. Staff access only.');
        }
        
        return view('staff.dashboard');
    }
}
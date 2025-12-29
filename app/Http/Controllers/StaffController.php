<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display staff dashboard.
     */
    public function index()
    {
        return view('staff.dashboard');
    }
}
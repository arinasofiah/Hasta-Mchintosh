<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\Vehicles;

class StaffController extends Controller
{
    public function index()
    {
        if (auth()->user()->userType !== 'staff') {
            abort(403, 'Unauthorized. Staff access only.');
        }
        
        // $pendingBookings = Bookings::where('bookingStatus', 'pending')->count(); 
        
        // $pendingPayments = Bookings::whereHas('payment', function($q) {
        //     $q->where('paymentStatus', 'pending');
        // })->count();

        // $pendingReturns = Vehicles::where('status', 'rented')->count();

        return view('staff.dashboard');
    }

    public function confirmation()
    {
        $bookings = Bookings::where('bookingStatus', 'pending')
                           ->with('customer')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('staff.booking_confirmation', compact('bookings'));
    }

    // public function verifyPayment()
    // {
    //     $bookings = Bookings::whereHas('payment', function($q) {
    //                             $q->where('paymentStatus', 'pending');
    //                        })
    //                        ->with(['customer', 'payment'])
    //                        ->orderBy('created_at', 'desc')
    //                        ->paginate(10);

    //     return view('staff.verify_payment', compact('bookings'));
    // }

    public function viewPickup()
    {
        $bookings = Bookings::where('bookingStatus', 'approved')
                           ->with(['customer', 'vehicle']) 
                           ->orderBy('startDate', 'asc') 
                           ->paginate(10);

        return view('staff.view_pickup', compact('bookings'));
    }
    public function verifyReturn()
    {
        $bookings = Bookings::where('bookingStatus', 'approved')
                           ->with(['customer', 'vehicle'])
                           ->orderBy('endDate', 'asc') 
                           ->paginate(10);

        return view('staff.verify_return', compact('bookings'));
    }

    public function history()
    {
        $bookings = Bookings::with(['customer', 'vehicle'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(15);

        return view('staff.booking_history', compact('bookings'));
    }

    public function updateStatus()
    {
        $vehicles = Vehicles::orderBy('vehicleID', 'asc')->paginate(10);

        return view('staff.vehicle_status', compact('vehicles'));
    }
}
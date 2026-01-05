<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\Vehicles;
use App\Models\LoyaltyCard; 
use App\Models\Promotion;

class StaffController extends Controller
{
    public function index()
    {
        if (auth()->user()->userType !== 'staff') {
            abort(403, 'Unauthorized. Staff access only.');
        }
        
        $pendingBookings = Bookings::where('bookingStatus', 'pending')->count(); 
        
        $pendingPayments = Bookings::whereHas('payment', function($q) {
            $q->where('paymentStatus', 'pending');
        })->count();

        $pendingReturns = Vehicles::where('status', 'rented')->count();

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

    public function verifyPayment()
    {
        $bookings = Bookings::whereHas('payment', function($q) {
                                $q->where('paymentStatus', 'pending');
                           })
                           ->with(['customer', 'payment'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('staff.verify_payment', compact('bookings'));
    }

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

    public function commission()
    {
        $user = auth()->user(); 
        $staff = $user->staff; 

        return view('staff.commission', compact('user', 'staff'));
    }

    public function updateBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'bank_account_number' => 'required|string',
        ]);

        auth()->user()->staff->update([
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
        ]);

        return back()->with('success', 'Bank details updated!');
    }

    public function redeem()
    {
        $staff = auth()->user()->staff;

        if ($staff->commissionCount <= 0) {
            return back()->with('error', 'No commission to redeem!');
        }

        return back()->with('success', 'Redemption request sent to Admin!');
    }

    public function approvePayment($id)
    {
        $booking = Bookings::with(['customer', 'payment'])->findOrFail($id);

        if ($booking->payment) {
            $booking->payment->update(['paymentStatus' => 'completed']);
        }
        
        $booking->update(['bookingStatus' => 'approved']);
        $user = $booking->customer; 
        
        $customerProfile = \DB::table('customer')->where('userID', $user->userID)->first();

        if ($customerProfile && $booking->bookingDuration > 9) {
            
            $matric = $customerProfile->matricNumber;

            $card = LoyaltyCard::firstOrCreate(
                ['matricNumber' => $matric],
                ['stampCount' => 0]
            );
            $card->increment('stampCount');
            if ($card->stampCount % 5 === 0) {
                $reward = Promotion::where('discountType', 'fixed')->first();

                if ($reward) {
                    $card->promotions()->attach($reward->promoID, [
                        'is_used' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        return back()->with('success', 'Payment verified, Booking approved & Loyalty Stamp added!');
    }

    public function showBooking($id)
    {
        $booking = \App\Models\Booking::with('customer')->where('bookingID', $id)->firstOrFail();
        
        return view('staff.booking_detail', compact('booking'));
    }

    public function approveBooking($id)
    {
        $booking = \App\Models\Booking::where('bookingID', $id)->firstOrFail();
        
        $booking->bookingStatus = 'Confirmed'; 
        $booking->save();
        
        return redirect()->route('staff.dashboard')->with('success', 'Booking has been approved successfully!');
    }
}
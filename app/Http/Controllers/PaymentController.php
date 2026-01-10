<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show remaining balance payment page
     */
    public function showRemainingPayment($bookingID)
{
    $booking = Bookings::with('vehicle')->findOrFail($bookingID);
    
    // Authorization check
    if ($booking->customerID != Auth::id() && $booking->userID != Auth::id()) {
        abort(403, 'Unauthorized access.');
    }
    
    // Check if booking is approved
    if ($booking->bookingStatus !== 'approved') {
        return back()->with('error', 'Booking must be approved before paying remaining balance.');
    }
    
    // Check if this is a deposit-only booking
    if ($booking->pay_amount_type !== 'deposit') {
        return back()->with('error', 'Only deposit-only bookings have remaining balance to pay.');
    }
    
    // Calculate remaining balance
    $totalPaid = $booking->payments()->where('paymentStatus', 'approved')->sum('amount');
    $totalCost = $booking->totalPrice + 50; // Rental + RM50 deposit
    $remainingBalance = max(0, $totalCost - $totalPaid);
    
    if ($remainingBalance <= 0) {
        return back()->with('error', 'No remaining balance to pay.');
    }
    
    return view('payment.remaining', [
        'booking' => $booking,
        'remainingBalance' => $remainingBalance
    ]);
}

public function processRemainingPayment(Request $request, $bookingID)
{
    $request->validate([
        'bank_name' => 'required|string|max:50',
        'bank_owner_name' => 'required|string|max:100',
        'payment_receipt' => 'required|image|max:5120',
    ]);
    
    $booking = Bookings::findOrFail($bookingID);
    
    // Authorization check
    if ($booking->customerID != Auth::id() && $booking->userID != Auth::id()) {
        abort(403, 'Unauthorized access.');
    }
    
    // Check remaining balance
    $totalPaid = $booking->payments()->where('paymentStatus', 'approved')->sum('amount');
    $totalCost = $booking->totalPrice + 50;
    $remainingBalance = max(0, $totalCost - $totalPaid);
    
    if ($remainingBalance <= 0) {
        return back()->with('error', 'No remaining balance to pay.');
    }
    
    // Handle receipt upload
    $receiptPath = $request->file('payment_receipt')->store('payment-receipts', 'public');
    
    // Create payment record
    $payment = Payment::create([
        'bookingID' => $bookingID,
        'bankName' => $request->bank_name,
        'bankOwnerName' => $request->bank_owner_name,
        'amount' => $remainingBalance,
        'paymentType' => 'remaining',
        'receiptImage' => $receiptPath,
        'paymentStatus' => 'pending',
        'paymentDate' => now(),
    ]);
    
    return redirect()->route('bookingHistory')
        ->with('success', 'Remaining balance payment submitted for approval!');
}
    
    /**
     * Show payment history for a booking
     */
    public function paymentHistory($bookingID)
    {
        $booking = Bookings::with(['payments', 'vehicle'])->findOrFail($bookingID);
        
        if ($booking->customerID != Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('payment.history', [
            'booking' => $booking,
            'payments' => $booking->payments()->orderBy('created_at', 'desc')->get()
        ]);
    }
}
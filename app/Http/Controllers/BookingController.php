<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Models\Vehicles;
use App\Models\Bookings;
use App\Models\PickUp;
use App\Models\ReturnCar;
use App\Models\LoyaltyCard;
use App\Models\Promotion;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function showForm($vehicleID, Request $request)
    {
        $vehicle = Vehicles::findOrFail($vehicleID);

        $pickupDate = $request->query('pickup_date', now()->toDateString());
        $pickupTime = $request->query('pickup_time', '08:00');
        $returnDate = $request->query('return_date', now()->addDay()->toDateString());
        $returnTime = $request->query('return_time', '08:00');

        $start = Carbon::parse("$pickupDate $pickupTime");
        $end = Carbon::parse("$returnDate $returnTime");
        $durationHours = $end->diffInHours($start);
        $durationDays = ceil($durationHours / 24);

        if ($durationDays < 1) {
            $durationDays = 1;
        }
        
        $totalPrice = $durationDays * $vehicle->pricePerDay;

        return view('bookingform', [
            'vehicle' => $vehicle,
            'pickupDate' => old('pickup_date', $pickupDate),
            'pickupTime' => old('pickup_time', $pickupTime),
            'returnDate' => old('return_date', $returnDate),
            'returnTime' => old('return_time', $returnTime),
            'durationDays' => $durationDays,
            'durationHours' => $durationHours,
            'totalPrice' => $totalPrice
        ]);
    }

    public function checkPromotion(Request $request)
    {
        // Get duration from the AJAX request
        $bookingDuration = $request->input('duration'); 
        $amount = $request->amount;

        $promotion = Promotion::where('applicableDays', '<=', $bookingDuration)
            ->orderBy('discountValue', 'desc') // Get the best discount
            ->first();

        if ($promotion) {
            // Calculate discount
            if ($promotion->discountType == 'percentage') {
                $discount = ($amount * $promotion->discountValue) / 100;
            } else {
                $discount = $promotion->discountValue;
            }

            return response()->json([
                'hasPromotion' => true,
                'promoID' => $promotion->promoID,
                'discount' => $discount,
                'message' => 'Applied: ' . $promotion->title
            ]);
        }

        return response()->json([
            'hasPromotion' => false,
            'discount' => 0
        ]);
    }

    public function showPaymentForm(Request $request)
    {
        $vehicleID = $request->input('vehicleID');
        if (!$vehicleID) {
            return back()->withErrors(['error' => 'Vehicle ID is missing']);
        }

        $vehicle = Vehicles::findOrFail($vehicleID);

        // Inputs from Booking Form
        $pickupDate = $request->input('pickup_date');
        $pickupTime = $request->input('pickup_time');
        $returnDate = $request->input('return_date');
        $returnTime = $request->input('return_time');
        $pickupLocation = $request->input('pickupLocation');
        $returnLocation = $request->input('returnLocation');
        $destination = $request->input('destination');
        $remark = $request->input('remark');
        $forSomeoneElse = $request->boolean('for_someone_else');
        
        // Driver Info
        $matricNumber = $request->input('matricNumber');
        $licenseNumber = $request->input('licenseNumber');
        $college = $request->input('college');
        $faculty = $request->input('faculty');
        $depoBalance = $request->input('depoBalance', 0);

        // Pricing Inputs
        $subtotal = $request->input('subtotal');
        $promotionDiscount = $request->input('promotionDiscount', 0);
        $total = $request->input('total');
        $duration = $request->input('duration');

        // Validation
        if (!$pickupDate || !$pickupTime || !$returnDate || !$returnTime) {
            return back()->withErrors(['error' => 'Booking dates/times are missing.']);
        }

        try {
            $pickup = Carbon::parse($pickupDate . ' ' . $pickupTime);
            $return = Carbon::parse($returnDate . ' ' . $returnTime);
            if ($return <= $pickup) {
                return back()->withErrors(['error' => 'Return date must be after pickup date.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Invalid date/time format.']);
        }

        // Calculations
        $diffHours = $return->diffInHours($pickup);
        $days = floor($diffHours / 24);
        $remainingHours = $diffHours % 24;

        $calculatedSubtotal = ($days * $vehicle->pricePerDay) + ($remainingHours * $vehicle->pricePerHour);
        $finalSubtotal = $subtotal ?? $calculatedSubtotal;
        $finalTotal = $total ?? ($finalSubtotal - $promotionDiscount);
        
        // Default deposit is 30% of total
        $deposit = $finalTotal * 0.3;

        $pickupLocationDisplay = $pickupLocation;
        $returnLocationDisplay = $returnLocation;
        $dateRange = Carbon::parse($pickupDate)->format('d M Y') . ' - ' . Carbon::parse($returnDate)->format('d M Y');
        $durationText = $duration ?? (
            $days > 0 
                ? "{$days} day" . ($days > 1 ? 's' : '') . " {$remainingHours} hour" . ($remainingHours != 1 ? 's' : '')
                : "{$diffHours} hour" . ($diffHours != 1 ? 's' : '')
        );

        // Fetch Promo Details if applied
        $promoDetails = null;
        if ($request->input('promo_id')) {
            $promoDetails = Promotion::find($request->input('promo_id'));
        }

        // ✅ NEW: Fetch Eligible Vouchers for the view
        // Logic: Vouchers that are NOT used and NOT expired
        $eligibleVouchers = Voucher::where('userID', Auth::id()) // <--- ADD THIS LINE
                                   ->where('isUsed', 0)
                                   ->where('expiryTime', '>', time()) // Check timestamp
                                   ->get()
                                   ->map(function($v) {
                                        return (object)[
                                            'promoID' => $v->voucherCode,
                                            'title' => ($v->voucherType == 'free_hour' ? 'Free Hour' : 'Cash Voucher'),
                                            'code' => $v->voucherCode,
                                            'discountValue' => $v->value
                                        ];
                                   });

        return view('paymentform', compact(
            'vehicle',
            'pickupLocationDisplay',
            'returnLocationDisplay',
            'dateRange',
            'durationText',
            'finalSubtotal',
            'promotionDiscount',
            'finalTotal',
            'deposit',
            'pickupDate',
            'pickupTime',
            'returnDate',
            'returnTime',
            'pickupLocation',
            'returnLocation',
            'destination',
            'remark',
            'forSomeoneElse',
            'matricNumber',
            'licenseNumber',
            'college',
            'faculty',
            'depoBalance',
            'promoDetails',
            'eligibleVouchers' // ✅ Passed to view
        ));
    }

    public function validateVoucher(Request $request)
    {
        $code = $request->code; // User enters the ID (e.g., 105)
        
        // Find voucher in DB
        $voucher = Voucher::where('voucherCode', $code)
                          ->where('userID', Auth::id())
                          ->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid Voucher Code'
            ]);
        }

        // Check if used
        if ($voucher->isUsed) {
            return response()->json([
                'valid' => false,
                'message' => 'This voucher has already been used'
            ]);
        }

        // Check expiry (expiryTime is int timestamp)
        if ($voucher->expiryTime < time()) {
            return response()->json([
                'valid' => false,
                'message' => 'This voucher has expired'
            ]);
        }

        // Success
        return response()->json([
            'valid' => true,
            'message' => 'Voucher Applied Successfully!',
            'amount' => $voucher->value,
            'voucher_id' => $voucher->voucherCode
        ]);
    }

    public function confirmBooking(Request $request)
{
    \Log::info('Booking request received', $request->all());

    try {
        $request->validate([
            'vehicleID' => 'required|exists:vehicles,vehicleID',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required',
            'pickupLocation' => 'required',
            'returnLocation' => 'required',
            'bank_name' => 'required',
            'bank_owner_name' => 'required',
            'payAmount' => 'required|in:full,deposit',
            'payment_receipt' => 'required|image|max:5120',
        ]);

        DB::beginTransaction();

        $vehicle = Vehicles::findOrFail($request->vehicleID);
        
        // Calculate duration
        $start = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $end = Carbon::parse($request->return_date . ' ' . $request->return_time);
        $durationDays = $end->diffInDays($start);
        if ($durationDays < 1) $durationDays = 1;
        
        // Calculate total price
        $totalPrice = $durationDays * $vehicle->pricePerDay;
        
        // Handle file upload
        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
        }
        
        // Save Booking
        $booking = new Bookings();
        
        // If your table has userID column:
        $booking->userID = auth()->id();
        
        // If your table doesn't have userID, only set customerID:
        $booking->customerID = auth()->id();
        $booking->vehicleID = $vehicle->vehicleID;
        $booking->bankNum = $request->bank_owner_name ?? '';
        $booking->penamaBank = $request->bank_name;
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $durationDays;
        $booking->totalPrice = $totalPrice;
        
        // Determine deposit
        if ($request->payAmount == 'deposit') {
            $booking->depositAmount = $totalPrice * 0.5;
        } else {
            $booking->depositAmount = $totalPrice; // Full payment
        }
        
        $booking->bookingStatus = 'pending';
        
        // Save WITHOUT using mass assignment to avoid userID issue
        $booking->save();
        
        // Update vehicle status
        $vehicle->status = 'rented';
        $vehicle->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Booking submitted successfully!',
            'booking_id' => $booking->bookingID
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Booking error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Booking failed: ' . $e->getMessage()
        ], 500);
    }
}

    public function bookingHistory()
    {
        $bookings = Bookings::where('customerID', auth()->id())
            ->orWhere('userID', auth()->id())
            ->with(['vehicle', 'pickup', 'return'])
            ->orderBy('created_at', 'desc')
            ->get();

        $active = $bookings->whereIn('bookingStatus', ['approved', 'confirmed']);
        $pending = $bookings->where('bookingStatus', 'pending');
        $completed = $bookings->where('bookingStatus', 'completed');
        $cancelled = $bookings->where('bookingStatus', 'cancelled');

        return view('bookingHistory', compact('active', 'pending', 'completed', 'cancelled'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vehicles;
use App\Models\Bookings;
use App\Models\PickUp;
use App\Models\ReturnCar;
use App\Models\LoyaltyCard;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Require authentication for all methods in this controller
        //$this->middleware('auth');
    }

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
        
        // FIX: Use ceil() instead of floor() to round up partial days
        $durationDays = ceil($durationHours / 24);
        
        // Ensure minimum 1 day for any booking
        if ($durationDays < 1) {
            $durationDays = 1;
        }
        
        $totalPrice = $durationDays * $vehicle->pricePerDay;

        $user = Auth::user();
        $vouchers = [];

        $customer = DB::table('customer')->where('userID', $user->userID)->first();

        if ($customer) {
            $card = LoyaltyCard::where('matricNumber', $customer->matricNumber)->first();
            
            if ($card) {
                $vouchers = $card->promotions()
                                 ->wherePivot('is_used', false)
                                 ->get();
            }
        }

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
        try {
            $duration = $request->input('duration');
            $amount = $request->input('amount');
            $vehicleID = $request->input('vehicleID');

            \Illuminate\Support\Facades\Log::info("DEBUG CHECK: Duration={$duration}, Amount={$amount}, VehicleID={$vehicleID}");

            if (!$vehicleID) {
                throw new \Exception("Vehicle ID is missing from the request!");
            }

            $vehicle = \App\Models\Vehicles::find($vehicleID);
            if (!$vehicle) {
                throw new \Exception("Vehicle found in DB is NULL for ID: {$vehicleID}");
            }

            \Illuminate\Support\Facades\Log::info("DEBUG CHECK: Vehicle Found -> " . $vehicle->model);

            $promotion = \App\Models\Promotion::where('applicableDays', '<=', $duration)
                ->where(function($query) use ($vehicle) {
                    $query->where('applicableModel', 'All')
                          ->orWhere('applicableModel', $vehicle->model);
                })
                ->orderBy('discountValue', 'desc')
                ->first();

            if ($promotion) {
                \Illuminate\Support\Facades\Log::info("DEBUG CHECK: Promo Match -> " . $promotion->title);
                
                $discount = 0;
                if ($promotion->discountType == 'percentage') {
                    $discount = $amount * ($promotion->discountValue / 100);
                } else {
                    $discount = $promotion->discountValue;
                }

                return response()->json([
                    'hasPromotion' => true,
                    'discount' => min($discount, $amount),
                    'promoID' => $promotion->promoID,
                    'message' => 'Applied: ' . $promotion->title,
                    'debug_info' => 'Success'
                ]);
            }

            \Illuminate\Support\Facades\Log::info("DEBUG CHECK: No promo matched.");
            return response()->json([
                'hasPromotion' => false, 
                'discount' => 0, 
                'debug_info' => 'No rules matched your criteria (Duration: '.$duration.')'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("CHECK PROMOTION CRASHED: " . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());

        return response()->json([
            'hasPromotion' => false,
            'discount' => 0
        ]);
        }
    }

    // Show payment form 
    public function showPaymentForm(Request $request)
    {
        \Log::info('Payment form request received', $request->all());

        // Validate required fields
        $validated = $request->validate([
            'vehicleID' => 'required',
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'return_date' => 'required',
            'return_time' => 'required',
            'pickupLocation' => 'required',
            'returnLocation' => 'required',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'duration' => 'required',
        ]);

        $vehicle = Vehicles::findOrFail($request->vehicleID);

        $pickupDate = $request->pickup_date;
        $pickupTime = $request->pickup_time;
        $returnDate = $request->return_date;
        $returnTime = $request->return_time;

        // Calculate dates and prices
        $pickup = Carbon::parse($pickupDate . ' ' . $pickupTime);
        $return = Carbon::parse($returnDate . ' ' . $returnTime);
        
        $dateRange = $pickup->format('d M Y') . ' - ' . $return->format('d M Y');
        
        $finalSubtotal = $request->subtotal;
        $originalRentalPrice = $finalSubtotal;
        $promotionDiscount = $request->promotionDiscount ?? 0;
        $finalTotal = $request->total;
        $deposit = 50;

        // ✅ 1. Get delivery charge (critical fix!)
        $deliveryCharge = $request->input('delivery_charge', 0);

        \Log::info('Delivery Charge Received:', ['delivery_charge' => $deliveryCharge, 'all_request' => $request->all()]);

        // Get promo details if exists
        $promoDetails = null;
        if ($request->promo_id) {
            $promoDetails = Promotion::find($request->promo_id);
        }

        // ✅ 2. Get eligible vouchers
        $eligibleVouchers = collect();
        if (Auth::check()) {
            $eligibleVouchers = Voucher::where('userID', Auth::id())
                ->where('isUsed', 0)
                ->where('expiryTime', '>', now()->timestamp)
                ->get()
                ->map(function ($v) {
                    return (object)[
                        'promoID' => $v->voucherID,
                        'title' => $v->voucherType == 'free_hour' ? 'Free Hour' : 'Cash Voucher',
                        'code' => $v->voucherCode,
                        'discountValue' => $v->value
                    ];
                });
        }

        // ✅ 3. Get loyalty card data
        $loyaltyCard = null;
        if (Auth::check()) {
            $user = Auth::user();
            $customerProfile = DB::table('customer')->where('userID', $user->userID)->first();
            
            if ($customerProfile) {
                $loyaltyCard = LoyaltyCard::firstOrCreate(
                    ['matricNumber' => $customerProfile->matricNumber],
                    ['stampCount' => 0]
                );
            }
        }

        return view('paymentform', [
            'vehicle' => $vehicle,
            'pickupDate' => $pickupDate,
            'pickupTime' => $pickupTime,
            'returnDate' => $returnDate,
            'returnTime' => $returnTime,
            'pickupLocation' => $request->pickupLocation,
            'returnLocation' => $request->returnLocation,
            'destination' => $request->destination,
            'remark' => $request->remark,
            'forSomeoneElse' => $request->for_someone_else ?? 0,
            'matricNumber' => $request->driver_matric ?? '',
            'licenseNumber' => $request->driver_license ?? '',
            'college' => $request->driver_college ?? '',
            'faculty' => $request->driver_faculty ?? '',
            'depoBalance' => $request->driver_deposit ?? 0,
            'finalSubtotal' => $finalSubtotal,
            'promotionDiscount' => $promotionDiscount,
            'finalTotal' => $finalTotal,
            'dateRange' => $dateRange,
            'durationText' => $request->duration,
            'deposit' => $deposit,
            'promoDetails' => $promoDetails,
            'eligibleVouchers' => $eligibleVouchers,
            'loyaltyCard' => $loyaltyCard,
            'originalRentalPrice' => $originalRentalPrice,
            'deliveryCharge' => $deliveryCharge,
        ]);
    }

    public function validateVoucher(Request $request)
    {
        $code = $request->input('code');
        $vehicleID = session('pending_booking.vehicleID') ?? $request->input('vehicleID'); // We need vehicle to calc free hours
        
        $voucher = Voucher::where('voucherCode', $code)
            ->where('isUsed', 0)
            ->where('expiryTime', '>=', now()->timestamp) // Assuming expiryTime is stored as timestamp per your model
            ->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired voucher.']);
        }

        // Calculate Discount Amount
        $discountAmount = 0;

        if ($voucher->voucherType == 'cash_reward') {
            $discountAmount = $voucher->value;
        } elseif ($voucher->voucherType == 'free_hour') {
            // If it's free hours, we need the vehicle price per hour
            $vehicle = Vehicles::find($vehicleID);
            if ($vehicle) {
                $discountAmount = $voucher->value * $vehicle->pricePerHour;
            }
        }

        return response()->json([
            'valid' => true,
            'voucher_id' => $voucher->voucherCode,
            'amount' => $discountAmount,
            'message' => 'Voucher Applied: ' . ucfirst(str_replace('_', ' ', $voucher->voucherType))
        ]);
    }

    // Confirm booking and payment - FIXED VERSION
 public function confirmBooking(Request $request)
{
    \Log::info('=== START BOOKING CONFIRMATION ===');
    \Log::info('Request data:', $request->all());
    \Log::info('User ID:', ['user_id' => auth()->id()]);

    try {
        // Validate request data
        $validated = $request->validate([
            'vehicleID' => 'required|exists:vehicles,vehicleID',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'return_date' => 'required|date',
            'return_time' => 'required',
            'pickupLocation' => 'required|string',
            'returnLocation' => 'required|string',
            'bank_name' => 'required|string',
            'bank_owner_name' => 'required|string',
            'payAmount' => 'required|in:full,deposit',
            'payment_receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'delivery_charge' => 'nullable|numeric',
        ]);

        \Log::info('Validation passed successfully');

        // Get vehicle
        $vehicle = Vehicles::findOrFail($request->vehicleID);
        
        if ($vehicle->status !== 'available') {
            \Log::warning('Vehicle not available', ['vehicleID' => $request->vehicleID]);
            return back()->with('error', 'Vehicle no longer available');
        }

        // Calculate duration and price
        $pickup = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $return = Carbon::parse($request->return_date . ' ' . $request->return_time);
        $durationHours = $return->diffInHours($pickup);
        
        \Log::info('Date calculation:', [
            'pickup' => $pickup->format('Y-m-d H:i:s'),
            'return' => $return->format('Y-m-d H:i:s'),
            'duration_hours' => $durationHours
        ]);
        
        $deliveryCharge = $request->input('delivery_charge', 0);
        
        $days = floor($durationHours / 24);
        $remainingHours = $durationHours % 24;
        $rentalPrice = ($days * $vehicle->pricePerDay) + ($remainingHours * $vehicle->pricePerHour);
        
        \Log::info('Price calculation:', [
            'days' => $days,
            'remaining_hours' => $remainingHours,
            'price_per_day' => $vehicle->pricePerDay,
            'price_per_hour' => $vehicle->pricePerHour,
            'rental_price' => $rentalPrice
        ]);
        
        // Apply promotion discount
        $promotionDiscount = 0;
        if ($request->promo_id) {
            $promo = Promotion::find($request->promo_id);
            if ($promo) {
                $promotionDiscount = ($rentalPrice * $promo->discountValue) / 100;
                \Log::info('Promotion applied:', [
                    'promo_id' => $promo->promoID,
                    'discount_percent' => $promo->discountValue,
                    'discount_amount' => $promotionDiscount
                ]);
            }
        }
        
        $totalPrice = max(0, $rentalPrice - $promotionDiscount) + $deliveryCharge;
        
        \Log::info('Final pricing:', [
            'rental_price' => $rentalPrice,
            'promotion_discount' => $promotionDiscount,
            'delivery_charge' => $deliveryCharge,
            'total_price' => $totalPrice,
            'payment_type' => $request->payAmount
        ]);

        // Upload payment receipt
        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            try {
                $file = $request->file('payment_receipt');
                $filename = 'receipt_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $receiptPath = $file->storeAs('receipts', $filename, 'public');
                \Log::info('Receipt uploaded successfully:', ['path' => $receiptPath]);
            } catch (\Exception $e) {
                \Log::error('Failed to upload receipt:', ['error' => $e->getMessage()]);
                return back()->with('error', 'Failed to upload payment receipt')->withInput();
            }
        } else {
            \Log::warning('No payment receipt file found in request');
            return back()->with('error', 'Payment receipt is required')->withInput();
        }

        // ========== START DATABASE TRANSACTION ==========
        DB::beginTransaction();
        
        try {
            // Create booking with PENDING status (awaiting admin approval)
            \Log::info('Creating booking record...');
            $booking = new Bookings();
            $booking->customerID = auth()->id();
            $booking->vehicleID = $vehicle->vehicleID;
            $booking->startDate = $request->pickup_date;
            $booking->endDate = $request->return_date;
            $booking->bookingDuration = $durationHours;
            $booking->bookingStatus = 'pending'; // ✅ PENDING - awaits admin approval
            $booking->reservation_expires_at = now()->addHours(24); // ✅ Give admin 24h to review
            $booking->totalPrice = $totalPrice;
            $booking->delivery_charge = $deliveryCharge;
            $booking->promo_id = $request->promo_id;
            $booking->destination = $request->destination;
            $booking->remark = $request->remark;
            $booking->bank_name = $request->bank_name;
            $booking->bank_owner_name = $request->bank_owner_name;
            $booking->pay_amount_type = $request->payAmount;
            $booking->payment_receipt_path = $receiptPath;
            
            if ($request->filled('for_someone_else') && $request->for_someone_else == 1) {
                $booking->for_someone_else = true;
                $booking->driver_matric_number = $request->matricNumber;
                $booking->driver_license_number = $request->licenseNumber;
                $booking->driver_college = $request->college;
                $booking->driver_faculty = $request->faculty;
                $booking->driver_deposit_balance = $request->depoBalance ?? 0;
            }
            
            $booking->save();
            \Log::info('Booking saved successfully:', ['bookingID' => $booking->bookingID]);

            // ========== CREATE PAYMENT RECORD ==========
            \Log::info('--- CREATING PAYMENT RECORD ---');
            
            // Calculate payment amount
            $paymentAmount = ($request->payAmount == 'deposit') ? 50 : ($totalPrice + 50);
            
            \Log::info('Payment calculation:', [
                'payment_type' => $request->payAmount,
                'calculated_amount' => $paymentAmount,
                'total_price' => $totalPrice,
                'formula' => $request->payAmount == 'deposit' ? 'RM50 fixed' : 'totalPrice + RM50'
            ]);
            
            // Create payment with COMPLETED status (payment verified automatically)
            $paymentData = [
                'bookingID' => $booking->bookingID,
                'bankName' => $request->bank_name,
                'bankOwnerName' => $request->bank_owner_name,
                'amount' => $paymentAmount,
                'paymentType' => $request->payAmount,
                'paymentStatus' => 'completed', // ✅ COMPLETED - payment received
                'receiptImage' => $receiptPath,
                'paymentDate' => now()->format('Y-m-d'),
                'qrPayment' => null,
            ];
            
            \Log::info('Payment data prepared:', $paymentData);
            
            $payment = Payment::create($paymentData);
            \Log::info('PAYMENT CREATED SUCCESSFULLY!', [
                'paymentID' => $payment->paymentID,
                'bookingID' => $payment->bookingID,
                'amount' => $payment->amount,
                'type' => $payment->paymentType,
                'status' => $payment->paymentStatus
            ]);

            // ========== CREATE PICKUP RECORD ==========
            \Log::info('Creating pickup record...');
            PickUp::create([
                'bookingID' => $booking->bookingID,
                'pickupDate' => $request->pickup_date,
                'pickupTime' => $request->pickup_time,
                'location' => $request->pickupLocation,
            ]);
            \Log::info('Pickup record created');

            // ========== CREATE RETURN RECORD ==========
            \Log::info('Creating return record...');
            ReturnCar::create([
                'bookingID' => $booking->bookingID,
                'returnDate' => $request->return_date,
                'returnTime' => $request->return_time,
                'location' => $request->returnLocation,
            ]);
            \Log::info('Return record created');

            // ========== UPDATE VEHICLE STATUS ==========
            \Log::info('Updating vehicle status...');
            // Vehicle stays 'reserved' until admin approves
            $vehicle->status = 'reserved';
            $vehicle->save();
            \Log::info('Vehicle status set to reserved (awaiting approval)');

            // ========== COMMIT TRANSACTION ==========
            DB::commit();
            \Log::info('=== BOOKING CONFIRMATION COMPLETED SUCCESSFULLY ===');
            
            return redirect()->route('bookinghistory')->with('success', 'Payment submitted successfully! Your booking is awaiting admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction failed, rolling back:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('VALIDATION FAILED:', ['errors' => $e->errors()]);
        return back()->withErrors($e->errors())->withInput();
        
    } catch (\Exception $e) {
        \Log::error('UNEXPECTED EXCEPTION:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        
        return back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
    }
}

    public function approveBooking($bookingID)
    {
        try {
            DB::beginTransaction();
            
            $booking = Bookings::findOrFail($bookingID);
            $booking->bookingStatus = 'approved';
            $booking->save();
            
            // === Issue Loyalty Stamp ===
            if (auth()->check()) {
                $customerProfile = DB::table('customer')->where('userID', auth()->id())->first();
                if ($customerProfile) {
                    $loyaltyCard = LoyaltyCard::firstOrCreate(
                        ['matricNumber' => $customerProfile->matricNumber],
                        ['stampCount' => 0]
                    );

                    // Calculate rental hours from booking
                    $start = Carbon::parse($booking->startDate . ' 00:00:00');
                    $end = Carbon::parse($booking->endDate . ' 23:59:59');
                    $rentalHours = $end->diffInHours($start);
                    
                    // Only give stamp if rental duration >= 7 hours
                    if ($rentalHours >= 7) {
                        $loyaltyCard->stampCount += 1;
                        $loyaltyCard->save();
                    }
                }
            }
            
            // Check if booking has already started
            $now = Carbon::now();
            $startDate = Carbon::parse($booking->startDate);
            
            // If booking hasn't started yet, mark vehicle as "reserved"
            // If booking has started or in progress, mark as "in_use"
            if ($startDate->isFuture()) {
                // Booking hasn't started yet - mark vehicle as "reserved"
                $booking->vehicle->status = 'reserved';
                $booking->vehicle->save();
            } else {
                // Booking has started or is in progress - use default logic
                $booking->updateVehicleStatus();
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Booking approved successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approve booking error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve booking: ' . $e->getMessage());
        }
    }


    public function bookingHistory()
{
    $userId = auth()->id();
    
    // Eager load payments to avoid N+1 queries
    $bookings = Bookings::with(['vehicle', 'pickup', 'returnCar', 'payments'])
        ->where(function($query) use ($userId) {
            $query->where('customerID', $userId)
                ->orWhere('userID', $userId);
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($booking) {
            // Calculate total paid from COMPLETED payments
            $totalPaid = $booking->payments
                ->where('paymentStatus', 'completed')
                ->sum('amount');
            
            // Get the latest payment to determine payment type
            $latestPayment = $booking->payments
                ->where('paymentStatus', 'completed')
                ->sortByDesc('paymentDate')
                ->first();
            
            // Use booking's pay_amount_type if no payment found
            $paymentType = $latestPayment ? $latestPayment->paymentType : $booking->pay_amount_type;
            $bankName = $latestPayment ? $latestPayment->bankName : $booking->bank_name;
            $bankOwnerName = $latestPayment ? $latestPayment->bankOwnerName : $booking->bank_owner_name;
            
            // Calculate amounts correctly
            $rentalPrice = $booking->totalPrice ?? 0;
            $depositAmount = 50; // Fixed deposit amount
            
            // Calculate like the frontend expects
            // Total Cost is ALWAYS rental + RM50 deposit
            $totalCost = $rentalPrice + $depositAmount;
            
            // Calculate remaining balance based on payment type
            if ($paymentType == 'deposit') {
                // For deposit payments: customer owes rental portion after paying RM50
                // They might have paid more than RM50 (partial payment of rental)
                // So remaining = totalCost - totalPaid
                $remainingBalance = max(0, $totalCost - $totalPaid);
            } elseif ($paymentType == 'full') {
                // For full payments: remaining = totalCost - totalPaid
                $remainingBalance = max(0, $totalCost - $totalPaid);
            } else {
                // No payment type or other type: full amount due
                $remainingBalance = $totalCost;
            }
            
            // Add dynamic properties
            $booking->totalPaid = $totalPaid;
            $booking->totalCost = $totalCost;
            $booking->remainingBalance = $remainingBalance;
            $booking->isFullyPaid = $remainingBalance <= 0;
            $booking->pay_amount_type = $paymentType;
            $booking->bank_name = $bankName;
            $booking->bank_owner_name = $bankOwnerName;
            $booking->depositAmount = $depositAmount;
            
            // Build datetime strings using related models
            $pickupDate = $booking->pickup?->pickupDate ?? $booking->startDate;
            $pickupTime = $booking->pickup?->pickupTime ?? '08:00:00';
            $returnDate = $booking->returnCar?->returnDate ?? $booking->endDate;
            $returnTime = $booking->returnCar?->returnTime ?? '16:00:00';

            $booking->pickupDateTime = "$pickupDate $pickupTime";
            $booking->returnDateTime = "$returnDate $returnTime";

            return $booking;
        });

    $now = Carbon::now();

    $active = $bookings->filter(function($booking) use ($now) {
        return in_array($booking->bookingStatus, ['approved', 'confirmed'])
            && $now->gte(Carbon::parse($booking->pickupDateTime))
            && $now->lte(Carbon::parse($booking->returnDateTime));
    });

    $pending = $bookings->filter(function($booking) use ($now) {
        return $booking->bookingStatus === 'pending'
            || (
                in_array($booking->bookingStatus, ['approved', 'confirmed'])
                && $now->lt(Carbon::parse($booking->pickupDateTime))
            );
    });

    $completed = $bookings->where('bookingStatus', 'completed');
    $cancelled = $bookings->where('bookingStatus', 'cancelled');

    return view('bookingHistory', compact('active', 'pending', 'completed', 'cancelled'));
}

}
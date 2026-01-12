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
    try {
        $request->validate([
            'vehicleID' => 'required|exists:vehicles,vehicleID',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'return_time' => 'required',
            'pickupLocation' => 'required',
            'returnLocation' => 'required',
            'bank_name' => 'required|string',
            'bank_owner_name' => 'required|string',
            'payAmount' => 'required|in:full,deposit',
            'payment_receipt' => 'required|image|max:5120',
        ]);

        DB::beginTransaction();

        // VEHICLE
        $vehicle = Vehicles::findOrFail($request->vehicleID);

        if ($vehicle->status !== 'available') {
            return back()->with('error', 'Vehicle not available');
        }

        // DATE CALCULATION
        $pickup = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $return = Carbon::parse($request->return_date . ' ' . $request->return_time);

        $hours = max(1, $pickup->diffInHours($return));
        $days = ceil($hours / 24);

        // PRICE
        $rentalPrice = $days * $vehicle->pricePerDay;
        $deposit = 50;

        // PROMO
        $promoDiscount = 0;
        if ($request->promo_id) {
            $promo = Promotion::find($request->promo_id);
            if ($promo) {
                $promoDiscount = $promo->discountType === 'percentage'
                    ? ($rentalPrice * $promo->discountValue / 100)
                    : $promo->discountValue;
            }
        }

        $finalPrice = max(0, $rentalPrice - $promoDiscount);

        // RECEIPT
        $receiptPath = $request->file('payment_receipt')
            ->store('receipts', 'public');

        // BOOKING
        $booking = new Bookings();
        $booking->userID = auth()->id(); 
        $booking->vehicleID = $vehicle->vehicleID;
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $hours;
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addHours(24);
        $booking->totalPrice = $finalPrice;
        $booking->depositAmount = $deposit;
        $booking->promo_id = $request->promo_id;
        $booking->voucher_id = $request->voucher_id;
        $booking->destination = $request->destination;
        $booking->remark = $request->remark;
        $booking->bank_name = $request->bank_name;
        $booking->bank_owner_name = $request->bank_owner_name;
        $booking->pay_amount_type = $request->payAmount;
        $booking->payment_receipt_path = $receiptPath;

        // FOR SOMEONE ELSE
        if ($request->for_someone_else == 1) {
            $booking->for_someone_else = 1;
            $booking->driver_matric_number = $request->matricNumber;
            $booking->driver_license_number = $request->licenseNumber;
            $booking->driver_college = $request->college;
            $booking->driver_faculty = $request->faculty;
            $booking->driver_deposit_balance = $request->depoBalance ?? 0;
        }

        $booking->save();

        // PAYMENT
        Payment::create([
            'bookingID' => $booking->bookingID,
            'bankName' => $request->bank_name,
            'bankOwnerName' => $request->bank_owner_name,
            'amount' => $request->payAmount === 'deposit' ? 50 : ($finalPrice + 50),
            'paymentType' => $request->payAmount,
            'paymentStatus' => 'completed',
            'receiptImage' => $receiptPath,
            'paymentDate' => now(),
        ]);

        // PICKUP
        PickUp::create([
            'bookingID' => $booking->bookingID,
            'pickupDate' => $request->pickup_date,
            'pickupTime' => $request->pickup_time,
            'location' => $request->pickupLocation,
        ]);

        // RETURN
        ReturnCar::create([
            'bookingID' => $booking->bookingID,
            'returnDate' => $request->return_date,
            'returnTime' => $request->return_time,
            'location' => $request->returnLocation,
        ]);

        // VEHICLE STATUS
        $vehicle->status = 'reserved';
        $vehicle->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Payment received. Awaiting admin approval.',
            'redirect_url' => route('bookingHistory')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Booking confirmation failed: ' . $e->getMessage());

        return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
        ], 500);
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
                $booking->bank_name = $bank_name;
                $booking->bank_owner_name = $bank_owner_name;
                $booking->depositAmount = $depositAmount;
                
                // Build datetime strings using related models
                $pickupDate = $booking->pickup?->pickupDate ?? $booking->startDate;
                $pickupTime = $booking->pickup?->pickupTime ?? '08:00:00';
                $returnDate = $booking->returnCar?->returnDate ?? $booking->endDate;
                $returnTime = $booking->returnCar?->returnTime ?? '16:00:00';

                $booking->pickupDateTime = "$pickupDate $pickupTime";
                $booking->returnDateTime = "$returnDate $returnTime";
                $booking->isPickupCompleted = $booking->pickup?->pickupComplete ?? false;

                return $booking;
            });

        \Log::info('Booking type:', ['class' => get_class($bookings->first())]);

        $now = Carbon::now();

        $active = $bookings->filter(function($booking) use ($now) {
            return in_array($booking->bookingStatus, ['approved', 'confirmed'])
                && $now->lte(Carbon::parse($booking->returnDateTime));
        });

        $pending = $bookings->filter(function($booking) use ($now) {
            return $booking->bookingStatus === 'pending';
        });

        $completed = $bookings->where('bookingStatus', 'completed');
        $cancelled = $bookings->where('bookingStatus', 'cancelled');

        return view('bookingHistory', compact('active', 'pending', 'completed', 'cancelled'));
    }

}
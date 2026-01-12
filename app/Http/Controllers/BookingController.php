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
        $promotionDiscount = $request->promotionDiscount ?? 0;
        $finalTotal = $request->total;
        $deposit = $finalTotal * 0.5; // 50% deposit

        // Get promo details if exists
        $promoDetails = null;
        if ($request->promo_id) {
            $promoDetails = Promotion::find($request->promo_id);
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
    \Log::info('Booking confirmation request received', $request->all());

    try {
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
            'delivery_charge' => 'nullable|numeric', // Add this validation
        ]);

        $vehicle = Vehicles::findOrFail($request->vehicleID);
        
        if ($vehicle->status !== 'available') {
            return back()->with('error', 'Vehicle no longer available');
        }

        $pickup = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $return = Carbon::parse($request->return_date . ' ' . $request->return_time);
        $durationHours = $return->diffInHours($pickup);
        
        // Get delivery charge
        $deliveryCharge = $request->input('delivery_charge', 0);
        
        // Calculate rental price
        $days = floor($durationHours / 24);
        $remainingHours = $durationHours % 24;
        $rentalPrice = ($days * $vehicle->pricePerDay) + ($remainingHours * $vehicle->pricePerHour);
        
        // Apply promotion discount
        $promotionDiscount = 0;
        if ($request->promo_id) {
            $promo = Promotion::find($request->promo_id);
            if ($promo) {
                $promotionDiscount = ($rentalPrice * $promo->discountValue) / 100;
            }
        }
        
        // Final total = (rental - promo) + delivery
        $totalPrice = max(0, $rentalPrice - $promotionDiscount) + $deliveryCharge;
        $depositAmount = $totalPrice * 0.5;

        // Upload payment receipt
        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $file = $request->file('payment_receipt');
            $filename = 'receipt_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $receiptPath = $file->storeAs('receipts', $filename, 'public');
        }

        // Create booking
        $booking = new Bookings();
        $booking->customerID = auth()->id();
        $booking->vehicleID = $vehicle->vehicleID;
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $durationHours;
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addMinutes(30);
        $booking->totalPrice = $totalPrice;
        $booking->depositAmount = $depositAmount;
        $booking->delivery_charge = $deliveryCharge; // Save delivery charge
        $booking->promo_id = $request->promo_id;
        $booking->destination = $request->destination;
        $booking->remark = $request->remark;
        $booking->bank_name = $request->bank_name;
        $booking->bank_owner_name = $request->bank_owner_name;
        $booking->pay_amount_type = $request->payAmount;
        $booking->payment_receipt_path = $receiptPath;
        
        // If booking for someone else
        if ($request->filled('for_someone_else') && $request->for_someone_else == 1) {
            $booking->for_someone_else = true;
            $booking->driver_matric_number = $request->matricNumber;
            $booking->driver_license_number = $request->licenseNumber;
            $booking->driver_college = $request->college;
            $booking->driver_faculty = $request->faculty;
            $booking->driver_deposit_balance = $request->depoBalance ?? 0;
        }
        
        $booking->save();

        // Create pickup record
        PickUp::create([
            'bookingID' => $booking->bookingID,
            'pickupDate' => $request->pickup_date,
            'pickupTime' => $request->pickup_time,
            'location' => $request->pickupLocation,
        ]);

        // Create return record
        ReturnCar::create([
            'bookingID' => $booking->bookingID,
            'returnDate' => $request->return_date,
            'returnTime' => $request->return_time,
            'location' => $request->returnLocation,
        ]);

        // Update vehicle status
        $vehicle->status = 'unavailable';
        $vehicle->save();

        return redirect()->route('bookinghistory')->with('success', 'Payment submitted successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed', $e->errors());
        return back()->withErrors($e->errors())->withInput();
        
    } catch (\Exception $e) {
        \Log::error('Booking confirmation failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to submit payment. Please try again.')->withInput();
    }
    // ✅ NO CODE AFTER THIS POINT!
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
        // Use Eloquent with eager loading
        $bookings = Bookings::with(['vehicle', 'pickup', 'returnCar'])
            ->where(function($query) use ($userId) {
                $query->where('customerID', $userId)
                    ->orWhere('userID', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($booking) {
                // Calculate total paid from payments relationship
                $totalPaid = $booking->payments()
                    ->where('paymentStatus', 'approved')
                    ->sum('amount');

                // Add dynamic properties
                $booking->totalPaid = $totalPaid;
                $booking->totalCost = $booking->totalPrice + 50;
                $booking->remainingBalance = max(0, $booking->totalCost - $totalPaid);
                $booking->isFullyPaid = $booking->remainingBalance <= 0;

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
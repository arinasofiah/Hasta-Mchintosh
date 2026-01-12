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
                'discount' => 0,
                'error' => true,
                'message' => 'CRITICAL ERROR: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    public function showPaymentForm(Request $request)
    {
        $vehicleID = $request->input('vehicleID');
        if (!$vehicleID) {
            return back()->withErrors(['error' => 'Vehicle ID is missing']);
        }

        $vehicle = Vehicles::findOrFail($vehicleID);

        $pickupDate = $request->input('pickup_date');
        $pickupTime = $request->input('pickup_time');
        $returnDate = $request->input('return_date');
        $returnTime = $request->input('return_time');
        $pickupLocation = $request->input('pickupLocation');
        $returnLocation = $request->input('returnLocation');
        $destination = $request->input('destination');
        $remark = $request->input('remark');
        $forSomeoneElse = $request->boolean('for_someone_else');
        
        $matricNumber = $request->input('matricNumber');
        $licenseNumber = $request->input('licenseNumber');
        $college = $request->input('college');
        $faculty = $request->input('faculty');
        $depoBalance = $request->input('depoBalance', 0);

        $subtotal = $request->input('subtotal');
        $promotionDiscount = $request->input('promotionDiscount', 0);
        $total = $request->input('total');
        $duration = $request->input('duration');

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

        $diffHours = $return->diffInHours($pickup);
        $days = floor($diffHours / 24);
        $remainingHours = $diffHours % 24;

        // Calculate base rental price (days + hours)
        $calculatedSubtotal = ($days * $vehicle->pricePerDay) + ($remainingHours * ($vehicle->pricePerHour ?? 0));
        
        // Use the subtotal from the booking form if available, otherwise use calculated
        $finalSubtotal = $subtotal ?? $calculatedSubtotal;
        
        // Delivery charge
        $deliveryCharge = $request->input('delivery_charge', 0);
        
        // Original rental price (before any discounts)
        $originalRentalPrice = $finalSubtotal;
        
        // Final total after promotion discount (but before voucher)
        $finalTotal = $originalRentalPrice - $promotionDiscount;
        
        $deposit = 50; // Fixed deposit

        $pickupLocationDisplay = $pickupLocation;
        $returnLocationDisplay = $returnLocation;
        $dateRange = Carbon::parse($pickupDate)->format('d M Y') . ' - ' . Carbon::parse($returnDate)->format('d M Y');
        $durationText = $duration ?? (
            $days > 0 
                ? "{$days} day" . ($days > 1 ? 's' : '') . ($remainingHours > 0 ? " {$remainingHours} hour" . ($remainingHours != 1 ? 's' : '') : '')
                : "{$diffHours} hour" . ($diffHours != 1 ? 's' : '')
        );

        $promoDetails = null;
        if ($request->input('promo_id')) {
            $promoDetails = Promotion::find($request->input('promo_id'));
        }

        $eligibleVouchers = Voucher::where('userID', Auth::id())
                                   ->where('isUsed', 0)
                                   ->where('expiryTime', '>', time())
                                   ->get()
                                   ->map(function($v) {
                                        return (object)[
                                            'promoID' => $v->voucherID,
                                            'title' => ($v->voucherType == 'free_hour' ? 'Free Hour' : 'Cash Voucher'),
                                            'code' => $v->voucherCode,
                                            'discountValue' => $v->value
                                        ];
                                   });

        // === Loyalty Card Data ===
        $loyaltyCard = null;
        $user = Auth::user();

        if ($user) {
            $customerProfile = DB::table('customer')->where('userID', $user->userID)->first();
            if ($customerProfile) {
                $loyaltyCard = LoyaltyCard::firstOrCreate(
                    ['matricNumber' => $customerProfile->matricNumber],
                    ['stampCount' => 0]
                );
            }
        }
                                   
        return view('paymentform', compact(
            'vehicle',
            'pickupLocationDisplay',
            'returnLocationDisplay',
            'dateRange',
            'durationText',
            'finalSubtotal',
            'originalRentalPrice',
            'deliveryCharge',
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
            'eligibleVouchers',
            'loyaltyCard'
        ));
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

    public function confirmBooking(Request $request)
    {
        \Log::info('Booking request received', $request->all());

        try {
            $request->validate([
                'vehicleID' => 'required|exists:vehicles,vehicleID',
                'pickup_date' => 'required|date',
                'pickup_time' => 'required',
                'return_date' => 'required|date|after_or_equal:pickup_date',
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
            
            // Parse dates and times correctly
            $start = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
            $end = Carbon::parse($request->return_date . ' ' . $request->return_time);
            
            // Ensure return is after pickup
            if ($end->lte($start)) {
                throw new \Exception('Return date/time must be after pickup date/time');
            }
            
            $durationHours = $end->diffInHours($start);
            $durationDays = floor($durationHours / 24);
            $remainingHours = $durationHours % 24;
            
            // Minimum 1 hour
            if ($durationHours < 1) {
                $durationHours = 1;
                $durationDays = 0;
                $remainingHours = 1;
            }
            
            \Log::info('Duration calculation', [
                'start' => $start->toDateTimeString(),
                'end' => $end->toDateTimeString(),
                'durationHours' => $durationHours,
                'durationDays' => $durationDays,
                'remainingHours' => $remainingHours
            ]);
            
            // Try to get the rental price from the payment form first (more accurate)
            // If not available, calculate it
            if ($request->has('base_rental_price') && $request->input('base_rental_price') > 0) {
                $baseRentalPrice = floatval($request->input('base_rental_price'));
                \Log::info('Using rental price from payment form', ['price' => $baseRentalPrice]);
            } else {
                // Calculate base rental price (days + hours)
                $baseRentalPrice = ($durationDays * $vehicle->pricePerDay) + ($remainingHours * ($vehicle->pricePerHour ?? 0));
                \Log::info('Calculated rental price', ['price' => $baseRentalPrice]);
            }
            
            // Get delivery charge
            $deliveryCharge = floatval($request->input('delivery_charge', 0));
            
            // Total before discounts
            $totalBeforeDiscounts = $baseRentalPrice + $deliveryCharge;
            
            $promoDiscount = 0;
            if ($request->promo_id) {
                $promo = Promotion::find($request->promo_id);
                if ($promo) {
                    if ($promo->discountType == 'percentage') {
                        $promoDiscount = $basePrice * ($promo->discountValue / 100);
                    } else {
                        $promoDiscount = $promo->discountValue;
                    }
                }
            }
            $voucherDiscount = 0;
            if ($request->voucher_id) {
                $voucher = Voucher::where('voucherCode', $request->voucher_id)->where('isUsed', 0)->first();
                if ($voucher) {
                    if ($voucher->voucherType == 'cash_reward') {
                        $voucherDiscount = $voucher->value;
                    } elseif ($voucher->voucherType == 'free_hour') {
                        $voucherDiscount = $voucher->value * $vehicle->pricePerHour;
                    }
                    
                    // Mark voucher as used
                    $voucher->isUsed = 1;
                    $voucher->save();
                }
            }
            
            // Calculate final rental price after all discounts (but never negative)
            $finalRentalPrice = max(0, $totalBeforeDiscounts - $promotionDiscount - $voucherDiscount);
            
            \Log::info('Price calculation', [
                'baseRentalPrice' => $baseRentalPrice,
                'deliveryCharge' => $deliveryCharge,
                'totalBeforeDiscounts' => $totalBeforeDiscounts,
                'promotionDiscount' => $promotionDiscount,
                'voucherDiscount' => $voucherDiscount,
                'finalRentalPrice' => $finalRentalPrice,
                'totalWithDeposit' => $finalRentalPrice + 50
            ]);
            
            $receiptPath = null;
            if ($request->hasFile('payment_receipt')) {
                $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
            }
            
            // Generate booking code
            $count = Bookings::whereDate('created_at', today())->count() + 1;
            $bookingCode = 'B' . date('ymd') . str_pad($count, 5, '0', STR_PAD_LEFT);
            
            // Create booking
            $booking = new Bookings();
            $booking->userID = auth()->id();
            $booking->customerID = auth()->id();
            $booking->vehicleID = $vehicle->vehicleID;
            $booking->startDate = $request->pickup_date;
            $booking->endDate = $request->return_date;
            $booking->destination = $request->input('destination', '');
            $booking->remark = $request->input('remark', '');
            $booking->driver_matric_number = $request->input('matricNumber', '');
            $booking->driver_license_number = $request->input('licenseNumber', '');
            $booking->driver_college = $request->input('college', '');
            $booking->driver_faculty = $request->input('faculty', '');
            $booking->driver_deposit_balance = $request->input('depoBalance', 0);
            $booking->bank_name = $request->bank_name;
            $booking->bank_owner_name = $request->bank_owner_name;
            $booking->pay_amount_type = $request->payAmount;
            $booking->payment_receipt_path = $receiptPath;
            
            // Store duration in days (round up if there are remaining hours)
            $bookingDuration = $durationDays;
            if ($remainingHours > 0) {
                $bookingDuration += 1; // Round up
            }
            $booking->bookingDuration = $bookingDuration;
            
            \Log::info('Storing booking duration', [
                'durationDays' => $durationDays,
                'remainingHours' => $remainingHours,
                'bookingDuration' => $bookingDuration
            ]);
            
            // totalPrice = TOTAL VEHICLE COST (Rental + Deposit)
            // This matches what's shown as "Total Vehicle Cost" in the payment form
            $booking->totalPrice = $finalRentalPrice + 50;
            
            \Log::info('Storing totalPrice', [
                'finalRentalPrice' => $finalRentalPrice,
                'deposit' => 50,
                'totalPrice' => $booking->totalPrice
            ]);
            
            $booking->promo_id = $request->input('promo_id') ?: null;
            $booking->voucher_id = $request->input('voucher_id') ?: null;
            $booking->booking_code = $bookingCode;
            $booking->for_someone_else = $request->boolean('for_someone_else') ? 1 : 0;
            
            // Bank number and name
            $booking->bankNum = $request->input('bankNum', '');
            $booking->penamaBank = $request->input('penamaBank', '');
            
            // depositAmount = Fixed security deposit (always RM50)
            $booking->depositAmount = 50;
            
            // pay_amount_type already stores whether they chose 'deposit' or 'full'
            // This tells us if they paid RM50 now or (rental + RM50) now
            
            // Set status to pending
            $booking->bookingStatus = 'pending';
            
            $booking->save();
            
            // Mark voucher as used if applicable
            if ($request->input('voucher_id')) {
                $voucher = Voucher::find($request->input('voucher_id'));
                if ($voucher) {
                    $voucher->isUsed = 1;
                    $voucher->save();
                }
            }
            
            // Create pickup record
            $pickup = new PickUp();
            $pickup->bookingID = $booking->bookingID;
            $pickup->pickupDate = $request->pickup_date;
            $pickup->pickupTime = $request->pickup_time;
            $pickup->pickupLocation = $request->pickupLocation;
            $pickup->save();
            
            // Create return record
            $returnCar = new ReturnCar();
            $returnCar->bookingID = $booking->bookingID;
            $returnCar->returnDate = $request->return_date;
            $returnCar->returnTime = $request->return_time;
            $returnCar->returnLocation = $request->returnLocation;
            $returnCar->save();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking submitted successfully! Waiting for admin approval.',
                'booking_id' => $booking->bookingID,
                'booking_code' => $booking->booking_code 
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
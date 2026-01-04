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
    // Show the booking form
    public function showForm($vehicleID, Request $request)
    {
        $vehicle = Vehicles::findOrFail($vehicleID);

        $pickupDate = $request->query('pickup_date', now()->toDateString());
        $pickupTime = $request->query('pickup_time', '08:00');
        $returnDate = $request->query('return_date', now()->addDay()->toDateString());
        $returnTime = $request->query('return_time', '08:00');

        // Calculate duration and total price
        $start = Carbon::parse("$pickupDate $pickupTime");
        $end = Carbon::parse("$returnDate $returnTime");
        $durationHours = $end->diffInHours($start);
        $durationDays = ceil($durationHours / 24);

        if ($durationDays < 1) {
            $durationDays = 1;
        }
        
        $totalPrice = $durationDays * $vehicle->pricePerDay;

        //voucher logic
        $user = Auth::user();
        $vouchers = [];

        // Find customer profile using User ID
        $customer = DB::table('customer')->where('userID', $user->userID)->first();

        if ($customer) {
            // Find Loyalty Card using Matric Number
            $card = LoyaltyCard::where('matricNumber', $customer->matricNumber)->first();
            
            if ($card) {
                // Get vouchers that are NOT used yet
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
            'totalPrice' => $totalPrice,
            'vouchers' => $vouchers
        ]);
    }

    public function start($vehicleID, Request $request)
    {
        $pickupDate = $request->pickup_date;
        $pickupTime = $request->pickup_time;
        $returnDate = $request->return_date;
        $returnTime = $request->return_time;

        $start = Carbon::parse("$pickupDate $pickupTime");
        $end = Carbon::parse("$returnDate $returnTime");
        $durationHours = $end->diffInHours($start);
        $durationDays = ceil($durationHours / 24);
        if ($durationDays < 1) {
            $durationDays = 1;
        }

        $vehicle = Vehicles::findOrFail($vehicleID);
        $totalPrice = $durationDays * $vehicle->pricePerDay;

        session([
            'pickup_date' => $pickupDate,
            'pickup_time' => $pickupTime,
            'return_date' => $returnDate,
            'return_time' => $returnTime,
            'durationDays' => $durationDays,
            'durationHours' => $durationHours,
            'totalPrice' => $totalPrice
        ]);

        return redirect()->route('booking.form', $vehicleID);
    }

    // Store booking after clicking "Book"
    public function store(Request $request, $vehicleID)
    {
        $vehicle = Vehicles::findOrFail($vehicleID);

        // 1. Check if vehicle is still available
        if ($vehicle->status !== 'available') {
            return back()->with('error', 'Sorry, this vehicle is no longer available.');
        }

        $request->validate([
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'pickup_location' => 'required|string|max:255',
            'return_date' => 'required|date',
            'return_time' => 'required',
            'return_location' => 'required|string|max:255',
        ]);

        $start = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $end = Carbon::parse($request->return_date . ' ' . $request->return_time);

        // Calculate duration in days (rounded up)
        $durationDays = ceil($start->diffInHours($end) / 24);
        if ($durationDays < 1) {
            $durationDays = 1;
        }
        $hours = ceil($start->diffInMinutes($end) / 60);
        $totalPrice = $hours * $vehicle->pricePerHour;

        /*$totalPrice = $durationDays * $vehicle->pricePerDay;*/

        $discountAmount = 0;
        
        if ($request->filled('voucher_id')) {
            $user = Auth::user();
            $customer = DB::table('customer')->where('userID', $user->userID)->first();
            $card = LoyaltyCard::where('matricNumber', $customer->matricNumber)->first();
            
            // Check if user actually owns this voucher and it is unused
            $promo = $card->promotions()
                          ->where('promotion.promoID', $request->voucher_id)
                          ->wherePivot('is_used', false)
                          ->first();

            if ($promo) {
                // Calculate Discount
                if ($promo->discountType == 'percentage') {
                    $discountAmount = $totalPrice * ($promo->discountValue / 100);
                } else {
                    $discountAmount = $promo->discountValue;
                }

                // Mark voucher as USED immediately
                $card->promotions()->updateExistingPivot($promo->promoID, ['is_used' => true]);
            }
        }

        // Calculate Final Price (Ensure it doesn't go below 0)
        $finalPrice = max(0, $totalPrice - $discountAmount);

        // Create the booking
        $booking = new Bookings();
        $booking->vehicleID = $vehicleID;
        $booking->customerID = auth()->id(); // ✅ Standardized to auth()->id()
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $end->diffInHours($start);
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addMinutes(10);
        $booking->totalPrice = $finalPrice;
        $booking->save();

        // Save pickup info
        PickUp::create([
            'bookingID' => $booking->bookingID,
            'pickupDate' => $request->pickup_date,
            'pickupTime' => $request->pickup_time,
            'location' => $request->pickup_location,
        ]);

        ReturnCar::create([
            'bookingID' => $booking->bookingID,
            'returnDate' => $request->return_date,
            'returnTime' => $request->return_time,
            'location' => $request->return_location,
        ]);

        // Lock vehicle during payment window
        $vehicle->status = 'unavailable';
        $vehicle->save();

        return redirect()
            ->route('customer.bookingPayment', $booking->bookingID)
            ->with('success', 'Booking created — please complete payment within 10 minutes.');
    }

    // Check promotion
    public function checkPromotion(Request $request)
    {
        $day = $request->day;
        $amount = $request->amount; 
        $promotion = Promotion::all()->first(function ($promo) use ($day) {
            $applicableDays = json_decode($promo->applicableDays, true);
            return is_array($applicableDays) && in_array($day, $applicableDays);
        });

        if ($promotion) {
            $discount = 0;
            if ($promotion->discountType == 'percentage') {
                $discount = $amount * ($promotion->discountValue / 100);
            } else {
                $discount = $promotion->discountValue; // fixed amount
            }
            
            $discount = min($discount, $amount);

            return response()->json([
                'hasPromotion' => true,
                'promoID' => $promotion->promoID,
                'discount' => $discount
            ]);
        }

        return response()->json(['hasPromotion' => false]);
    }

    public function validateVoucher(Request $request)
    {
        $request->validate(['code' => 'required']); 

        $voucher = Voucher::where('voucherCode', $request->code)
            ->where('isUsed', 0)
            ->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Invalid or used voucher']);
        }

        if ($voucher->expiryTime < time()) {
             return response()->json(['valid' => false, 'message' => 'Voucher expired']);
        }

        return response()->json([
            'valid' => true,
            'voucher_id' => $voucher->voucherCode,
            'amount' => $voucher->value,
            'message' => 'Voucher applied! - RM' . number_format($voucher->value, 2)
        ]);
    }

    // Show payment form
    public function showPaymentForm(Request $request)
    {
        $vehicleID = $request->input('vehicleID');
        if (!$vehicleID) return back()->withErrors(['error' => 'Vehicle ID is missing']);

        $vehicle = Vehicles::findOrFail($vehicleID);

        $data = $request->all();

        $subtotal = $request->input('subtotal', 0);
        $promotionDiscount = $request->input('promotionDiscount', 0);
        $total = $request->input('total', $subtotal - $promotionDiscount);
        $deposit = $total * 0.5;

        $eligibleVouchers = [];
        $user = Auth::user();
        $customer = DB::table('customer')->where('userID', $user->userID)->first();
        
        if ($customer) {
            $card = LoyaltyCard::where('matricNumber', $customer->matricNumber)->first();
            if ($card) {
                $eligibleVouchers = $card->promotions()
                                 ->wherePivot('is_used', false)
                                 ->get();
            }
        }

        return view('paymentform', array_merge($data, [
            'vehicle' => $vehicle,
            'finalSubtotal' => $subtotal,
            'finalTotal' => $total,
            'deposit' => $deposit,
            'promotionDiscount' => $promotionDiscount,
            'pickupLocationDisplay' => $request->pickupLocation,
            'returnLocationDisplay' => $request->returnLocation,
            'dateRange' => $request->pickup_date . ' - ' . $request->return_date,
            'durationText' => $request->duration,
            'pickupDate' => $request->pickup_date, 
            'pickupTime' => $request->pickup_time,
            'returnDate' => $request->return_date,
            'returnTime' => $request->return_time,
            'pickupLocation' => $request->pickupLocation,
            'returnLocation' => $request->returnLocation,
            'destination' => $request->destination,
            'remark' => $request->remark,
            'forSomeoneElse' => $request->boolean('for_someone_else'),
            'eligibleVouchers' => $eligibleVouchers
        ]));
    }

    public function confirmBooking(Request $request)
{
        \Log::info('Booking request received', $request->all());
        \Log::info('confirmBooking called');
        \Log::info('Request data:', $request->all());
    try {
        $request->validate([
            'vehicleID' => 'required|exists:vehicles,vehicleID',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required',
            'pickupLocation' => 'required|string',
            'returnLocation' => 'required|string',
            'bank_name' => 'required|string',
            'bank_owner_name' => 'required|string',
            'payAmount' => 'required|in:full,deposit',
            'payment_receipt' => 'required|image',
        ]);

        $vehicle = Vehicles::findOrFail($request->vehicleID);
        
        if ($vehicle->status !== 'available') {
            return response()->json([
                'success' => false, 
                'message' => 'Vehicle no longer available'
            ], 400);
        }

        $pickup = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $return = Carbon::parse($request->return_date . ' ' . $request->return_time);
        $durationHours = $return->diffInHours($pickup);
        
        // Calculate total price with promotion discount if applicable
        $subtotal = $durationHours * $vehicle->pricePerHour;
        $promotionDiscount = 0;
        
        if ($request->promo_id) {
            $promo = Promotion::find($request->promo_id);
            if ($promo && $promo->isActive) {
                $promotionDiscount = ($subtotal * $promo->discountPercentage) / 100;
            }
        }
        
        $totalPrice = $subtotal - $promotionDiscount;
        $depositAmount = $totalPrice * 0.3; // 30% deposit

        // Create booking
        $booking = new Bookings();
        $booking->customerID = auth()->id();
        $booking->vehicleID = $vehicle->vehicleID;
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $durationHours;
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addMinutes(30);

        $booking->totalPrice = $request->input('finalTotal') ?? ($durationHours * $vehicle->pricePerHour); 
        
        $booking->depositAmount = $request->depoBalance ?? 0;
        $booking->promo_id = $request->promo_id;
        $booking->voucher_id = $request->voucher_id;
        
        $booking->destination = $request->destination;
        $booking->remark = $request->remark;
        $booking->bank_name = $request->bank_name;
        $booking->bank_owner_name = $request->bank_owner_name;
        $booking->pay_amount_type = $request->payAmount;
        
        // If booking for someone else, store driver info
        if ($request->filled('for_someone_else') && $request->for_someone_else == 1) {
            $booking->for_someone_else = true;
            $booking->driver_matric_number = $request->matricNumber;
            $booking->driver_license_number = $request->licenseNumber;
            $booking->driver_college = $request->college;
            $booking->driver_faculty = $request->faculty;
            $booking->driver_deposit_balance = $request->depoBalance ?? 0;
        }
        
        // Upload payment receipt
        if ($request->hasFile('payment_receipt')) {
            $file = $request->file('payment_receipt');
            $filename = 'receipt_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'public');
            $booking->payment_receipt_path = $path;
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

        if ($request->hasFile('payment_receipt')) {
            $path = $request->file('payment_receipt')->store('receipts', 'public');
            $booking->payment_receipt_path = $path;
            $booking->save();
        }

        if ($request->voucher_id) {
            Voucher::where('voucherCode', $request->voucher_id)
                ->update(['isUsed' => 1]); 
        }
        // Mark voucher as used
        // if ($request->voucher_id) {
        //     Voucher::where('voucherID', $request->voucher_id)
        //         ->where('customerID', auth()->id())
        //         ->where('isUsed', false)
        //         ->update([
        //             'isUsed' => true,
        //             'usedAt' => now()
        //         ]);
        // }

        // Update loyalty card (optional - uncomment if you want to use)
        /*$loyaltyCard = LoyaltyCard::firstOrNew(['userID' => auth()->id()]);
        $loyaltyCard->stampCount = ($loyaltyCard->stampCount ?? 0) + 1;
        $loyaltyCard->save();*/

        // ✅ Update vehicle status to unavailable
        $vehicle->status = 'unavailable';
        $vehicle->save();

        return response()->json(['success' => true]);
    }catch (\Exception $e) { 
            \Log::error('Booking Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


/* public function validateVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $voucher = Voucher::where('voucherCode', $request->code)
            ->where('userID', auth()->id())
            ->where('isUsed', false)
            ->where('expiryTime', '>', now())
            ->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired voucher']);
        }

        if ($voucher->voucherType === 'loyalty') {
            $loyaltyCard = LoyaltyCard::where('userID', auth()->id())->first();
            if (!$loyaltyCard || $loyaltyCard->stampCount < 3) {
                return response()->json(['valid' => false, 'message' => 'You need 3 stamps to use this voucher']);
            }
        }

        return response()->json([
            'valid' => true,
            'voucher_id' => $voucher->voucherID,
            'message' => 'Voucher applied!'
        ]);
    }*/

    public function bookingHistory()
{
    $bookings = Bookings::where('customer_id', auth()->id())->get();

    $active = Bookings::where('bookingStatus', 'approved')->get();

    $pending = Bookings::whereIn('bookingStatus', ['pending', 'confirmed'])->get();

    $completed = Bookings::where('bookingStatus', 'completed')->get();

    $cancelled = Bookings::where('bookingStatus', 'cancelled')->get();

    return view('bookingHistory', compact(
        'active',
        'pending',
        'completed',
        'cancelled'
    ));
}



    private function checkBlacklist()
    {
        if (auth()->check() && auth()->user()->is_blacklisted) {
            return redirect()->route('welcome')
                ->with('error', 'You are blacklisted and cannot make bookings.')
                ->send();
        }
    }

   

}
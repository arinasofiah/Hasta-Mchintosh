<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use App\Models\Bookings;
use App\Models\PickUp;
use App\Models\ReturnCar;
use App\Models\Promotion;
//use App\Models\Voucher;          
//use App\Models\LoyaltyCard;      
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

    // Start booking and store session data
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

        $durationDays = ceil($start->diffInHours($end) / 24);
        if ($durationDays < 1) {
            $durationDays = 1;
        }
        $hours = ceil($start->diffInMinutes($end) / 60);
        $totalPrice = $hours * $vehicle->pricePerHour;

        $booking = new Bookings();
        $booking->vehicleID = $vehicleID;
        $booking->customerID = auth()->id();
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $end->diffInHours($start);
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addMinutes(10);
        $booking->totalPrice = $booking->bookingDuration * $vehicle->pricePerDay;
        $booking->save();

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

        $vehicle->status = 'unavailable';
        $vehicle->save();

        return redirect()
            ->route('customer.bookingPayment', $booking->bookingID)
            ->with('success', 'Booking created — please complete payment within 10 minutes.');
    }

    // Check promotion
    /*public function checkPromotion(Request $request)
    {
        $day = $request->day;
        $amount = $request->amount;

        $promotion = Promotion::whereNotNull('applicableDays')
            ->get()
            ->first(function ($promo) use ($day) {
                $applicableDays = json_decode($promo->applicableDays, true);
                return is_array($applicableDays) && in_array($day, $applicableDays);
            });

        if ($promotion) {
            $discount = min($promotion->discountValue, $amount);

            return response()->json([
                'hasPromotion' => true,
                'promoID' => $promotion->promoID,
                'discount' => $discount
            ]);
        }

        return response()->json([
            'hasPromotion' => false
        ]);
    }*/

    // Show payment form
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

        // Calculate prices
        $calculatedSubtotal = ($days * $vehicle->pricePerDay) + ($remainingHours * $vehicle->pricePerHour);
        $finalSubtotal = $subtotal ?? $calculatedSubtotal;
        $finalTotal = $total ?? ($finalSubtotal - $promotionDiscount);
        $deposit = $finalTotal * 0.5;

        // Build data for view
        $pickupLocationDisplay = $pickupLocation;
        $returnLocationDisplay = $returnLocation;
        $dateRange = Carbon::parse($pickupDate)->format('d M Y') . ' - ' . Carbon::parse($returnDate)->format('d M Y');
        $durationText = $duration ?? (
            $days > 0 
                ? "{$days} day" . ($days > 1 ? 's' : '') . " {$remainingHours} hour" . ($remainingHours != 1 ? 's' : '')
                : "{$diffHours} hour" . ($diffHours != 1 ? 's' : '')
        );

        // Get eligible vouchers
       /* $userId = auth()->id();
        $eligibleVouchers = Voucher::where('userID', $userId)
            ->where('isUsed', false)
            ->where('expiryTime', '>', now())
            ->where('voucherType', 'general')
            ->get();

        $loyaltyCard = LoyaltyCard::where('userID', $userId)->first();
        if ($loyaltyCard && $loyaltyCard->stampCount >= 3) {
            $loyaltyVoucher = Voucher::where('userID', $userId)
                ->where('voucherType', 'loyalty')
                ->where('isUsed', false)
                ->where('expiryTime', '>', now())
                ->first();
            if ($loyaltyVoucher) {
                $eligibleVouchers->push($loyaltyVoucher);
            }
        }*/

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
            //'eligibleVouchers',
            //'loyaltyCard',
            'pickupDate',
            'pickupTime',
            'returnDate',
            'returnTime',
            'pickupLocation',
            'returnLocation',
            'destination',
            'remark',
            'forSomeoneElse'
        ));
    }

    public function confirmBooking(Request $request)
    {
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
            'payment_receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vehicle = Vehicles::findOrFail($request->vehicleID);
        
        if ($vehicle->status !== 'available') {
            return response()->json(['success' => false, 'message' => 'Vehicle no longer available']);
        }

        $pickup = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
        $return = Carbon::parse($request->return_date . ' ' . $request->return_time);
        $durationHours = $return->diffInHours($pickup);
        $totalPrice = $durationHours * $vehicle->pricePerHour;

        $booking = new Bookings();
        $booking->customerID = auth()->id();
        $booking->vehicleID = $vehicle->vehicleID;
        $booking->startDate = $request->pickup_date;
        $booking->endDate = $request->return_date;
        $booking->bookingDuration = $durationHours;
        $booking->bookingStatus = 'pending';
        $booking->reservation_expires_at = now()->addMinutes(30); // Extended to 30 mins
        $booking->totalPrice = $totalPrice;
        $booking->depositAmount = $request->depoBalance ?? 0;
        $booking->promo_id = $request->promo_id;
        //$booking->voucher_id = $request->voucher_id;
        $booking->destination = $request->destination;
        $booking->remark = $request->remark;
        $booking->bank_name = $request->bank_name;
        $booking->bank_owner_name = $request->bank_owner_name;
        $booking->pay_amount_type = $request->payAmount;
        
        if ($request->filled('for_someone_else')) {
            $booking->driver_matric_number = $request->matricNumber;
            $booking->driver_license_number = $request->licenseNumber;
            $booking->driver_college = $request->college;
            $booking->driver_faculty = $request->faculty;
        }
        
        $booking->save();

        PickUp::create([
            'bookingID' => $booking->bookingID,
            'pickupDate' => $request->pickup_date,
            'pickupTime' => $request->pickup_time,
            'location' => $request->pickupLocation,
        ]);

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
            Voucher::where('voucherID', $request->voucher_id)
                ->update(['isUsed' => true]);
        }

        /*$loyaltyCard = LoyaltyCard::firstOrNew(['userID' => auth()->id()]);
        $loyaltyCard->stampCount = ($loyaltyCard->stampCount ?? 0) + 1;
        $loyaltyCard->save();*/

        $vehicle->status = 'unavailable';
        $vehicle->save();

        return response()->json(['success' => true]);
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
        $bookings = Bookings::with('vehicle')
            ->where('customerID', auth()->id()) 
            ->orderBy('created_at', 'desc')  
            ->get();

        return view('booking-history', [
            'completed' => $bookings->where('status', 'Completed'),
            'upcoming'  => $bookings->where('status', 'Upcoming'),
            'cancelled' => $bookings->where('status', 'Cancelled'),
        ]);
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
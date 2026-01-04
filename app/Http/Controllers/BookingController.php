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

    public function checkPromotion(Request $request)
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
            $discount = ($amount * $promotion->discountValue) / 100;

            return response()->json([
                'hasPromotion' => true,
                'promoID' => $promotion->promoID,
                'discount' => $discount
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

        $calculatedSubtotal = ($days * $vehicle->pricePerDay) + ($remainingHours * $vehicle->pricePerHour);
        $finalSubtotal = $subtotal ?? $calculatedSubtotal;
        $finalTotal = $total ?? ($finalSubtotal - $promotionDiscount);
        $deposit = $finalTotal * 0.5;

        $pickupLocationDisplay = $pickupLocation;
        $returnLocationDisplay = $returnLocation;
        $dateRange = Carbon::parse($pickupDate)->format('d M Y') . ' - ' . Carbon::parse($returnDate)->format('d M Y');
        $durationText = $duration ?? (
            $days > 0 
                ? "{$days} day" . ($days > 1 ? 's' : '') . " {$remainingHours} hour" . ($remainingHours != 1 ? 's' : '')
                : "{$diffHours} hour" . ($diffHours != 1 ? 's' : '')
        );

        $promoDetails = null;
        if ($request->input('promo_id')) {
            $promoDetails = Promotion::find($request->input('promo_id'));
        }

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
            'promoDetails'
        ));
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
                'pickupLocation' => 'required|string',
                'returnLocation' => 'required|string',
                'bank_name' => 'required|string',
                'bank_owner_name' => 'required|string',
                'bankNum' => 'required|string', // ✅ Add validation for bank account number
                'payAmount' => 'required|in:full,deposit',
                'payment_receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
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
            
            $days = floor($durationHours / 24);
            $remainingHours = $durationHours % 24;
            $subtotal = ($days * $vehicle->pricePerDay) + ($remainingHours * $vehicle->pricePerHour);
            
            $promotionDiscount = 0;
            if ($request->promo_id) {
                $promo = Promotion::find($request->promo_id);
                if ($promo) {
                    $promotionDiscount = ($subtotal * $promo->discountValue) / 100;
                }
            }
            
            $totalPrice = $subtotal - $promotionDiscount;
            $depositAmount = $totalPrice * 0.5;

            // ✅ Create booking with BOTH userID and customerID
            $booking = new Bookings();
            $booking->userID = auth()->id(); // ✅ Set userID
            $booking->customerID = auth()->id(); // ✅ Also set customerID if it exists
            $booking->vehicleID = $vehicle->vehicleID;
            $booking->startDate = $request->pickup_date;
            $booking->endDate = $request->return_date;
            $booking->bookingDuration = $durationHours;
            $booking->bookingStatus = 'pending';
            $booking->reservation_expires_at = now()->addMinutes(30);
            $booking->totalPrice = $totalPrice;
            $booking->depositAmount = $depositAmount;
            $booking->promo_id = $request->promo_id;
            $booking->voucher_id = $request->voucher_id;
            $booking->destination = $request->destination;
            $booking->remark = $request->remark;
            $booking->bank_name = $request->bank_name;
            $booking->bank_owner_name = $request->bank_owner_name;
            $booking->bankNum = $request->bankNum; // ✅ Add bank account number
            $booking->pay_amount_type = $request->payAmount;
            
            if ($request->filled('for_someone_else') && $request->for_someone_else == 1) {
                $booking->for_someone_else = true;
                $booking->driver_matric_number = $request->matricNumber;
                $booking->driver_license_number = $request->licenseNumber;
                $booking->driver_college = $request->college;
                $booking->driver_faculty = $request->faculty;
                $booking->driver_deposit_balance = $request->depoBalance ?? 0;
            }
            
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

            // Update vehicle status
            $vehicle->status = 'unavailable';
            $vehicle->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking submitted successfully!',
                'booking_id' => $booking->bookingID
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Booking confirmation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Booking failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validateVoucher(Request $request)
    {
        return response()->json([
            'valid' => false,
            'message' => 'Voucher system not yet implemented'
        ]);
    }

    public function bookingHistory()
    {
        $bookings = Bookings::where('customerID', auth()->id())
            ->orWhere('userID', auth()->id()) // ✅ Also check userID
            ->with(['vehicle', 'pickup', 'return'])
            ->orderBy('created_at', 'desc')
            ->get();

        $active = $bookings->whereIn('bookingStatus', ['approved', 'confirmed']);
        $pending = $bookings->where('bookingStatus', 'pending');
        $completed = $bookings->where('bookingStatus', 'completed');
        $cancelled = $bookings->where('bookingStatus', 'cancelled');

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
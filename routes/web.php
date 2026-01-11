<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PickUpController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;

// Public routes
Route::view('/signup', 'signup');

Route::post('/ocr/ic', [OcrController::class, 'ic']);
Route::post('/ocr/license', [OcrController::class, 'license']);
Route::post('/ocr/student', [OcrController::class, 'student']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', [VehicleController::class, 'index'])->name('welcome');

// Dashboard (auth required)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Vehicle Index page (shows all available vehicles in grid)
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');

// Pickup & Return (public or auth? assuming auth later)
// Pickup
Route::get('/pickup/{bookingID}', [PickUpController::class, 'show'])->name('pickup.show');
Route::post('/pickup/store', [PickUpController::class, 'store'])->name('pickup.store');

// Return
Route::post('/return/store', [PickUpController::class, 'storeReturn'])->name('return.store');

// Vehicle routes
Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.select');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/select/{id}', [VehicleController::class, 'select'])->name('selectVehicle');
Route::get('/vehicles/available', [VehicleController::class, 'getAvailableVehicles'])->name('vehicles.available');

Route::post('/store-pending-booking', function (Request $request) {
    session([
        'pending_booking' => [
            'vehicleID' => $request->vehicleID,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'return_date' => $request->return_date,
            'return_time' => $request->return_time,
            'timestamp' => now()
        ]
    ]);
    return response()->json(['success' => true]);
})->name('store.pending.booking');


// Admin vehicle management
Route::middleware(['auth'])->prefix('admin')->group(function () {
    //Route::get('/dashboard', [VehicleController::class, 'adminDashboard'])->name('admin.dashboard');
   // Route::get('/fleet', [VehicleController::class, 'adminVehicles'])->name('admin.fleet');
   // Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('admin.vehicles.create');
    //Route::post('/vehicles/store', [VehicleController::class, 'store'])->name('admin.vehicles.store');
    //Route::put('/vehicles/update/{id}', [VehicleController::class, 'update'])->name('admin.vehicles.update');
    //Route::delete('/vehicles/delete/{id}', [VehicleController::class, 'destroy'])->name('admin.vehicles.destroy');
    Route::get('/fleet', [AdminController::class, 'fleet'])->name('admin.fleet');
    Route::get('/vehicles/create', [AdminController::class, 'createVehicle'])->name('admin.vehicles.create');
    Route::post('/vehicles', [AdminController::class, 'storeVehicle'])->name('admin.vehicles.store');
    Route::put('/vehicles/{vehicleID}', [AdminController::class, 'updateVehicle'])->name('admin.vehicles.update');
    Route::delete('/vehicles/{vehicleID}', [AdminController::class, 'destroyVehicle'])->name('admin.vehicles.destroy');


    Route::get('/staff/create', [AdminController::class, 'createStaff'])->name('admin.staff.create');

    Route::get('/reporting', [ReportController::class, 'reportingIndex'])->name('admin.reporting');
    Route::get('/admin/reporting/export', [ReportController::class, 'exportReport'])->name('admin.reporting.export');

    Route::get('/promotions', [PromotionController::class, 'index'])->name('admin.promotions');
    
    // Promo
    Route::post('/promotions/store', [PromotionController::class, 'store'])->name('admin.promotions.store');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('admin.promotions.destroy');

    // Voucher
    Route::post('/vouchers/store', [PromotionController::class, 'storeVoucher'])->name('admin.vouchers.store');
    Route::delete('/vouchers/{id}', [PromotionController::class, 'destroyVoucher'])->name('admin.vouchers.destroy');

    // Commission
    Route::post('/commission/reset/{id}', [PromotionController::class, 'resetCommission'])->name('admin.commission.reset');
    Route::put('/commission/update/{id}', [PromotionController::class, 'updateCommission'])->name('admin.commission.update');

    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    // Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');    
    Route::post('/payment/{id}/approve', [AdminController::class, 'approvePayment'])->name('admin.payment.approve');
    Route::post('/bookings/{id}/approve', [AdminController::class, 'approveBooking'])->name('admin.bookings.approve');
    Route::post('/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])->name('admin.bookings.reject');
    Route::post('/bookings/{id}/complete', [AdminController::class, 'completeReturn'])->name('admin.bookings.complete');
});

//  Booking routes (customer)
Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/{vehicleID}', [BookingController::class, 'showForm'])->name('form');
    Route::post('/{vehicleID}', [BookingController::class, 'store'])->name('store');
    Route::post('/start/{vehicleID}', [BookingController::class, 'start'])->name('start');
    Route::post('/booking/confirm', [BookingController::class, 'confirmBooking'])->name('confirm');
    Route::get('/history', [BookingController::class, 'bookingHistory'])->name('history');
    Route::post('/payment-form', [BookingController::class, 'showPaymentForm'])->name('payment.form');
    Route::get('/booking-history', [BookingController::class, 'history'])->name('booking.history');
});

Route::post('/register-customer', [CustomerController::class, 'registerCustomer'])->name('customer.register');

// Payment-related routes (used during booking flow)
Route::middleware(['auth'])->group(function () {
    Route::post('/payment-form', [BookingController::class, 'showPaymentForm'])->name('payment.form');
    // In routes/web.php
Route::post('/booking/check-promotion', [BookingController::class, 'checkPromotion'])->name('booking.checkPromotion');
    Route::post('/validate-voucher', [BookingController::class, 'validateVoucher'])->name('validate.voucher'); 
});

// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   Route::get('/documents', [CustomerController::class, 'showDocuments'])->name('customer.documents');
    Route::post('/documents/upload', [CustomerController::class, 'uploadDocuments'])->name('customer.documents.upload');
    Route::get('/documents/delete/{type}', [CustomerController::class, 'deleteDocument'])->name('customer.documents.delete');
    Route::get('/my-loyalty', [LoyaltyController::class, 'index'])->name('customer.loyaltycard');
});

// Staff routes
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
    Route::get('/booking/confirmation', [StaffController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/payment/verify', [StaffController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/vehicle/pickup', [StaffController::class, 'viewPickup'])->name('vehicle.pickup');
    Route::get('/vehicle/return', [StaffController::class, 'verifyReturn'])->name('vehicle.return');
    Route::get('/booking/history', [StaffController::class, 'history'])->name('booking.history'); // staff history
    Route::get('/vehicle/status', [StaffController::class, 'updateStatus'])->name('vehicle.status');
    Route::get('/commission', [StaffController::class, 'commission'])->name('commission');
    Route::put('/commission/update', [StaffController::class, 'updateBank'])->name('commission.update');
    Route::post('/commission/redeem', [StaffController::class, 'redeem'])->name('commission.redeem');
    Route::post('/payment/approve/{id}', [StaffController::class, 'approvePayment'])->name('payment.approve');
});

// Payment routes
// Payment routes for remaining balance
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/remaining/{bookingID}', [PaymentController::class, 'showRemainingPayment'])
        ->name('payment.remaining');
    Route::post('/payment/remaining/{bookingID}', [PaymentController::class, 'processRemainingPayment']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/payment/remaining/{bookingID}', [PaymentController::class, 'showRemainingPayment'])
        ->name('payment.remaining');
    
    Route::post('/payment/remaining/{bookingID}', [PaymentController::class, 'processRemainingPayment']);
    
    Route::get('/payment/history/{bookingID}', [PaymentController::class, 'paymentHistory'])
        ->name('payment.history');
});

Route::get('/select-vehicle', function () {
    return view('selectVehicle');
})->name('vehicle.select');

Route::get('/booking-form', function () {
    return view('bookingform');
})->name('booking.form');

Route::get('/payment-form', function () {
    return view('paymentform');
})->name('payment.form');

Route::post('/customer/booking/{id}/cancel', [CustomerController::class, 'cancelBooking'])
    ->name('customer.booking.cancel')
    ->middleware('auth');
   
Route::get('/admin/get-vehicle-availability', [AdminController::class, 'getVehicleAvailability'])
    ->name('admin.vehicle-availability');


Route::get('/pickup/form/{bookingID}', [PickupController::class, 'form'])->name('pickup.form');
Route::get('/return/form/{bookingID}', [ReturnController::class, 'showForm'])->name('return.form');

Route::get('/staff/booking/{id}', [StaffController::class, 'showBooking'])->name('staff.bookings.show');
Route::post('/staff/booking/{id}/approve', [StaffController::class, 'approveBooking'])->name('staff.bookings.approve');
Route::post('/staff/booking/{id}/reject', [StaffController::class, 'rejectBooking'])->name('staff.bookings.reject');

require __DIR__.'/auth.php';
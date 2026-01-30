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

// Pickup & Return (auth)
Route::middleware(['auth'])->group(function () {
Route::get('/pickup/{bookingID}', [PickUpController::class, 'show'])->name('pickup.show');
Route::post('/pickup/store', [PickUpController::class, 'store'])->name('pickup.store');
Route::get('/pickup/form/{bookingID}', [PickUpController::class, 'show'])->name('pickup.form');
});
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
    Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('admin.bookings.show');    
    Route::post('/payment/{id}/approve', [AdminController::class, 'approvePayment'])->name('admin.payment.approve');
    Route::post('/bookings/{id}/approve', [AdminController::class, 'approveBooking'])->name('admin.bookings.approve');
    Route::post('/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])->name('admin.bookings.reject');
    Route::post('/bookings/{id}/complete', [AdminController::class, 'completeReturn'])->name('admin.bookings.complete');

    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::put('/customers/{id}', [AdminController::class, 'updateCustomer'])->name('admin.customers.update');

    Route::get('/staff', [AdminController::class, 'staff'])->name('admin.staff');
    Route::post('/staff', [AdminController::class, 'storeStaff'])->name('admin.staff.store'); 
    Route::put('/staff/{id}', [AdminController::class, 'updateStaff'])->name('admin.staff.update'); 
    Route::delete('/staff/{id}', [AdminController::class, 'destroyStaff'])->name('admin.staff.destroy'); 
    
    Route::post('/staff/{id}/resend', [AdminController::class, 'resendStaffInvitation'])->name('admin.staff.resendInvitation');
    Route::post('/staff/{id}/cancel', [AdminController::class, 'cancelStaffInvitation'])->name('admin.staff.cancelInvitation');

    Route::get('/pickup/{bookingID}', [BookingController::class, 'showPickupForm'])->name('pickup.form');
    Route::post('/pickup/{bookingID}', [BookingController::class, 'processPickup'])->name('pickup.process');
    Route::get('/return/{bookingID}', [BookingController::class, 'showReturnForm'])->name('return.form');
    Route::post('/return/{bookingID}', [BookingController::class, 'processReturn'])->name('return.process');
});

//  Booking routes (customer)
Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/{vehicleID}', [BookingController::class, 'showForm'])->name('form');
    Route::post('/{vehicleID}', [BookingController::class, 'store'])->name('store');
    Route::post('/start/{vehicleID}', [BookingController::class, 'start'])->name('start');
    Route::post('/booking/confirm', [BookingController::class, 'confirmBooking'])->name('confirm');
    Route::get('/history', [BookingController::class, 'bookingHistory'])->name('history');
    Route::post('/payment-form', [BookingController::class, 'showPaymentForm'])->name('payment.form');

});

Route::post('/register-customer', [CustomerController::class, 'registerCustomer'])->name('customer.register');

Route::middleware(['auth'])->group(function () {
    Route::post('/payment-form', [BookingController::class, 'showPaymentForm'])->name('payment.form');
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

// Add this to your routes/web.php
Route::get('/test-mailtrap', function () {
    try {
        // Test with a dummy user
        $user = new \App\Models\User();
        $user->email = 'test@example.com';
        $user->userType = 'staff';
        $user->invitation_expires_at = now()->addDays(7);
        
        $registrationUrl = 'https://hasta.test/staff/register/test-token';
        
        \Mail::to('test@example.com')->send(new \App\Mail\StaffInvitationMail(
            $user, 
            $registrationUrl,
            'Test Admin'
        ));
        
        return '✅ Email sent successfully! Check Mailtrap inbox.';
        
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

// Commission routes
Route::middleware(['auth'])->group(function () {
    Route::post('/staff/commission/add', [AdminController::class, 'addCommission'])->name('staff.commission.add');
    Route::get('/staff/commission/{id}/edit', [AdminController::class, 'editCommission'])->name('staff.commission.edit');
    Route::put('/staff/commission/{id}', [AdminController::class, 'updateCommission'])->name('staff.commission.update');
    Route::delete('/staff/commission/{id}', [AdminController::class, 'destroyCommission'])->name('staff.commission.destroy');
    Route::post('/staff/bank/update', [AdminController::class, 'updateBankDetails'])->name('staff.bank.update');
    Route::post('/staff/redemption/request', [AdminController::class, 'requestRedemption'])->name('staff.redemption.request');
});

Route::get('/pickup/form/{bookingID}', [PickupController::class, 'form'])->name('pickup.form');
Route::get('/return/form/{bookingID}', [ReturnController::class, 'showForm'])->name('return.form');

require __DIR__.'/auth.php';
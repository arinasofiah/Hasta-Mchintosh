<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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

// Pickup & Return (public or auth? assuming auth later)
Route::post('/pickup', [PickUpController::class, 'store'])->name('pickup.store');
Route::get('/pickup', [PickUpController::class, 'show']);

Route::get('/return', [ReturnController::class, 'show']);
Route::post('/return', [ReturnController::class, 'store'])->name('return.store');

// Vehicle routes
Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.select');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/select/{id}', [VehicleController::class, 'select'])->name('selectVehicle');
Route::get('/vehicles/available', [VehicleController::class, 'getAvailableVehicles'])->name('vehicles.available');

// Admin vehicle management
Route::middleware(['auth'])->prefix('admin')->group(function () {
    //Route::get('/dashboard', [VehicleController::class, 'adminDashboard'])->name('admin.dashboard');
   // Route::get('/fleet', [VehicleController::class, 'adminVehicles'])->name('admin.fleet');
   // Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('admin.vehicles.create');
    //Route::post('/vehicles/store', [VehicleController::class, 'store'])->name('admin.vehicles.store');
    //Route::put('/vehicles/update/{id}', [VehicleController::class, 'update'])->name('admin.vehicles.update');
    //Route::delete('/vehicles/delete/{id}', [VehicleController::class, 'destroy'])->name('admin.vehicles.destroy');

    Route::get('/staff/create', [AdminController::class, 'createStaff'])->name('admin.staff.create');

    Route::get('/reporting', [ReportController::class, 'reportingIndex'])->name('admin.reporting');
    Route::get('/admin/reporting/export', [ReportController::class, 'exportReport'])->name('admin.reporting.export');

    Route::get('/promotions', [PromotionController::class, 'index'])->name('admin.promotions');
    Route::post('/promotions/store', [PromotionController::class, 'store'])->name('admin.promotions.store');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('admin.promotions.destroy');
});

//  Booking routes (customer)
Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/{vehicleID}', [BookingController::class, 'showForm'])->name('form');
    Route::post('/{vehicleID}', [BookingController::class, 'store'])->name('store');
    Route::post('/start/{vehicleID}', [BookingController::class, 'start'])->name('start');
    Route::post('/confirm', [BookingController::class, 'confirmBooking'])->name('confirm');
    Route::get('/history', [BookingController::class, 'bookingHistory'])->name('history'); // ✅
    Route::post('/payment-form', [BookingController::class, 'showPaymentForm'])->name('payment.form');
});

Route::post('/register-customer', [CustomerController::class, 'registerCustomer'])->name('customer.register');

// Payment-related routes (used during booking flow)
Route::middleware(['auth'])->group(function () {
    Route::post('/payment-form', [BookingController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/check-promotion', [BookingController::class, 'checkPromotion']);
    Route::post('/validate-voucher', [BookingController::class, 'validateVoucher'])->name('validate.voucher'); 
});

// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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


require __DIR__.'/auth.php';
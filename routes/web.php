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

Route::view('/signup', 'signup');

Route::post('/ocr/ic', [OcrController::class, 'ic']);
Route::post('/ocr/license', [OcrController::class, 'license']);
Route::post('/ocr/student', [OcrController::class, 'student']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/pickup', [PickUpController::class,'store'])->name('pickup.store');
Route::get('/pickup',[PickUpController::class,'show']);

Route::get('/return',[ReturnController::class,'show']);
Route::post('/return', [ReturnController::class,'store'])->name('return.store');

Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.select'); 
Route::get('/', [VehicleController::class, 'index'])->name('welcome');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/admin/dashboard', [VehicleController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin/fleet', [VehicleController::class, 'adminVehicles'])->name('admin.fleet');
Route::post('/admin/vehicles/store', [VehicleController::class, 'store'])->name('admin.vehicles.store');
Route::get('/vehicles/select/{id}', [VehicleController::class, 'select'])->name('selectVehicle');
Route::get('/vehicles/available', [VehicleController::class, 'getAvailableVehicles'])->name('vehicles.available');


Route::put('/admin/vehicles/update/{id}', [VehicleController::class, 'update'])->name('admin.vehicles.update');
Route::delete('/admin/vehicles/delete/{id}', [VehicleController::class, 'destroy'])->name('admin.vehicles.destroy');
Route::get('/admin/vehicles/create', [VehicleController::class, 'create'])->name('admin.vehicles.create');

// Add this anywhere in your routes file (test it outside groups)
Route::get('/admin/staff/create', [AdminController::class, 'createStaff'])
    ->name('admin.staff.create')
    ->middleware('auth');

Route::get('/admin/staff/create', [AdminController::class, 'createStaff'])
    ->name('admin.staff.create')
    ->middleware('auth');

Route::prefix('admin')->group(function () {
    Route::get('/reporting', [ReportController::class, 'reportingIndex'])->name('admin.reporting');
});

Route::get('/booking/{vehicleID}', [BookingController::class, 'showForm'])
    ->name('booking.form')
    ->middleware('auth');

Route::post('/booking/{vehicleID}', [BookingController::class, 'store'])
    ->name('booking.store');

Route::post('/booking/start/{vehicleID}', [BookingController::class, 'start'])
    ->name('booking.start');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
    Route::get('/booking/confirmation', [StaffController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/payment/verify', [StaffController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/vehicle/pickup', [StaffController::class, 'viewPickup'])->name('vehicle.pickup');
    Route::get('/vehicle/return', [StaffController::class, 'verifyReturn'])->name('vehicle.return');
    Route::get('/booking/history', [StaffController::class, 'history'])->name('booking.history');
    Route::get('/vehicle/status', [StaffController::class, 'updateStatus'])->name('vehicle.status');
});

require __DIR__.'/auth.php';

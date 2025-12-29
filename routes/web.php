<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VehicleController;

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

Route::get('/pickup', function () {
    return view('pickupform');
});

/*nisa add this*/
Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.select'); 
Route::get('/', [VehicleController::class, 'index'])->name('welcome');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/admin/dashboard', [VehicleController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin/fleet', [VehicleController::class, 'adminVehicles'])->name('admin.fleet');
Route::post('/admin/vehicles/store', [VehicleController::class, 'store'])->name('admin.vehicles.store');
Route::get('/select-vehicle/{id}', [VehicleController::class, 'select'])
    ->name('selectVehicle');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/booking/{vehicleID}', function ($vehicleID) {
        return view('bookingform', compact('vehicleID'));
    })->name('booking.form');
});

require __DIR__.'/auth.php';

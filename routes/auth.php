<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
   Route::get('/admin/customers', [AdminController::class, 'customers'])
        ->name('admin.customers');
    
    // Add this update route
    Route::put('/admin/customers/{id}', [AdminController::class, 'updateCustomer'])
        ->name('admin.customers.update');
    Route::get('/admin/staff', [AdminController::class, 'staff'])->name('admin.staff');
    
    
    // Staff routes
    Route::get('/staff/dashboard', [StaffController::class, 'index'])
        ->name('staff.dashboard');
    
    // Customer routes
    Route::prefix('customer')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'index'])
            ->name('customer.dashboard');
        
        Route::get('/profile', [CustomerController::class, 'profile'])
            ->name('customer.profile');
        
        Route::get('/profile/edit', [CustomerController::class, 'edit'])->name('customer.profile.edit');
        Route::put('/profile/update', [CustomerController::class, 'update'])->name('customer.profile.update');
            
        Route::get('/bookings', [CustomerController::class, 'bookings'])
            ->name('customer.bookings');

        Route::get('/customers', [CustomerController::class, 'adminIndex'])->name('customers.index');
   
        Route::put('/customers/{id}', [CustomerController::class, 'adminUpdate'])->name('customers.update');
    });
    
    // Common routes for all users
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});



Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

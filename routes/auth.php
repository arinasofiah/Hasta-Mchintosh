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

//guest
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

//auth
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    

   
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
        Route::put('/customers/{id}', [AdminController::class, 'updateCustomer'])->name('admin.customers.update');
         Route::get('/profile', [AdminController::class, 'profile'])->name('profile');

       // Staff management routes
    Route::get('/staff', [AdminController::class, 'staff'])->name('admin.staff');
    Route::get('/staff/create', [AdminController::class, 'createStaff'])->name('admin.staff.create');
    Route::post('/staff', [AdminController::class, 'storeStaff'])->name('admin.staff.store');
    Route::put('/staff/{id}', [AdminController::class, 'updateStaff'])->name('admin.staff.update');
    Route::delete('/staff/{id}', [AdminController::class, 'destroyStaff'])->name('admin.staff.destroy');
    Route::post('/staff/{id}/resend-invitation', [AdminController::class, 'resendStaffInvitation'])->name('admin.staff.resendInvitation');
    Route::post('/staff/{id}/cancel-invitation', [AdminController::class, 'cancelStaffInvitation'])->name('admin.staff.cancelInvitation');
});

// For admin/staff routes
Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    // Add other admin routes as needed
});

// Public routes for staff registration (no auth required)
Route::middleware('guest')->group(function () {
    Route::get('/staff/register/{token}', [AdminController::class, 'showStaffRegistrationForm'])->name('staff.register.form');
    Route::post('/staff/register/{token}', [AdminController::class, 'completeStaffRegistration'])->name('staff.completeRegistration');
});
    
    // Staff routes
    Route::prefix('staff')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'index'])->name('admin.dashboard');
    });

    
    // Customer routes
    Route::prefix('customer')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');
        
        Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
        Route::get('/profile/edit', [CustomerController::class, 'edit'])->name('customer.profile.edit');
        Route::put('/profile/update', [CustomerController::class, 'update'])->name('customer.profile.update');
       Route::get('/customer/bookings', [CustomerController::class, 'bookings'])
        ->name('bookingHistory');
    
    // Booking Form
    Route::get('/customer/book/{vehicleId}', [CustomerController::class, 'bookingForm'])
        ->name('customer.booking.form');
    
    // Booking Details API (for modals)
    Route::get('/customer/booking/{id}', [CustomerController::class, 'getBookingDetails'])
        ->name('customer.booking.details');
    });
    
});

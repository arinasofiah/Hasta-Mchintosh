<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Customer;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        if ($request->has('redirect')) {
            session(['url.intended' => $request->get('redirect')]);
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First attempt to authenticate
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        
        // CHECK BLACKLIST IN CUSTOMER TABLE - using isBlacklisted (not is_blacklisted)
        // Only check for customers (not admin/staff)
        if ($user->userType === 'customer') {
            // Load the customer relationship with blacklist check
            $customer = Customer::where('userID', $user->userID)->first();
            
            if ($customer && $customer->isBlacklisted) { // ← Changed to isBlacklisted
                // User is blacklisted - log them out
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been blacklisted. Please contact support.']);
            }
        }
        
        // Check if there's a pending booking in session
        if (session()->has('pending_booking')) {
            $booking = session('pending_booking');
            session()->forget('pending_booking');
            
            return redirect()->route('booking.form', [
                'vehicleID' => $booking['vehicleID'],
                'pickup_date' => $booking['pickup_date'],
                'pickup_time' => $booking['pickup_time'],
                'return_date' => $booking['return_date'],
                'return_time' => $booking['return_time']
            ]);
        }
        
        // Check for URL redirect parameter
        if ($request->has('redirect_to') || session()->has('url.intended')) {
            $redirectUrl = $request->get('redirect_to') ?? session('url.intended');
            session()->forget('url.intended');
            
            if (filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
                return redirect($redirectUrl);
            }
        }
        
        // Standard role-based redirect
        if ($user->userType === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->userType === 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($user->userType === 'customer') {
            return redirect()->intended('/customer/dashboard');
        }
        
        return redirect()->intended('/customer/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (session()->has('pending_booking')) {
            session()->forget('pending_booking');
        }
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
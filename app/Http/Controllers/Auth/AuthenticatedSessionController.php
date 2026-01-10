<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Admin;
use App\Models\Staff;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Store the redirect URL if provided
        if ($request->has('redirect')) {
            session(['url.intended' => $request->get('redirect')]);
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        
        // Check if user is blacklisted
        if ($user->is_blacklisted) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['error' => 'Your account has been blacklisted. Please contact support.']);
        }
        
        // FIRST: Check if there's a pending booking in session (for guests who clicked Book Now)
        if (session()->has('pending_booking')) {
            $booking = session('pending_booking');
            
            // Clear the session
            session()->forget('pending_booking');
            
            // Redirect to booking form with parameters
            return redirect()->route('booking.form', [
                'vehicleID' => $booking['vehicleID'],
                'pickup_date' => $booking['pickup_date'],
                'pickup_time' => $booking['pickup_time'],
                'return_date' => $booking['return_date'],
                'return_time' => $booking['return_time']
            ]);
        }
        
        // SECOND: Check for URL redirect parameter (fallback method)
        if ($request->has('redirect_to') || session()->has('url.intended')) {
            $redirectUrl = $request->get('redirect_to') ?? session('url.intended');
            session()->forget('url.intended');
            
            // Validate that it's a safe URL (optional but recommended)
            if (filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
                return redirect($redirectUrl);
            }
        }
        
        // THIRD: Standard role-based redirect
        if ($user->userType === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->userType === 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($user->userType === 'customer') {
            // Check if there's an intended URL from Laravel's auth system
            return redirect()->intended('/customer/dashboard');
        }
        
        // Fallback
        return redirect()->intended('/customer/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clear any pending booking session before logout
        if (session()->has('pending_booking')) {
            session()->forget('pending_booking');
        }
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
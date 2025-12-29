<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Add this
use Illuminate\View\View;
use App\Models\Admin;
use App\Models\Staff;
// Remove: use App\Models\Customer; // Remove this

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
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
    
    // Simple redirect based on userType
    if ($user->userType === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->userType === 'staff') {
        return redirect()->route('staff.dashboard');
    } elseif ($user->userType === 'customer') {
        return redirect()->route('customer.dashboard');
    }
    
    // Fallback
    return redirect()->intended('/customer/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
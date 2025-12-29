<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Customer;

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

    $user = auth()->user();

    if (Admin::where('userID', $user->userID)->exists()) {
        return redirect()->intended('/admin/dashboard');
    }

    if (Staff::where('userID', $user->userID)->exists()) {
        return redirect()->intended('/staff/dashboard');
    }

    if (Customer::where('userID', $user->userID)->exists()) {
        return redirect()->intended('/customer/dashboard');
    }

    Auth::logout();
    return redirect('/login')->withErrors([
        'email' => 'Unauthorized account.'
    ]);
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

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;      // Crucial: Import User
use App\Models\Customer;  // Crucial: Import Customer
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Crucial: For transactions
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'icNumber' => ['required', 'string', 'unique:users,icNumber'],
            'matricNumber' => ['required', 'string', 'unique:customers,matricNumber'],
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create User (Supertype)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'icNumber' => $request->icNumber,
                'userType' => 'customer',
            ]);

            // 2. Create Customer (Subtype)
            Customer::create([
                'matricNumber' => $request->matricNumber,
                'userID' => $user->userID, // Links to User table
                'depoBalance' => 0.00,
            ]);
        });

        return redirect()->route('auth.login')->with('status', 'Registration successful! Please login.');
    }
}
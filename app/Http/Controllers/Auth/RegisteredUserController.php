<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Telephone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'icNumber' => ['required', 'string', 'unique:users,icNumber'],
        'matricNumber' => ['required', 'string', 'unique:customer,matricNumber'],
        'phone' => ['required', 'string', 'unique:telephone,phoneNumber'],
        'licenseNumber' => ['nullable', 'string', 'max:50'],
    ]);

    DB::transaction(function () use ($request) {
        // Create user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'icNumber' => $request->icNumber,
            'userType' => 'customer',
        ]);

        // Create phone record - using full namespace
        Telephone::create([
            'phoneNumber' => $request->phone,
            'userID' => $user->userID,
        ]);

        // Create customer record
        DB::table('customer')->insert([
            'userID' => $user->userID,
            'matricNumber' => $request->matricNumber,
            'licenseNumber' => $request->licenseNumber,
            'depoBalance' => 0.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        event(new Registered($user));
    });

    return redirect()->route('login')->with('status', 'Registration successful!');
}

}

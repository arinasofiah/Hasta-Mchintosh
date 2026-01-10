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
        // Validate all fields
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'icNumber' => ['required', 'string', 'unique:users,icNumber'],
            'phone' => ['required', 'string', 'unique:telephone,phoneNumber'],
            
            // Optional fields
            'matricNumber' => ['nullable', 'string', 'max:50', 'unique:customer,matricNumber'],
            'college' => ['nullable', 'string', 'max:100'],
            'faculty' => ['nullable', 'string', 'max:100'],
            'licenseNumber' => ['nullable', 'string', 'max:50'],
            
            // Emergency contact fields
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:50'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create user first
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'icNumber' => $request->icNumber,
                    'userType' => 'customer',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create phone record
                Telephone::create([
                    'phoneNumber' => $request->phone,
                    'userID' => $user->userID,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create customer record with all fields
                DB::table('customer')->insert([
                    'userID' => $user->userID,
                    'matricNumber' => $request->matricNumber,
                    'licenseNumber' => $request->licenseNumber,
                    'college' => $request->college,
                    'faculty' => $request->faculty,
                    'emergency_contact_name' => $request->emergency_contact_name,
                    'emergency_contact_phone' => $request->emergency_contact_phone,
                    'emergency_contact_relationship' => $request->emergency_contact_relationship,
                    'depoBalance' => 0.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                event(new Registered($user));
            });

            return redirect()->route('login')
                ->with('success', 'Registration successful! Please login to continue.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Registration error: ' . $e->getMessage());
            
            // Redirect back with error message
            return redirect()->back()
                ->withInput()
                ->withErrors(['server_error' => 'Registration failed. Please try again.']);
        }
    }
}
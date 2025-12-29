<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        // 1. Validate exactly what is in your screenshot
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'icNumber' => ['required', 'string', 'unique:users,icNumber'],
            'matricNumber' => ['required', 'string', 'unique:customer,matricNumber'],
        ]);

        // 2. Use a transaction to ensure both tables are updated or none
        DB::transaction(function () use ($request) {
            
            // Create the User in the 'users' table (Supertype)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'icNumber' => $request->icNumber,
                'userType' => 'customer', // Identifies the role
            ]);

            // 3. Create the entry in the separate 'customer' table (Subtype)
            // We use the ID directly from the $user object we just created
            DB::table('customer')->insert([
                'userID'       => $user->userID, // Links the two tables
                'matricNumber' => $request->matricNumber, 
                'depoBalance'  => 0.00,
                'created_at'   => now(),
                'updated_at'   => now(),
                // matricNumber, license, etc., remain NULL for now
            ]);

            event(new Registered($user));
        });

        return redirect()->route('login')->with('status', 'Registration successful! Please login.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function registerCustomer(Request $request)
    {
        // Validate user + customer data
        $validator = Validator::make($request->all(), [
            // Stored in USERS table
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',

            // Stored in CUSTOMER table
            'matricNumber' => 'required|string|unique:customer,matricNumber',
            'licenseNumber' => 'nullable|string',
            'licenseExpiryDate' => 'nullable|date',
            'college' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'depoBalance' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create USER (parent)
            $user = User::create([
                'email' => $request->email,
                'name'  => $request->name,
                'password' => bcrypt('password') // you can change this later
            ]);

            // Create CUSTOMER (child)
            $customer = Customer::create([
                'userID' => $user->userID,
                'matricNumber' => $request->matricNumber,
                'licenseNumber' => $request->licenseNumber,
                'licenseExpiryDate' => $request->licenseExpiryDate,
                'college' => $request->college,
                'faculty' => $request->faculty,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relationship' => $request->emergency_contact_relationship,
                'depoBalance' => $request->depoBalance ?? 0,
                'rewardPoints' => 0,
                'isBlacklisted' => 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer registered successfully!',
                'user_id' => $user->userID,
                'customer_id' => $customer->userID
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }
}

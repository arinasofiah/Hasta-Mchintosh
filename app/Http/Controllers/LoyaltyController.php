<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyCard;
use App\Models\Promotion;
use App\Models\Voucher; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoyaltyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $customerProfile = DB::table('customer')->where('userID', $user->userID)->first();
        
        if (!$customerProfile) {
            return redirect()->route('customer.profile')->with('error', 'Please complete your profile first.');
        }
        
        $matric = $customerProfile->matricNumber;

        $card = LoyaltyCard::firstOrCreate(
            ['matricNumber' => $matric],
            ['stampCount' => 0]
        );

        $vouchers = Voucher::where('userID', $user->userID)
                         ->orderBy('created_at', 'desc') 
                         ->get();

        return view('customer.loyaltycard', compact('card', 'vouchers', 'user'));
    }
}
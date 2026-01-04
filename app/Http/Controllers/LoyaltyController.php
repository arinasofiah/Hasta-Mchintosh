<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyCard;
use App\Models\Promotion;
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

        $vouchers = $card->promotions()
                         ->withPivot('created_at', 'is_used') 
                         ->orderByPivot('created_at', 'desc')
                         ->get();

        return view('customer.loyaltycard', compact('card', 'vouchers', 'user'));
    }
}
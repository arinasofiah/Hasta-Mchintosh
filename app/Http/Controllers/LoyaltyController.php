<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyCard;
use App\Models\Promotion;
use App\Models\Voucher; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoyaltyController extends Controller
{
    // Define reward tiers
    protected $rewardTiers = [
        1 => 'RM10',
        2 => 'RM10',
        3 => 'RM20',
        4 => 'RM10',
        5 => 'RM10',
        6 => 'RM30',
        7 => 'RM10',
        8 => 'RM10',
        9 => 'RM20',
        10 => 'RM10',
        11 => 'RM10',
        12 => 'HALFDAY',
    ];

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

        return view('customer.loyaltycard', compact('card', 'vouchers', 'user', 'rewardTiers'));
    }

    // Optional: Add a method to issue reward when stamp is added
    public function addStamp(Request $request)
    {
        $user = Auth::user();
        $customerProfile = DB::table('customer')->where('userID', $user->userID)->first();
        if (!$customerProfile) {
            return response()->json(['error' => 'Profile incomplete'], 400);
        }

        $matric = $customerProfile->matricNumber;
        $card = LoyaltyCard::firstOrCreate(
            ['matricNumber' => $matric],
            ['stampCount' => 0]
        );

        // Increment stamp
        $card->stampCount += 1;
        $card->save();

        // Issue reward if applicable
        $this->issueRewardIfNeeded($card, $user);

        return response()->json(['success' => true, 'stampCount' => $card->stampCount]);
    }

    protected function issueRewardIfNeeded(LoyaltyCard $card, $user)
    {
        $count = $card->stampCount;

        if (!isset($this->rewardTiers[$count])) {
            return; // No reward for this stamp
        }

        $rewardType = $this->rewardTiers[$count];

        if ($rewardType === 'HALFDAY') {
            // Create half-day free booking voucher
            Voucher::create([
                'userID' => $user->userID,
                'voucherCode' => 'HALFDAY_' . strtoupper(uniqid()),
                'voucherType' => 'free_halfday',
                'value' => 0, // Not monetary
                'isUsed' => false,
                'expiryTime' => Carbon::now()->addMonths(3)->timestamp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Extract amount: e.g., 'RM10' → 10
            $amount = (int) filter_var($rewardType, FILTER_SANITIZE_NUMBER_INT);
            Voucher::create([
                'userID' => $user->userID,
                'voucherCode' => 'LOYALTY_' . strtoupper(uniqid()),
                'voucherType' => 'cash_reward',
                'value' => $amount,
                'isUsed' => false,
                'expiryTime' => Carbon::now()->addMonths(3)->timestamp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
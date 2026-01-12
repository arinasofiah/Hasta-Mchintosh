<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Vehicles;
use App\Models\Voucher;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        $vehicleModels = Vehicles::select('model')->distinct()->get();
        $vouchers = Voucher::where('isUsed', 0)->orderBy('created_at', 'desc')->get();

        $staffMembers = User::where('userType', 'staff')
                            ->join('staff', 'users.userID', '=', 'staff.userID')
                            ->select('users.name', 'users.email', 'staff.*')
                            ->get();

        return view('admin.promotions', compact('promotions', 'vehicleModels', 'vouchers', 'staffMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promotion,code',
            'title' => 'required',
            'discountValue' => 'required|numeric',
            'applicableDays' => 'required|integer',
        ]);

        $promo = new Promotion();
        $promo->code = $request->code;
        $promo->title = $request->title;
        $promo->description = $request->description;
        $promo->discountType = $request->discountType;
        $promo->discountValue = $request->discountValue;
        $promo->applicableDays = $request->applicableDays; 
        $promo->applicableModel = $request->applicableModel ?? 'All';
        $promo->save();

        return back()->with('success', 'Promotion created successfully!');
    }

    public function destroy($id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->delete();
        return back()->with('success', 'Promotion removed!');
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'voucherType' => 'required|in:cash_reward,free_hour',
            'value' => 'required|numeric',
            'expiryDate' => 'required|date',
        ]);

        $voucher = new Voucher();
        $voucher->voucherType = $request->voucherType; 
        $voucher->value = $request->value;
        $voucher->expiryTime = strtotime($request->expiryDate); 
        $voucher->isUsed = 0;
        $voucher->save();

        $typeMsg = $request->voucherType == 'free_hour' ? 'Free Hours' : 'Cash Reward';
        return back()->with('success', "Voucher ($typeMsg) created!");
    }

    public function destroyVoucher($id)
    {
        $voucher = Voucher::where('voucherCode', $id)->firstOrFail();
        $voucher->delete();
        return back()->with('success', 'Voucher deleted!');
    }


    public function resetCommission($staffUserID)
    {
        DB::table('staff')->where('userID', $staffUserID)->update(['commissionCount' => 0]);
        return back()->with('success', 'Commission marked as paid! Count reset to 0.');
    }

    public function updateCommission(Request $request, $staffUserID)
    {
        $request->validate(['commissionCount' => 'required|integer|min:0']);

        DB::table('staff')
            ->where('userID', $staffUserID)
            ->update(['commissionCount' => $request->commissionCount]);

        return back()->with('success', 'Commission count updated successfully!');
    }

    public function adminCommission()
    {
        $user = Auth::user();
        $admin = Admin::where('userID', $user->userID)->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin record not found.');
        }

        return view('admin.commission', compact('user', 'admin'));
    }
}
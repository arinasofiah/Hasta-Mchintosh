<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Vehicles;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();

        $vehicleModels = Vehicles::select('model')->distinct()->get();

        return view('admin.promotions', compact('promotions', 'vehicleModels'));
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
}
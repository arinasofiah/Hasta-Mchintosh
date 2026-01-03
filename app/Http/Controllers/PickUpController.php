<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;
use App\Models\PickUp;

class PickUpController extends Controller
{
    public function show()
    {
        $vehicle = Vehicles::findOrFail(1);
        return view('pickupform',compact('vehicle'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'pickupLocation' => 'required|string|max:255',
            'pickupDate' => 'required|date',
        ]);

        $pickup = new PickUp();

        $pickup->pickupLocation = $request->pickupLocation;
        $pickup->pickupDate = $request->pickupDate;
        $pickup->save();

        return redirect()->back()->with('showModal', true);
    }
}
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
            'pickupPhoto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'agreementForm' => 'required|in:yes'
        ]);

        $pickup = new PickUp();

        if ($request->hasFile('pickupPhoto')) {
        $file = $request->file('pickupPhoto');
        $fileName = time() . '_vehicle.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/pickups'), $fileName);
        $pickup->pickupPhoto = 'uploads/pickups/' . $fileName;
    }

        $pickup->pickupLocation = $request->pickupLocation;
        $pickup->pickupDate = $request->pickupDate;
        $pickup->agreementForm = $request->agreementForm === 'yes' ? 1 : 0;
        $pickup->save();

        return redirect()->back()->with('showModal', true);
    }
}
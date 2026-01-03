<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;
use App\Models\ReturnCar;

class ReturnController extends Controller
{
    public function show()
    {
        $vehicle = Vehicles::findOrFail(1);
        return view('returnform',compact('vehicle'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'returnLocation' => 'required|string|max:255',
            'returnDate' => 'required|date',
            'isFined' =>  'required|in:yes,no',
            'feedback' => 'required|min:20'
        ]);

        $returnCar = new ReturnCar();

        $returnCar->returnLocation = $request->returnLocation;
        $returnCar->returnDate = $request->returnDate;
        $returnCar->isfined = ($request->isFined === 'yes') ? 1 : 0;
        $returnCar->feedback = $request->feedback;
        $returnCar->save();

        return redirect()->back()->with('success', 'Data saved successfully!');
    }
}
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
            'returnPhoto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'trafficTicketPhoto' => 'image|mimes:jpeg,png,jpg|max:2048',
            'feedback' => 'required|min:20'
        ]);

        $returnCar = new ReturnCar();

         if ($request->hasFile('returnPhoto')) {
        $file = $request->file('returnPhoto');
        $fileName = time() . '_vehicle.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/returns'), $fileName);
        $returnCar->returnPhoto = 'uploads/returns/' . $fileName;
    }
        if ($request->hasFile('trafficTicketPhoto')) {
        $file = $request->file('trafficTicketPhoto');
        $fileName = time() . '_ticket.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/tickets'), $fileName);
        $returnCar->trafficTicketPhoto = 'uploads/tickets/' . $fileName;
    }

        $returnCar->returnLocation = $request->returnLocation;
        $returnCar->returnDate = $request->returnDate;
        $returnCar->isfined = ($request->isFined === 'yes') ? 1 : 0;
        $returnCar->feedback = $request->feedback;
        $returnCar->save();

        return redirect()->back()->with('success', 'Data saved successfully!');
    }
}
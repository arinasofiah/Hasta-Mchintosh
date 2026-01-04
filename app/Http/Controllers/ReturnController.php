<?php

namespace App\Http\Controllers;


use App\Models\Vehicles;
use Illuminate\Http\Request;
use App\Models\ReturnCar;
use App\Models\Bookings;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function show($bookingID)
    {
        $booking = Bookings::with('vehicle')->findOrFail($bookingID);

        $returnCar = ReturnCar::where('bookingID', $bookingID)->first();
        
        return view('returnform', [
            'booking' => $booking,
            'vehicle' => $booking->vehicle,
            'returnCar' => $returnCar,
        ]);
    }

     public function store(Request $request)
    {
        $request->validate([
            'returnID' => 'required|exists:return,returnID',
            'bookingID' => 'required|exists:booking,bookingID',
            'isFined' =>  'required|in:yes,no',
            'returnPhoto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'trafficTicketPhoto' => 'image|mimes:jpeg,png,jpg|max:2048',
            'feedback' => 'required|min:20',
            'fuelAmount' => 'required',
        ]);

        $returnCar = ReturnCar::findOrFail($request->returnID);

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

        $scheduledDeadline = Carbon::parse($returnCar->returnDate . ' ' . $returnCar->returnTime,'Asia/Kuala_Lumpur');
        $actualReturn = Carbon::now('Asia/Kuala_Lumpur');

        $hoursLate = 0;

        if ($actualReturn->gt($scheduledDeadline)) {
            $hoursLate = $actualReturn->diffInHours($scheduledDeadline, true);
        } else {
            $hoursLate = 0;
        }

        $returnCar->bookingID = $request->bookingID;
        $returnCar->fuelAmount = $request->fuelAmount;
        $returnCar->isfined = ($request->isFined === 'yes') ? 1 : 0;
        $returnCar->feedback = $request->feedback;
        $returnCar->lateHours = $hoursLate;
        $returnCar->save();

        return redirect()->route('dashboard')->with('success', 'Return completed!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VehicleController extends Controller
{
   
    /**
     * Show vehicle index page (grid of all available vehicles)
     */
    public function index(Request $request)
    {
        // Get search parameters from request
        $searchParams = [
            'pickup_date' => $request->input('pickup_date', now()->toDateString()),
            'pickup_time' => $request->input('pickup_time', '12:00'),
            'return_date' => $request->input('return_date', now()->addDay()->toDateString()),
            'return_time' => $request->input('return_time', '12:00'),
        ];
        
        // Validate dates
        $pickupCarbon = Carbon::parse($searchParams['pickup_date'] . ' ' . $searchParams['pickup_time']);
        $returnCarbon = Carbon::parse($searchParams['return_date'] . ' ' . $searchParams['return_time']);
        
        if ($pickupCarbon >= $returnCarbon) {
            return back()->withErrors(['error' => 'Return date must be after pickup date.']);
        }
        
        // Store in session for later use
        session(['booking_search_params' => $searchParams]);
        
        // Get available vehicles based on dates
        $vehicles = Vehicles::where('status', 'available')
            ->whereDoesntHave('booking', function ($query) use ($pickupCarbon, $returnCarbon) {
                $query->where('bookingStatus', 'confirmed')
                    ->where(function ($q) use ($pickupCarbon, $returnCarbon) {
                        $q->whereBetween('startDate', [$pickupCarbon, $returnCarbon])
                          ->orWhereBetween('endDate', [$pickupCarbon, $returnCarbon])
                          ->orWhere(function($q2) use ($pickupCarbon, $returnCarbon) {
                              $q2->where('startDate', '<=', $pickupCarbon)
                                  ->where('endDate', '>=', $returnCarbon);
                          });
                    });
            })
            ->get();
        
        // Calculate duration for display
        $diffHours = $pickupCarbon->diffInHours($returnCarbon);
        $days = floor($diffHours / 24);
        $hours = $diffHours % 24;
        
        $durationText = '';
        if ($days > 0) {
            $durationText = $days . ' day' . ($days > 1 ? 's' : '');
            if ($hours > 0) {
                $durationText .= ' ' . $hours . ' hour' . ($hours > 1 ? 's' : '');
            }
        } else {
            $durationText = $hours . ' hour' . ($hours != 1 ? 's' : '');
        }
        
        return view('vehiclesIndex', [
            'vehicles' => $vehicles,
            'searchParams' => $searchParams,
            'durationText' => $durationText
        ]);
    }
    
   

   public function select($id, Request $request)
{
    // NO authentication check here - allow guests to view
    
    $pickupDate = $request->query('pickup_date', now()->toDateString());
    $pickupTime = $request->query('pickup_time', '08:00');
    $returnDate = $request->query('return_date', now()->addDay()->toDateString());
    $returnTime = $request->query('return_time', '08:00');

    $pickupCarbon = Carbon::parse("$pickupDate $pickupTime");
    $returnCarbon = Carbon::parse("$returnDate $returnTime");

    if ($pickupCarbon >= $returnCarbon) {
        return back()->withErrors(['error' => 'Return date must be after pickup date.']);
    }

    $availableVehicles = Vehicles::where('status', 'available')
        ->whereDoesntHave('booking', function ($query) use ($pickupCarbon, $returnCarbon) {
            $query->where('bookingStatus', 'confirmed')
                ->where(function ($q) use ($pickupCarbon, $returnCarbon) {
                    $q->whereBetween('startDate', [$pickupCarbon, $returnCarbon])
                      ->orWhereBetween('endDate', [$pickupCarbon, $returnCarbon])
                      ->orWhere(function($q2) use ($pickupCarbon, $returnCarbon) {
                          $q2->where('startDate', '<=', $pickupCarbon)
                              ->where('endDate', '>=', $returnCarbon);
                      });
                });
        })
        ->get();

    $featuredVehicle = $availableVehicles->where('vehicleID', $id)->first() ?? $availableVehicles->first();
    $otherVehicles = $availableVehicles->where('vehicleID', '!=', optional($featuredVehicle)->vehicleID);

    return view('selectVehicle', compact(
        'featuredVehicle',
        'otherVehicles',
        'pickupDate',
        'pickupTime',
        'returnDate',
        'returnTime'
    ));
}
    

    public function reserveVehicle(Request $request, $vehicleID)
    {
  
        if (auth()->check() && auth()->user()->isBlacklisted) {
            return redirect()->route('welcome')
                ->with('error', 'You are blacklisted and cannot make bookings.');
        }

        $user = auth()->user();
        $vehicle = Vehicles::findOrFail($vehicleID);

        if ($vehicle->status !== 'available') {
            return back()->with('error', 'Sorry, this vehicle is no longer available.');
        }

        $vehicle->status = 'reserved';
        $vehicle->reservation_expires_at = now()->addMinutes(10);
        $vehicle->save();

        $booking = Bookings::create([
            'vehicleID' => $vehicle->vehicleID,
            'customerID' => $user->id, // ✅ Use standard 'id' instead of 'userID'
            'bookingStatus' => 'pending',
            'reservation_expires_at' => now()->addMinutes(10),
        ]);

        return redirect()->route('customer.payment', $booking->bookingID)
            ->with('success', 'Vehicle reserved! Complete payment within 10 minutes.');
    }

 
    public function showForm($vehicleID, Request $request)
    {
        if (auth()->check() && auth()->user()->isBlacklisted) {
            return redirect()->route('welcome')
                ->with('error', 'You are blacklisted and cannot make bookings.');
        }

        $vehicle = Vehicles::findOrFail($vehicleID);
        $pickupDate = $request->query('pickup_date', now()->toDateString());
        $pickupTime = $request->query('pickup_time', '08:00');
        $returnDate = $request->query('return_date', now()->addDay()->toDateString());
        $returnTime = $request->query('return_time', '08:00');

        return view('bookingform', compact('vehicle', 'pickupDate', 'pickupTime', 'returnDate', 'returnTime'));
    }

    public function getAvailableVehicles(Request $request)
    {
        $pickupDate = $request->pickup_date;
        $pickupTime = $request->pickup_time;
        $returnDate = $request->return_date;
        $returnTime = $request->return_time;

        $pickupDateTime = Carbon::parse("$pickupDate $pickupTime");
        $returnDateTime = Carbon::parse("$returnDate $returnTime");

        $availableVehicles = Vehicles::where('status', 'available')
            ->whereDoesntHave('booking', function ($query) use ($pickupDateTime, $returnDateTime) {
                $query->where('bookingStatus', 'confirmed')
                    ->where(function ($q) use ($pickupDateTime, $returnDateTime) {
                        $q->whereBetween('startDateTime', [$pickupDateTime, $returnDateTime])
                          ->orWhereBetween('endDateTime', [$pickupDateTime, $returnDateTime])
                          ->orWhere(function($q2) use ($pickupDateTime, $returnDateTime) {
                              $q2->where('startDateTime', '<=', $pickupDateTime)
                                  ->where('endDateTime', '>=', $returnDateTime);
                          });
                    });
            })
            ->get();

        return response()->json($availableVehicles);
    }

    public function start($vehicleID, Request $request)
    {
        $this->checkBlacklist();

        session([
            'pickup_date'  => $request->pickup_date,
            'pickup_time'  => $request->pickup_time,
            'return_date'  => $request->return_date,
            'return_time'  => $request->return_time,
        ]);

        return redirect()->route('booking.form', $vehicleID);
    }

    private function checkBlacklist()
    {
        if (auth()->check() && auth()->user()->is_blacklisted) {
            return redirect()->route('welcome')
                ->with('error', 'You are blacklisted and cannot make bookings.')
                ->send();
        }
    }
}
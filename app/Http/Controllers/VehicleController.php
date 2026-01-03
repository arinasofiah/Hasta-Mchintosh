<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicles::where('status', 'available');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('model', 'like', '%' . $search . '%')
                ->orWhere('plateNumber', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('vehicleType', trim($request->category));
        }

        $vehicles = $query->get();

        return view('welcome', compact('vehicles'));
    }


    public function select($id, Request $request)
    {
        $pickupDate = $request->query('pickup_date', now()->toDateString());
        $pickupTime = $request->query('pickup_time', '08:00');
        $returnDate = $request->query('return_date', now()->addDay()->toDateString());
        $returnTime = $request->query('return_time', '08:00');


        // Use Carbon for date comparison (date-only)
        $pickupCarbon = Carbon::parse("$pickupDate $pickupTime");
        $returnCarbon = Carbon::parse("$returnDate $returnTime");

        // Validate that return date is after pickup date
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

        if ($id) {
            $featuredVehicle = $availableVehicles->where('vehicleID', $id)->first() ?? $availableVehicles->first();
        } else {
            $featuredVehicle = $availableVehicles->first();
        }

        // Other available vehicles
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


    public function manage(Request $request)
    {
        
        $status = $request->get('status', 'available');
        
        $vehicles = Vehicles::where('status', $status)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $totalCount = Vehicles::count();

        return view('admin.fleet', compact('vehicles', 'totalCount', 'status'));
    }

    public function create()
    {
        return view('admin.fleet_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'vehicleType' => 'required',
            'plateNumber' => 'required|unique:vehicles',
            'pricePerDay' => 'required|numeric',
            'seat' => 'required|integer',
        ]);

        \App\Models\Vehicles::create($validated + [
            'status' => 'available',
            'fuelType' => 'Petrol',
            'fuelLevel' => 100,
            'pricePerHour' => $request->pricePerDay / 10,
        ]);


        return redirect()->route('admin.fleet')->with('success', 'Vehicle added to fleet successfully!');
    }
    

    public function update(Request $request, $vehicleID)
    {
        $vehicle = \App\Models\Vehicles::findOrFail($vehicleID);
        
        $validated = $request->validate([
            'model' => 'required|string',
        'plateNumber' => 'required|unique:vehicles,plateNumber,' . $vehicleID . ',vehicleID',
            'status' => 'required',
            'pricePerDay' => 'required|numeric',
        ]);

        $vehicle->update($validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully!');
    }


    public function destroy($id)
    {
        $vehicle = \App\Models\Vehicles::findOrFail($id);
        $vehicle->delete();
        
        return redirect()->back()->with('success', 'Vehicle deleted successfully!');
    }

    public function adminDashboard()
    {
        
        $totalVehicles = \App\Models\Vehicles::count();
        $availableCount = \App\Models\Vehicles::where('status', 'available')->count();
        $onRentCount = \App\Models\Vehicles::where('status', 'rented')->count();
        $maintenanceCount = \App\Models\Vehicles::where('status', 'maintenance')->count();

        
        $recentVehicles = \App\Models\Vehicles::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalVehicles', 
            'availableCount', 
            'onRentCount', 
            'maintenanceCount', 
            'recentVehicles'
        ));
    }

    public function adminVehicles(Request $request)
    {
        // 1. Get the current status filter from the URL (defaults to 'available')
        $status = $request->query('status', 'available');

        // 2. Calculate statistics for the top cards
        $totalCount = \App\Models\Vehicles::count(); // Matches your Blade's $totalCount
        $availableCount = \App\Models\Vehicles::where('status', 'available')->count();
        $onRentCount = \App\Models\Vehicles::where('status', 'rented')->count();
        
        // 3. Get the list of vehicles filtered by the active tab
        $vehicles = \App\Models\Vehicles::where('status', $status)->get();

        // 4. Pass everything to the view
        return view('admin.fleet', compact(
            'vehicles', 
            'totalCount', 
            'availableCount', 
            'onRentCount', 
            'status'
        ));
    }

    public function reserveVehicle(Request $request, $vehicleID)
    {
        $user = auth()->user();

        // 1. Check if vehicle is still available
        $vehicle = Vehicles::findOrFail($vehicleID);

        if ($vehicle->status !== 'available') {
            return back()->with('error', 'Sorry, this vehicle is no longer available.');
        }

        // 2. Reserve the vehicle for 10 minutes
        $vehicle->status = 'reserved';
        $vehicle->reservation_expires_at = now()->addMinutes(10);
        $vehicle->save();

        // 3. Create booking record
        $booking = Bookings::create([
            'vehicleID' => $vehicle->vehicleID,
            'customerID' => $user->userID,
            'bookingStatus' => 'pending',
            'reservation_expires_at' => now()->addMinutes(10),
            // You can fill in other fields like totalPrice, startDate, endDate later
        ]);

        return redirect()->route('customer.payment', $booking->bookingID)
            ->with('success', 'Vehicle reserved! Complete payment within 10 minutes.');
    }

    public function showForm($vehicleID, Request $request)
    {
        // Use the Vehicles model (not undefined Vehicle)
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
    session([
        'pickup_date'  => $request->pickup_date,
        'pickup_time'  => $request->pickup_time,
        'return_date'  => $request->return_date,
        'return_time'  => $request->return_time,
    ]);

    return redirect()->route('booking.form', $vehicleID);
}



}
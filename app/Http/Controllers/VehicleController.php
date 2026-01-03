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
        // Get dates/times from the request (with defaults)
        $pickupDate = $request->pickupDate ?? date('Y-m-d');
        $pickupTime = $request->pickupTime ?? '08:00';
        $returnDate = $request->returnDate ?? date('Y-m-d', strtotime('+1 day'));
        $returnTime = $request->returnTime ?? '08:00';

        // Combine into Carbon DateTime objects for easy comparison
        $pickupDateTime = Carbon::createFromFormat('Y-m-d H:i', $pickupDate . ' ' . $pickupTime);
        $returnDateTime = Carbon::createFromFormat('Y-m-d H:i', $returnDate . ' ' . $returnTime);

        // Validate that return is after pickup (optional but recommended)
        if ($pickupDateTime >= $returnDateTime) {
            return back()->withErrors(['error' => 'Return date/time must be after pickup date/time.']);
        }

        // Query available vehicles: status = 'available' AND no overlapping confirmed bookings
        $availableVehicles = Vehicles::where('status', 'available')
            ->whereDoesntHave('booking', function ($query) use ($pickupDateTime, $returnDateTime) {
                $query->where('bookingStatus', 'confirmed') // Only consider confirmed bookings
                    ->where(function ($q) use ($pickupDateTime, $returnDateTime) {
                        // Overlap condition: booking starts before return AND ends after pickup
                        $q->where('startDate', '<', $returnDateTime->toDateString())
                          ->where('endDate', '>', $pickupDateTime->toDateString());
                    });
            })
            ->get();

        // Select the featured vehicle (the one with the given $id, if available; otherwise, the first available)
        $featuredVehicle = $availableVehicles->find($id) ?? $availableVehicles->first();

        // If no featured vehicle is available, handle gracefully (e.g., show a message)
        if (!$featuredVehicle) {
            return view('selectVehicle', [
                'featuredVehicle' => null,
                'otherVehicles' => collect(),
                'pickupDate' => $pickupDate,
                'pickupTime' => $pickupTime,
                'returnDate' => $returnDate,
                'returnTime' => $returnTime,
                'error' => 'No vehicles available for the selected dates.'
            ]);
        }

        // Other available vehicles (exclude the featured one)
        $otherVehicles = $availableVehicles->where('vehicleID', '!=', $featuredVehicle->vehicleID);

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

}
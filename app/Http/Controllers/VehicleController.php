<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;

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
    $featuredVehicle = Vehicles::findOrFail($id);
    $otherVehicles = Vehicles::where('vehicleID', '!=', $id)->get();

    // Set default dates and times
    $pickupDate = $request->pickup_date ?? date('Y-m-d');
    $pickupTime = $request->pickup_time ?? '08:00';
    $returnDate = $request->return_date ?? date('Y-m-d', strtotime('+1 day'));
    $returnTime = $request->return_time ?? '08:00';

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
}
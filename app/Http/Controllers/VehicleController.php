<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
{
    // 1. Start with available vehicles
    $query = Vehicles::where('status', 'available');

    // 2. Search Logic (Model or Plate)
    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where(function($q) use ($search) {
            $q->where('model', 'like', '%' . $search . '%')
              ->orWhere('plateNumber', 'like', '%' . $search . '%');
        });
    }

    // 3. Category Filter (The Pill Buttons)
    // We check if category is filled and NOT equal to 'All'
    if ($request->filled('category') && $request->category !== 'All') {
        $query->where('vehicleType', trim($request->category));
    }

    $vehicles = $query->get();

    return view('welcome', compact('vehicles'));
}

  public function select($id)
{
    $vehicle = Vehicles::findOrFail($id);
    return view('selectVehicle', compact('vehicle'));  // Remove 'vehicles.' prefix
}


public function manage(Request $request)
{
    // Fetch vehicles based on status tab (default to active)
    $status = $request->get('status', 'available');
    
    $vehicles = Vehicles::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->get();

    $totalCount = Vehicles::count();

    return view('admin.fleet', compact('vehicles', 'totalCount', 'status'));
}

public function store(Request $request)
{
    // 1. Validate the form data
    $validated = $request->validate([
        'model' => 'required|string|max:255',
        'vehicleType' => 'required|string',
        'plateNumber' => 'required|string|unique:vehicles,plateNumber',
        'pricePerDay' => 'required|numeric',
        'fuelLevel' => 'required|integer',
        'fuelType' => 'required|string',
        'seat' => 'required|integer',
    ]);

    // 2. Create the vehicle record
    // We manually add 'status' as 'available' and calculate pricePerHour
    \App\Models\Vehicles::create($validated + [
        'status' => 'available',
        'pricePerHour' => $request->pricePerDay / 10, 
    ]);

    // 3. Go back to the fleet page with a success message
    return redirect()->route('admin.fleet')->with('success', 'Vehicle added successfully!');
}

public function adminDashboard()
{
    // 1. Prepare all the stats for your dashboard cards
    $totalVehicles = \App\Models\Vehicles::count();
    $availableCount = \App\Models\Vehicles::where('status', 'available')->count();
    $onRentCount = \App\Models\Vehicles::where('status', 'rented')->count();
    $maintenanceCount = \App\Models\Vehicles::where('status', 'maintenance')->count();

    // 2. Fetch the recent vehicles list (THIS FIXES YOUR ERROR)
    $recentVehicles = \App\Models\Vehicles::latest()->take(5)->get();

    // 3. Pass EVERYTHING to the view
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
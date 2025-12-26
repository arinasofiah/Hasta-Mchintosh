<?php

namespace App\Http\Controllers;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::where('status', 'available');

        if ($request->search) {
            $query->where('model', 'like', '%'.$request->search.'%');
        }

        if ($request->type) {
            $query->where('vehicleType', $request->type);
        }

        $vehicles = $query->get();

        return view('welcome', compact('vehicles'));
    }
}

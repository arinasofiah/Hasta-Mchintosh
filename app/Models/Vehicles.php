<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    protected $primaryKey = 'vehicleID';

    protected $fillable = [
        'vehicleType',
        'model',
        'plateNumber',
        'fuelLevel',
        'status',
        'pricePerHour',
        'pricePerDay'
    ];
}

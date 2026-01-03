<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;

    protected $primaryKey = 'vehicleID';

    protected $fillable = [
        'vehicleType',
        'model',
        'plateNumber',
        'fuelLevel',
        'fuelType',
        'ac',
        'seat',
        'transmission',
        'status',
        'pricePerHour',
        'pricePerDay',
        'image'
    ];

    public function booking()
    {
        return $this->hasMany(\App\Models\Bookings::class, 'vehicleID', 'vehicleID');
    }
}

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
        'reservation_expires_at'
    ];

    protected $dates = [
        'reservation_expires_at',
        'created_at',
        'updated_at'
    ];
    
    public function booking()
    {
        return $this->hasMany(\App\Models\Bookings::class, 'vehicleID', 'vehicleID');
    }
}

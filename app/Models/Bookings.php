<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'bookingID';
    protected $fillable = [
        'vehicleID',
        'startDate',
        'endDate',
        'bookingDuration',
        'bookingStatus',
        'totalPrice',
        'depositAmount',
        'rewardApplied',
        'reservation_expires_at'
    ];

    protected $dates = [
        'reservation_expires_at',
        'created_at',
        'updated_at'
    ];

    
    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicles::class, 'vehicleID', 'vehicleID');
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'bookingID', 'bookingID');
    }

    public function pickup()
{
    return $this->hasOne(PickUp::class, 'bookingID', 'bookingID');
}

public function returnCar()
{
    return $this->hasOne(ReturnCar::class, 'bookingID', 'bookingID');
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickUp extends Model
{
    protected $table = "pickup";
    protected $primaryKey = 'pickupID';       
    
    protected $fillable = [
        'bookingID',
        'pickupDate',
        'pickupLocation',
        'photo_front', 
        'photo_back',   
        'photo_left',   
        'photo_right',
        'pickupTime',
        'signature_path',
        'pickupComplete'
    ];

    public function booking() {
        return $this->belongsTo(Bookings::class, 'bookingID','bookingID');
    }
};
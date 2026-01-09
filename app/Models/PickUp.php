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
        'photo_front',  // New column
        'photo_back',   // New column
        'photo_left',   // New column
        'photo_right',
        'pickupTime',
        'signature_path'
    ];

    public function booking() {
        return $this->belongsTo(Bookings::class, 'bookingID','bookingID');
    }
};
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
        'pickupPhoto',
        'agreementForm',
        'pickupTime'
    ];

    public function booking() {
        return $this->belongsTo(Bookings::class, 'bookingID','bookingID');
    }
};
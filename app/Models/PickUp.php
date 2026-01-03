<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickUp extends Model
{
    protected $table = "pickup";
    protected $primaryKey = 'pickupID';       
    
    protected $fillable = [
        'pickupDate',
        'pickupLocation',
        'pickupPhoto',
        'agreementForm',
        'pickupTime'
    ];

    public function booking() {
        return $this->belongsTo(Booking::class, 'bookingID');
    }
};
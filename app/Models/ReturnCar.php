<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnCar extends Model
{
    protected $table = "return";
    protected $primaryKey = 'returnID';       
    
    protected $fillable = [
        'bookingID',
        'returnDate',
        'returnLocation',
        'isfined',
        'trafficTicketPhoto',
        'feedback',
        'fuelAmount',
        'returnTime',
        'agreementForm',
        'lateHours',
        'return_photo_front',
        'return_photo_back',
        'return_photo_left',
        'return_photo_right',
        'photo_dashboard',
        'photo_keys'
    ];

     public function booking()
    {
        return $this->belongsTo(Bookings::class, 'bookingID', 'bookingID');
    }
};
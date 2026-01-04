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
        'returnPhoto',
        'isfined',
        'trafficTicketPhoto',
        'feedback',
        'fuelAmount',
        'returnTime',
        'agreementForm',
    ];

     public function booking()
    {
        return $this->belongsTo(Bookings::class, 'bookingID', 'bookingID');
    }
};
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnTrip extends Model
{
    protected $table = "return";
    protected $primaryKey = 'returnID';

    protected $fillable = [
        'bookingID',
        'returnDate',
        'returnTime',
        'returnLocation',
        'returnPhoto',
        'agreementForm',
    ];

    public function booking()
    {
        return $this->belongsTo(Bookings::class, 'bookingID', 'bookingID');
    }
}

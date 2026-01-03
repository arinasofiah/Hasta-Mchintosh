<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnTrip extends Model
{
    protected $table = "return";
    protected $primaryKey = 'returnID';

    protected $fillable = [
        'bookingID',       // <-- must be fillable
        'returnDate',
        'returnTime',
        'returnLocation',  // <-- correct field name
        'returnPhoto',
        'agreementForm',
    ];

    public function booking()
    {
        return $this->belongsTo(Bookings::class, 'bookingID', 'bookingID');
    }
}

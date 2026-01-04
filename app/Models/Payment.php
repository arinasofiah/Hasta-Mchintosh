<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payment';
    protected $primaryKey = 'paymentID';
    protected $fillable = [
        'bookingID',  
        'bankName',
        'amount',
        'receiptImage',
        'paymentStatus',
        'paymentDate',
    ];

    public function booking()
    {
        return $this->belongsTo(Bookings::class, 'bookingID', 'bookingID');
    }
}
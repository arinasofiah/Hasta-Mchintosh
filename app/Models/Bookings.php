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
        'userID', // ✅ Added userID
        'customerID',
        'vehicleID',
        'startDate',
        'endDate',
        'bookingDuration',
        'bookingStatus',
        'totalPrice',
        'reservation_expires_at',
        'depositAmount',
        'promo_id',
        'voucher_id',
        'destination',
        'remark',
        'bank_name',
        'bank_owner_name',
        'bankNum', // ✅ Add bank account number
        'pay_amount_type',
        'payment_receipt_path',
        'for_someone_else',
        'driver_matric_number',
        'driver_license_number',
        'driver_college',
        'driver_faculty',
        'driver_deposit_balance'
    ];

    protected $casts = [
        'reservation_expires_at' => 'datetime',
        'for_someone_else' => 'boolean',
        'depositAmount' => 'decimal:2',
        'totalPrice' => 'decimal:2',
        'driver_deposit_balance' => 'decimal:2'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customerID', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicleID', 'vehicleID');
    }

    public function pickup()
    {
        return $this->hasOne(PickUp::class, 'bookingID', 'bookingID');
    }

    public function return()
    {
        return $this->hasOne(ReturnCar::class, 'bookingID', 'bookingID');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promo_id', 'promoID');
    }
}
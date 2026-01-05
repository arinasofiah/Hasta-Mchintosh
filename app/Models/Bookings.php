<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'bookingID';
   // app/Models/Bookings.php
protected $fillable = [
    'userID',
    'customerID',
    'vehicleID',
    'startDate',
    'endDate',
    'pickupTime',
    'returnTime',
    'pickupLocation',
    'returnLocation',
    'destination',
    'remark',
    'forSomeoneElse',
    'matricNumber',
    'licenseNumber',
    'college',
    'faculty',
    'depoBalance',
    'bankName',
    'bankOwnerName',
    'bankNum', // Add if exists
    'payAmount',
    'paymentReceipt',
    'promo_id',
    'voucher_id',
    'bookingStatus'
];

    protected $dates = [
        'reservation_expires_at',
        'created_at',
        'updated_at'
    ];

    
    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicles::class, 'vehicleID', 'vehicleID');
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'bookingID', 'bookingID');
    }

    public function pickup()
{
    return $this->hasOne(PickUp::class, 'bookingID', 'bookingID');
}

public function returnCar()
{
    return $this->hasOne(ReturnCar::class, 'bookingID', 'bookingID');
}

public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucherCode');
    }

// Bookings.php model - Remove customer() method and use:
 public function customer()
    {
        // Since your customer table doesn't have customerID,
        // and booking has customerID field, we need to figure out what it references
        
        // Option 1: If booking.customerID = customer.userID
        // return $this->belongsTo(Customer::class, 'customerID', 'userID');
        
        // Option 2: If booking.customerID = users.userID (customer info in users table)
        return $this->belongsTo(User::class, 'customerID', 'userID');
        
        // Option 3: If booking.customerID should actually reference users.id
        // return $this->belongsTo(User::class, 'customerID', 'id');
    }
    
    // Also add user relationship for userID field
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'bookingID';
    
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
        'bankNum',
        'payAmount',
        'paymentReceipt',
        'promo_id',
        'voucher_id',
        'bookingStatus',
        'booking_code',
        'totalPrice',
        'depositAmount',
        'bookingDuration'
    ];

    protected $dates = [
        'reservation_expires_at',
        'startDate',
        'endDate',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_code = self::generateBookingCode();
        });

        // When booking is updated, check vehicle status
        static::updated(function ($booking) {
            if (in_array($booking->bookingStatus, ['approved', 'confirmed'])) {
                $booking->updateVehicleStatus();
            }
            
            // If booking is cancelled or rejected, make vehicle available
            if (in_array($booking->bookingStatus, ['cancelled', 'rejected'])) {
                $vehicle = $booking->vehicle;
                if ($vehicle) {
                    $vehicle->status = 'available';
                    $vehicle->save();
                }
            }
        });

        // When booking is created with approved status
        static::created(function ($booking) {
            if (in_array($booking->bookingStatus, ['approved', 'confirmed'])) {
                $booking->updateVehicleStatus();
            }
        });
    }

    private static function generateBookingCode()
    {
        $date = now()->format('ymd');
        $prefix = 'B' . $date;
        
        $lastBooking = self::where('booking_code', 'like', $prefix . '%')
            ->orderBy('booking_code', 'desc')
            ->lockForUpdate()
            ->first();
        
        if ($lastBooking) {
            $lastNumber = (int) substr($lastBooking->booking_code, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Update vehicle status based on booking dates
     */
    public function updateVehicleStatus()
    {
        $vehicle = $this->vehicle;
        if (!$vehicle) return;

        $now = Carbon::now();
        $start = Carbon::parse($this->startDate . ' ' . $this->pickupTime);
        $end = Carbon::parse($this->endDate . ' ' . $this->returnTime);

        if ($this->bookingStatus === 'approved' && $now->between($start, $end)) {
            // Vehicle should be rented during booking period
            if ($vehicle->status !== 'rented') {
                $vehicle->status = 'rented';
                $vehicle->save();
            }
        } elseif ($this->bookingStatus === 'approved' && $now->lt($start)) {
            // Future approved booking, mark as reserved
            if ($vehicle->status !== 'reserved') {
                $vehicle->status = 'reserved';
                $vehicle->save();
            }
        }
    }

    /**
     * Check if booking is currently active (in rental period)
     */
    public function isCurrentlyActive()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->startDate . ' ' . $this->pickupTime);
        $end = Carbon::parse($this->endDate . ' ' . $this->returnTime);
        
        return $this->bookingStatus === 'approved' && 
               $now->between($start, $end);
    }

    /**
     * Check if booking will be active in the future
     */
    public function isFutureBooking()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->startDate . ' ' . $this->pickupTime);
        
        return $this->bookingStatus === 'approved' && 
               $start > $now;
    }

    /**
     * Check if booking has ended
     */
    public function isPastBooking()
    {
        $now = Carbon::now();
        $end = Carbon::parse($this->endDate . ' ' . $this->returnTime);
        
        return $this->bookingStatus === 'approved' && 
               $end < $now;
    }

    // Scopes
    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('bookingStatus', 'approved')
            ->where('startDate', '<=', $now)
            ->where('endDate', '>=', $now);
    }

    public function scopeOverlapping($query, $startDateTime, $endDateTime)
    {
        return $query->whereIn('bookingStatus', ['pending', 'confirmed', 'approved'])
            ->where(function($q) use ($startDateTime, $endDateTime) {
                $q->whereBetween('startDate', [$startDateTime, $endDateTime])
                  ->orWhereBetween('endDate', [$startDateTime, $endDateTime])
                  ->orWhere(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->where('startDate', '<=', $startDateTime)
                          ->where('endDate', '>=', $endDateTime);
                  });
            });
    }

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicleID', 'vehicleID');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'bookingID', 'bookingID');
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

    public function customer()
    {
        return $this->belongsTo(User::class, 'customerID', 'userID');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promo_id', 'promoID');
    }
}
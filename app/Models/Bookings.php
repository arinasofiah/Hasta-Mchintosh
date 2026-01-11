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
        'startDate',    // Date only
        'endDate',      // Date only
        'pickupLocation',
        'returnLocation',
        'destination',
        'remark',
        'matricNumber',
        'licenseNumber',
        'bankName',
        'bankOwnerName',
        'payAmount',
        'paymentReceipt',
        'bookingDuration',
        'totalPrice',
        'promo_id',
        'voucher_id',
        'depositAmount',
        'bookingStatus',
        'booking_code'
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
    
    // Use pickup and return times if available, otherwise use default times
    $start = Carbon::parse($this->startDate . ' ' . ($this->pickupTime ?? '08:00:00'));
    $end = Carbon::parse($this->endDate . ' ' . ($this->returnTime ?? '17:00:00'));
    
    // If pickupTime/returnTime fields don't exist, check related pickup/return tables
    if (!$this->pickupTime || !$this->returnTime) {
        $pickup = $this->pickup()->first();
        $return = $this->returnCar()->first();
        
        if ($pickup) {
            $start = Carbon::parse($pickup->pickupDate . ' ' . $pickup->pickupTime);
        }
        
        if ($return) {
            $end = Carbon::parse($return->returnDate . ' ' . $return->returnTime);
        }
    }

    switch ($this->bookingStatus) {
        case 'approved':
        case 'confirmed':
        case 'reserved':
            if ($now->lt($start)) {
                // Future booking - mark as reserved
                if ($vehicle->status !== 'reserved') {
                    $vehicle->status = 'reserved';
                    $vehicle->save();
                }
            } elseif ($now->between($start, $end)) {
                // Currently active booking - mark as rented/in_use
                if ($vehicle->status !== 'rented' && $vehicle->status !== 'in_use') {
                    $vehicle->status = 'rented'; // or 'in_use' depending on your system
                    $vehicle->save();
                }
            } elseif ($now->gt($end)) {
                // Past booking - mark as available (unless another booking exists)
                // But only if no other active booking for this vehicle
                $hasActiveBooking = self::where('vehicleID', $vehicle->vehicleID)
                    ->where('bookingID', '!=', $this->bookingID)
                    ->whereIn('bookingStatus', ['approved', 'confirmed', 'reserved'])
                    ->where(function($query) use ($now) {
                        $query->where('endDate', '>=', $now)
                              ->orWhere(function($q) use ($now) {
                                  $q->where('startDate', '<=', $now)
                                    ->where('endDate', '>=', $now);
                              });
                    })
                    ->exists();
                    
                if (!$hasActiveBooking && $vehicle->status !== 'available') {
                    $vehicle->status = 'available';
                    $vehicle->save();
                }
            }
            break;
            
        case 'cancelled':
        case 'rejected':
            // Only mark as available if no other active bookings
            $hasActiveBooking = self::where('vehicleID', $vehicle->vehicleID)
                ->where('bookingID', '!=', $this->bookingID)
                ->whereIn('bookingStatus', ['approved', 'confirmed', 'reserved'])
                ->where(function($query) use ($now) {
                    $query->where('endDate', '>=', $now)
                          ->orWhere(function($q) use ($now) {
                              $q->where('startDate', '<=', $now)
                                ->where('endDate', '>=', $now);
                          });
                })
                ->exists();
                
            if (!$hasActiveBooking && $vehicle->status !== 'available') {
                $vehicle->status = 'available';
                $vehicle->save();
            }
            break;
            
        case 'completed':
            // Booking completed - mark vehicle as available
            // Check if there are any upcoming bookings first
            $hasUpcomingBooking = self::where('vehicleID', $vehicle->vehicleID)
                ->where('bookingID', '!=', $this->bookingID)
                ->whereIn('bookingStatus', ['approved', 'confirmed', 'reserved'])
                ->where('startDate', '>', $now)
                ->exists();
                
            if (!$hasUpcomingBooking && $vehicle->status !== 'available') {
                $vehicle->status = 'available';
                $vehicle->save();
            } elseif ($hasUpcomingBooking && $vehicle->status !== 'reserved') {
                // If there's an upcoming booking, mark as reserved
                $vehicle->status = 'reserved';
                $vehicle->save();
            }
            break;
            
        case 'pending':
            // Pending bookings shouldn't affect vehicle status
            // But we might want to mark it as "reserved" temporarily
            // to prevent double booking during approval process
            if ($now->between($start, $end)) {
                // If booking is supposedly active but still pending, keep as is
                // Or mark as reserved if you want to hold it
                if ($vehicle->status === 'available') {
                    $vehicle->status = 'reserved';
                    $vehicle->save();
                }
            }
            break;
            
        default:
            // For any other status, ensure vehicle is available if no other bookings
            $hasActiveBooking = self::where('vehicleID', $vehicle->vehicleID)
                ->where('bookingID', '!=', $this->bookingID)
                ->whereIn('bookingStatus', ['approved', 'confirmed', 'reserved'])
                ->where(function($query) use ($now) {
                    $query->where('endDate', '>=', $now)
                          ->orWhere(function($q) use ($now) {
                              $q->where('startDate', '<=', $now)
                                ->where('endDate', '>=', $now);
                          });
                })
                ->exists();
                
            if (!$hasActiveBooking && $vehicle->status !== 'available') {
                $vehicle->status = 'available';
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

    // Add these methods to your Bookings model

public function payments()
{
    return $this->hasMany(Payment::class, 'bookingID', 'bookingID');
}

public function depositPayment()
{
    return $this->hasOne(Payment::class, 'bookingID', 'bookingID')
                ->where('paymentType', 'deposit')
                ->where('paymentStatus', 'approved');
}

public function remainingPayments()
{
    return $this->hasMany(Payment::class, 'bookingID', 'bookingID')
                ->where('paymentType', 'remaining');
}

/**
 * Calculate total paid amount
 */
public function getTotalPaidAttribute()
{
    return $this->payments()
                ->where('paymentStatus', 'approved')
                ->sum('amount');
}

/**
 * Calculate remaining balance
 */
public function getRemainingBalanceAttribute()
{
    $totalCost = $this->totalPrice + 50; // Rental price + fixed deposit
    $totalPaid = $this->total_paid;
    
    return max(0, $totalCost - $totalPaid);
}

/**
 * Check if booking is fully paid
 */
public function getIsFullyPaidAttribute()
{
    return $this->remaining_balance <= 0;
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
        return $this->hasOne(ReturnCar::class, 'bookingID', 'bookingID')
        ->from('return');
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
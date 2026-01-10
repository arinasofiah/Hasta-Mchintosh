<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'userID';

    protected $fillable = [
        'userID',
        'matricNumber',
        'licenseNumber',
        'licenseExpiryDate',
        'college',
        'faculty',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'depoBalance',
        'rewardPoints',
        'isBlacklisted',
        'blacklistReason',
        'ic_passport_path',
        'driving_license_path',
        'matric_card_path',
    ];

    protected $casts = [
        'licenseExpiryDate' => 'date',
        'depoBalance' => 'decimal:2',
        'rewardPoints' => 'integer',
        'isBlacklisted' => 'boolean',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    /**
     * Relationship to Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'userID', 'userID');
    }

    /**
     * Check if customer has valid license
     */
    public function hasValidLicense()
    {
        if (!$this->licenseNumber) {
            return false;
        }
        
        if ($this->licenseExpiryDate && $this->licenseExpiryDate->isPast()) {
            return false;
        }
        
        return true;
    }
}
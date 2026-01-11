<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'userID';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'icNumber', 
        'userType',
        'phoneNumber',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is blacklisted
     */
    // In your User model (App\Models\User.php)
public function isBlacklisted()
{
    // Only customers can be blacklisted
    if ($this->userType !== 'customer') {
        return false;
    }
    
    // Eager load the customer relationship
    if (!$this->relationLoaded('customer')) {
        $this->load('customer');
    }
    
    return $this->customer && $this->customer->isBlacklisted; // ← Changed to isBlacklisted
}

    public function customer()
    {
        return $this->hasOne(\App\Models\Customer::class, 'userID', 'userID');
    }

    public function staff()
    {
        return $this->hasOne(\App\Models\Staff::class, 'userID', 'userID');
    }

    public function telephone()
    {
        return $this->hasOne(Telephone::class, 'userID', 'userID');
    }

    public function getPhoneAttribute()
    {
        return $this->telephone ? $this->telephone->phoneNumber : null;
    }

    public function getPhoneNumberAttribute()
    {
        return $this->phone;
    }

    public function isAdmin()
    {
        return $this->userType === 'admin';
    }
    
    public function isStaff()
    {
        return $this->userType === 'staff';
    }
    
    public function isCustomer()
    {
        return $this->userType === 'customer';
    }
}
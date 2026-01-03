<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $primaryKey = 'userID';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'icNumber', 
        'userType',
        'phoneNumber',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer()
{
    // Use fully qualified namespace
    return $this->hasOne(\App\Models\Customer::class, 'userID', 'userID');
}

    public function staff()
{
    return $this->hasOne(\App\Models\Staff::class, 'userID', 'userID');
}

// Add this method to your User model:
public function telephone()
{
    return $this->hasOne(Telephone::class, 'userID', 'userID');
}

// Add accessor for phone number:
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
    
    /**
     * Check if user is staff
     */
    public function isStaff()
    {
        return $this->userType === 'staff';
    }
    
    /**
     * Check if user is customer
     */
    public function isCustomer()
    {
        return $this->userType === 'customer';
    }
}

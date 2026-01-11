<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

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
        'invitation_token',
        'invitation_sent_at',
        'invitation_accepted_at',
        'invitation_expires_at',
        'invitation_status',
        'invited_by'
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
            'invitation_sent_at' => 'datetime',
            'invitation_accepted_at' => 'datetime',
            'invitation_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if the user is blacklisted
     */
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
        
        return $this->customer && $this->customer->isBlacklisted;
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

    // Invitation methods
    public function createInvitation($invitedBy, $userType = 'staff')
    {
        $this->update([
            'invitation_token' => Str::random(60),
            'invitation_sent_at' => now(),
            'invitation_expires_at' => now()->addDays(7),
            'invitation_status' => 'pending',
            'invited_by' => $invitedBy,
            'userType' => $userType,
        ]);
        
        return $this;
    }

    public function isInvitationValid()
    {
        if ($this->invitation_status !== 'pending') {
            return false;
        }
        
        if (!$this->invitation_expires_at) {
            return false;
        }
        
        return now()->lessThan($this->invitation_expires_at);
    }

    public function completeInvitation($data)
    {
        $this->update([
            'name' => $data['name'],
            'icNumber' => $data['icNumber'],
            'password' => bcrypt($data['password']),
            'invitation_token' => null,
            'invitation_accepted_at' => now(),
            'invitation_status' => 'accepted',
            'email_verified_at' => now(),
        ]);
        
        return $this;
    }

    // Relationship to inviter
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by', 'userID');
    }

    // Scope for pending invitations
    public function scopePendingInvitations($query)
    {
        return $query->where('invitation_status', 'pending');
    }

    // Scope for staff users
    public function scopeStaffUsers($query)
    {
        return $query->where('userType', 'staff');
    }
}
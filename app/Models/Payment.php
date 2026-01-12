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
        'bankOwnerName',
        'amount',
        'paymentType',
        'receiptImage',
        'paymentStatus',
        'paymentDate',
        'qrPayment'
    ];
    
    // In App\Models\Payment.php
protected $casts = [
    'amount' => 'decimal:2',
    'paymentDate' => 'date:Y-m-d', // Add this format
];
    
    /**
     * Relationship with Booking
     */
    public function booking()
    {
        return $this->belongsTo(Bookings::class, 'bookingID', 'bookingID');
    }
    
    /**
     * Get payment type label
     */
    public function getPaymentTypeLabelAttribute()
    {
        $labels = [
            'deposit' => 'Deposit Payment',
            'full' => 'Full Payment',
            'remaining' => 'Remaining Balance Payment'
        ];
        
        return $labels[$this->paymentType] ?? $this->paymentType;
    }
    
    /**
     * Get payment status label with color
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => ['label' => 'Pending Review', 'color' => 'warning'],
            'approved' => ['label' => 'Approved', 'color' => 'success'],
            'rejected' => ['label' => 'Rejected', 'color' => 'danger'],
            'completed' => ['label' => 'Completed', 'color' => 'success'],
            'failed' => ['label' => 'Failed', 'color' => 'danger']
        ];
        
        return $labels[$this->paymentStatus] ?? ['label' => $this->paymentStatus, 'color' => 'secondary'];
    }
    
    /**
     * Check if this is a remaining balance payment
     */
    public function isRemainingBalancePayment()
    {
        return $this->paymentType === 'remaining';
    }
    
    /**
     * Scope for remaining balance payments
     */
    public function scopeRemainingBalance($query)
    {
        return $query->where('paymentType', 'remaining');
    }
    
    /**
     * Scope for deposit payments
     */
    public function scopeDeposit($query)
    {
        return $query->where('paymentType', 'deposit');
    }
    
    /**
     * Scope for approved payments
     */
    public function scopeApproved($query)
    {
        return $query->where('paymentStatus', 'approved');
    }
}
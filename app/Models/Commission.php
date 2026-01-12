<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $table = 'commission';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'staff_id',
        'commissionType',
        'commissionDate',
        'notes'
    ];
    
    protected $casts = [
        'commissionDate' => 'date',
    ];
    
    /**
     * Relationship to staff/user
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'userID');
    }
}
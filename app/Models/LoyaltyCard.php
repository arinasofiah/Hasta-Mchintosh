<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    use HasFactory;
    
    protected $table = 'loyalty_card';

    protected $primaryKey = 'matricNumber';
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['matricNumber', 'stampCount', 'rewardEligible', 'commissionAmount'];

    public function promotions()
    {
        return $this->belongsToMany(
            Promotion::class, 
            'loyalty_card_promotion', 
            'matricNumber', 
            'promotion_id'
        )
        ->withPivot('id', 'is_used', 'created_at') 
        ->withTimestamps();
    }
}
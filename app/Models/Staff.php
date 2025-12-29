<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    protected $table = 'staff';

    protected $primaryKey = 'userID';

    public $incrementing = false;

    protected $fillable = [
        'userID',
        'position',
        'commissionCount',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
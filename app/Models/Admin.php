<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $table = 'admin';

    protected $primaryKey = 'userID';

    public $incrementing = false;

    protected $fillable = [
        'userID',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
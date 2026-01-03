<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telephone extends Model
{
    use HasFactory;

    protected $primaryKey = 'phoneNumber';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'phoneNumber',
    ];

    // Define relationship to users
    public function users()
    {
        return $this->hasMany(User::class, 'phoneNumber', 'phoneNumber');
    }
}
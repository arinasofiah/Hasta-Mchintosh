<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnCar extends Model
{
    protected $table = "return";
    protected $primaryKey = 'returnID';       
    
    protected $fillable = [
        'returnDate',
        'returnLocation',
        'returnPhoto',
        'isfined',
        'trafficTicketPhoto',
        'feedback'
    ];
};
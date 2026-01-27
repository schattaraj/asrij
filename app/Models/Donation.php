<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
     protected $fillable = [
        'name',
        'email',
        'amount',
        'currency',
        'donation_type',
        'payment_intent_id',
        'status',
        'message',
    ];
    
}

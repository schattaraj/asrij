<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'receiver_type',
        'blood_group',
        'hospital',
    ];
}

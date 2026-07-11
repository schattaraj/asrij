<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationPayment extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'mobile',
        'email',
        'pan_no',
        'address',
        'amount',
        'screenshot',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

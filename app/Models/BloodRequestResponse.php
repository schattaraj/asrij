<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequestResponse extends Model
{
    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'status'
    ];

    public function request()
    {
        return $this->belongsTo(BloodRequest::class, 'blood_request_id');
    }

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}

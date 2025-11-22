<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hospital_name',
        'contact_number',
        'address',
        'pin_code',
        'license_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

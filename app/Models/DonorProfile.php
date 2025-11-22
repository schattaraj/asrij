<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_group',
        'last_donation_date',
        'contact_number',
        'address',
        'pin_code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

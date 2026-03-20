<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'volunteer_type',
        'organization',
        'contact_number',
        'address',
        'pin_code',
        'registration_number',
        'group_quantity',
        'president_name',
        'president_number',
        'secretary_name',
        'secretary_number',
        'account_name',
        'account_number',
        'extra_data',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'extra_data' => 'array',
    ];
}

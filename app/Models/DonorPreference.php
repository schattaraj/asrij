<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorPreference extends Model
{
    protected $fillable = [
        'user_id',
        'available_to_donate',
        'search_radius_km',
        'auto_respond',
    ];

    protected $casts = [
        'available_to_donate' => 'boolean',
        'search_radius_km' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

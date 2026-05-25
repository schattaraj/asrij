<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'notifications',
        'privacy',
        'security',
        'appearance',
        'language',
        'medical_info',
        'quiet_hours',
    ];

    protected $casts = [
        'notifications' => 'array',
        'privacy' => 'array',
        'security' => 'array',
        'appearance' => 'array',
        'quiet_hours' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

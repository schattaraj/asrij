<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BloodCamp extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'camp_date',
        'start_time',
        'end_time',
        'location',
        'slug',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($camp) {
            $camp->slug = Str::slug($camp->title) . '-' . time();
        });
    }
}

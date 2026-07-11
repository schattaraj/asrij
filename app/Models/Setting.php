<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Read a setting value by key (with optional default).
     */
    public static function get(string $key, $default = null)
    {
        return optional(static::where('key', $key)->first())->value ?? $default;
    }

    /**
     * Create or update a setting value.
     */
    public static function set(string $key, $value): self
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donor extends Model
{
    use HasFactory;

    // If you want to define a custom table name (optional if it's just 'donors')
    protected $table = 'donors';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        // 'type',
        'blood_group',
        'year_of_birth',
        'last_donation',
        'pin_code',
        'address'
    ];

    // You can also define relationships here if applicable (like User model)
    public function user()
    {
        return $this->belongsTo(User::class); // Assuming there's a User model
    }

    // Cast date fields to Carbon instances (optional, if you need to use date methods)
    protected $dates = [
        'last_donation',
    ];

    // Optionally, you can define the data types or other field rules here
    // For example, if 'year_of_birth' should be treated as an integer, you can cast it like so:
    protected $casts = [
        'year_of_birth' => 'integer',
    ];

}

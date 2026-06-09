<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'profession',
        'message',
        'image',
        'sort_order',
        'status'
    ];
}

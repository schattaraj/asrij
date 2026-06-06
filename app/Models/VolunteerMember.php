<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_organization_id',
        'last_donation',
        'position',
        'is_available',
        'user_id'
    ];

    protected $casts = [
        'last_donation' => 'date',
        'is_available'  => 'boolean',
    ];

    /**
     * Get the volunteer associated with this member.
     */
public function organization()
{
    return $this->belongsTo(VolunteerOrganization::class, 'volunteer_organization_id');
}
public function user()
{
    return $this->belongsTo(User::class);
}
}

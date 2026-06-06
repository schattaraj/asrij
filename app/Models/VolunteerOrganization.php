<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerOrganization extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'organization_name',
        'registration_number',
        'contact_number',
        'email',
        'address',
        'president_name',
        'president_number',
        'secretary_name',
        'secretary_number',
        'account_name',
        'account_number',
        'created_by'
    ];
    protected $hidden = [
    'account_number',
    ];

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function members()
{
    return $this->hasMany(VolunteerMember::class, 'volunteer_organization_id');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $table = 'blood_requests';

    protected $fillable = [
        'name',
        'blood_group',
        'hospital_name',
        'unit',
        'patient_type',
        'mobile',
        'whatsapp_number',
        'address',
        'pin_code',
        'email',
        'request_for',
        'submitted_by',
        'required_before',
        'required_before_unit',
        'status',
        'patient_latitude',
        'patient_longitude',
        'prescription'
    ];

    protected $casts = [
        'submitted_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['urgency'];

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function getUrgencyAttribute()
    {
        if (!$this->required_before || !$this->required_before_unit) {
            return 'normal';
        }

        $hours = $this->required_before_unit === 'days'
            ? $this->required_before * 24
            : $this->required_before;

        return $hours <= 48 ? 'urgent' : 'normal';
    }
    public function responses()
{
    return $this->hasMany(BloodRequestResponse::class);
}

public function donors()
{
    return $this->belongsToMany(User::class, 'blood_request_responses', 'blood_request_id', 'donor_id')
                ->withPivot('status')
                ->withTimestamps();
}
}

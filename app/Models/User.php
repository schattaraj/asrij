<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;
    use CanResetPasswordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'avatar',
        'whatsapp_number',
        'password',
        'role',
        'roles',
        'dob',
        'gender',
        'address',
        'pin_code',
        'blood_group',
        'latitude',
        'longitude',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'referred_by',
        'is_verified',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }
        // Already an absolute URL? (e.g. social-login imports)
        if (preg_match('#^https?://#i', $this->avatar)) {
            return $this->avatar;
        }
        return asset('storage/app/public/' . ltrim($this->avatar, '/'));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'roles' => 'array',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // 🔗 Relationships

    public function donor(): HasOne
    {
        return $this->hasOne(Donor::class);
    }

    public function receiver(): HasOne
    {
        return $this->hasOne(Receiver::class);
    }


    public function bloodBank(): HasOne
    {
        return $this->hasOne(BloodBank::class);
    }
    public function volunteerMemberships()
{
    return $this->hasMany(VolunteerMember::class);
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected array $fillable = [
        'firstname',
        'lastname',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'image_url',
        'hospital_id',
        'registered_with',
        'register_id',
        'last_login',
        'status'
    ];

    protected array $hidden = [
        'password'
    ];

    public function format($user) {
        return (object) [
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

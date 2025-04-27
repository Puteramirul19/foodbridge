<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone_number', 'role', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'recipient_id');
    }
}
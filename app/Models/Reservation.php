<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'donation_id', 'recipient_id', 'status'
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
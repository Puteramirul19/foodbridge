<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'donation_id', 
        'recipient_id', 
        'status', 
        'pickup_time', 
        'pickup_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pickup_date' => 'date',
        'pickup_time' => 'datetime:H:i',
    ];

    /**
     * Relationship with Donation
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Relationship with Recipient (User)
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Check if the reservation is still valid
     */
    public function isValid()
    {
        // Reservation is valid if it's pending and the pickup date is in the future
        return $this->status === 'pending' && 
               $this->pickup_date->isFuture();
    }

    /**
     * Check if the reservation is approaching
     */
    public function isApproaching()
    {
        // Reservation is approaching if it's within 24 hours
        return $this->status === 'pending' && 
               $this->pickup_date->isToday() && 
               Carbon::parse($this->pickup_time)->subHours(24)->isPast();
    }

    /**
     * Scope a query to only include active reservations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'pending')
                     ->whereDate('pickup_date', '>=', now());
    }
}
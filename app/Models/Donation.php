<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', // Donor ID
        'food_category',
        'food_description',
        'estimated_servings',
        'best_before',
        'donation_type',
        'pickup_location',
        'contact_number',
        'additional_instructions',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'best_before' => 'date',
        'estimated_servings' => 'integer'
    ];

    /**
     * Relationship with Donor (User)
     */
    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with Reservations
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Scope for available donations
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Get the donation's status badge
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'available' => 'badge-success',
            'reserved' => 'badge-warning',
            'completed' => 'badge-secondary',
            default => 'badge-light'
        };
    }

    /**
     * Check if donation is expiring soon
     */
    public function isExpiringSoon()
    {
        return now()->diffInDays($this->best_before, false) <= 1;
    }
}
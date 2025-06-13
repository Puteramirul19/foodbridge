<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Scope for non-expired donations
     */
    public function scopeNotExpired($query)
    {
        return $query->where('best_before', '>=', Carbon::today());
    }

    /**
     * Scope for expired donations
     */
    public function scopeExpired($query)
    {
        return $query->where('best_before', '<', Carbon::today());
    }

    /**
     * Scope for donations expiring soon (within specified days)
     */
    public function scopeExpiringSoon($query, $days = 1)
    {
        return $query->whereBetween('best_before', [
            Carbon::today(),
            Carbon::today()->addDays($days)
        ]);
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
            'expired' => 'badge-danger',
            default => 'badge-light'
        };
    }

    /**
     * Check if donation is expired
     */
    public function isExpired()
    {
        return Carbon::parse($this->best_before)->isPast();
    }

    /**
     * Check if donation is expiring soon
     */
    public function isExpiringSoon($days = 1)
    {
        $bestBefore = Carbon::parse($this->best_before);
        $now = Carbon::now();
        
        return !$this->isExpired() && 
               $now->diffInDays($bestBefore, false) <= $days;
    }

    /**
     * Get days left until expiration
     */
    public function getDaysUntilExpiration()
    {
        $bestBefore = Carbon::parse($this->best_before);
        $now = Carbon::now();
        
        return $now->diffInDays($bestBefore, false);
    }

    /**
     * Get formatted expiration status
     */
    public function getExpirationStatusAttribute()
    {
        if ($this->isExpired()) {
            return [
                'status' => 'expired',
                'class' => 'text-danger fw-bold',
                'message' => 'Expired'
            ];
        } elseif ($this->isExpiringSoon()) {
            return [
                'status' => 'expiring_soon',
                'class' => 'text-warning fw-bold',
                'message' => 'Expiring Soon'
            ];
        } else {
            $daysLeft = $this->getDaysUntilExpiration();
            return [
                'status' => 'fresh',
                'class' => 'text-success',
                'message' => $daysLeft . ' days left'
            ];
        }
    }

    /**
     * Manually update expired donations
     */
    public static function updateExpiredDonations()
    {
        try {
            // Use a raw update to minimize memory usage
            $updatedCount = DB::table('donations')
                ->where('status', 'available')
                ->where('best_before', '<', now())
                ->update(['status' => 'expired']);

            if ($updatedCount > 0) {
                Log::info("Updated {$updatedCount} donations to expired status.");
            }

            return $updatedCount;
        } catch (\Exception $e) {
            Log::error('Failed to update expired donations: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function booted()
    {
        parent::boot();

        // Automatically mark donations as expired when they pass their best_before date
        static::addGlobalScope('updateExpiredStatus', function ($query) {
            // Trigger update for expired donations
            self::updateExpiredDonations();
        });
    }
}
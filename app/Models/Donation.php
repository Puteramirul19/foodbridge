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
     * A donation is considered expired only AFTER its best before date
     */
    public function isExpired()
    {
        return Carbon::parse($this->best_before)->lt(Carbon::today());
    }

    /**
     * Get formatted expiration status
     * Ensures status is only marked as expired after the best before date
     */
    public function getExpirationStatusAttribute()
    {
        // If best before date is today or in the future, it's not expired
        if (Carbon::parse($this->best_before)->isSameDay(Carbon::today()) || 
            Carbon::parse($this->best_before)->isFuture()) {
            return [
                'status' => 'available',
                'class' => 'text-success',
                'message' => 'Available'
            ];
        }

        // If best before date is in the past, it's expired
        return [
            'status' => 'expired',
            'class' => 'text-danger fw-bold',
            'message' => 'Expired'
        ];
    }

    /**
     * Manually update expired donations
     */
    public static function updateExpiredDonations()
    {
        try {
            // Use a raw update to minimize memory usage
            // Note: Now only marking as expired when the date is strictly less than today
            $updatedCount = DB::table('donations')
                ->where('status', 'available')
                ->where('best_before', '<', now()->subDay())
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

    /**
     * Determine the status of the donation
     * 
     * @return string
     */
    public function determineStatus()
    {
        // If best before date is today or in the future, it's available
        if (Carbon::parse($this->best_before)->isSameDay(Carbon::today()) || 
            Carbon::parse($this->best_before)->isFuture()) {
            return 'available';
        }

        // If best before date is in the past, it's expired
        return 'expired';
    }
}
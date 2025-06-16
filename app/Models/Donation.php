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
     * Get active (pending) reservation
     */
    public function activeReservation()
    {
        return $this->hasOne(Reservation::class)->where('status', 'pending');
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
     * Check if donation is expiring soon (within 1 day)
     */
    public function isExpiringSoon()
    {
        $bestBefore = Carbon::parse($this->best_before);
        $today = Carbon::today();
        
        // Consider expiring soon if it expires today or tomorrow
        return $bestBefore->isSameDay($today) || 
            ($bestBefore->gte($today) && $bestBefore->lte($today->copy()->addDay()));
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
     * Manually update expired donations and clean up overdue reservations
     */
    public static function updateExpiredDonations()
    {
        try {
            $updatedCount = 0;

            // 1. Mark available donations as expired if past best before date
            $expiredAvailable = DB::table('donations')
                ->where('status', 'available')
                ->where('best_before', '<', Carbon::today())
                ->update(['status' => 'expired']);
            
            $updatedCount += $expiredAvailable;

            // 2. Handle reserved donations that are past best before date
            $expiredReserved = DB::table('donations')
                ->where('status', 'reserved')
                ->where('best_before', '<', Carbon::today())
                ->get();

            foreach ($expiredReserved as $donation) {
                // Mark the donation as expired
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update(['status' => 'expired']);

                // DELETE any pending reservations (don't cancel, just remove them)
                DB::table('reservations')
                    ->where('donation_id', $donation->id)
                    ->where('status', 'pending')
                    ->delete();

                $updatedCount++;
            }

            if ($updatedCount > 0) {
                Log::info("Updated {$updatedCount} donations to expired status and cleaned up overdue reservations.");
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
     * Determine the status of the donation based on current conditions
     * FIXED LOGIC: Properly handle cancelled reservations
     * 
     * @return string
     */
    public function determineStatus()
    {
        // If donation is past best before date, it's expired
        if (Carbon::parse($this->best_before)->lt(Carbon::today())) {
            return 'expired';
        }

        // If there's a completed reservation, it's completed
        if ($this->reservations()->where('status', 'completed')->exists()) {
            return 'completed';
        }

        // If there's an active (pending) reservation, it's reserved
        if ($this->reservations()->where('status', 'pending')->exists()) {
            return 'reserved';
        }

        // OTHERWISE, it's available (this includes when reservations are cancelled/deleted)
        return 'available';
    }

    /**
     * Check if the donation can be edited
     */
    public function canBeEdited()
    {
        return !$this->isExpired() && 
               $this->status === 'available';
    }

    /**
     * Check if the donation can be deleted
     */
    public function canBeDeleted()
    {
        return $this->status === 'available' && !$this->isExpired();
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpirationAttribute()
    {
        $bestBefore = Carbon::parse($this->best_before);
        $today = Carbon::today();
        
        if ($bestBefore->lt($today)) {
            return 0; // Already expired
        }
        
        return $today->diffInDays($bestBefore);
    }
}
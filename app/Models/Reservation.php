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
        // REMOVED: pickup_time cast - we'll handle this manually
    ];

    /**
     * Get formatted pickup time (e.g., "5:30 PM")
     */
    public function getFormattedPickupTimeAttribute()
    {
        if ($this->pickup_time) {
            // Handle both time formats - whether it's stored as time or datetime
            try {
                // Try parsing as time first (HH:MM:SS or HH:MM)
                if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $this->pickup_time)) {
                    return Carbon::createFromFormat('H:i:s', $this->pickup_time)->format('g:i A');
                } else {
                    // If it's a full datetime, extract just the time part
                    return Carbon::parse($this->pickup_time)->format('g:i A');
                }
            } catch (\Exception $e) {
                // Fallback: return as-is if parsing fails
                return $this->pickup_time;
            }
        }
        return null;
    }

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
     * Check if the reservation is still valid (not expired and pending)
     */
    public function isValid()
    {
        // Reservation is valid if it's pending and hasn't passed the food's best before date
        return $this->status === 'pending' && 
               !$this->donation->isExpired();
    }

    /**
     * Check if the reservation is overdue (past best before date but still pending)
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && 
               $this->donation->isExpired();
    }

    /**
     * Check if the reservation is approaching (within 24 hours)
     */
    public function isApproaching()
    {
        // Reservation is approaching if pickup date is today and it's pending
        return $this->status === 'pending' && 
               $this->pickup_date->isToday();
    }

    /**
     * Check if the reservation is due today
     */
    public function isDueToday()
    {
        return $this->pickup_date->isToday() && $this->status === 'pending';
    }

    /**
     * Check if the reservation is past due (pickup date has passed but still pending)
     */
    public function isPastDue()
    {
        return $this->status === 'pending' && 
               $this->pickup_date->lt(Carbon::today());
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning text-dark',
            'completed' => 'bg-success',
            default => 'bg-secondary'
        };
    }

    /**
     * Get formatted status with context
     */
    public function getFormattedStatusAttribute()
    {
        $baseStatus = ucfirst($this->status);
        
        if ($this->status === 'pending') {
            if ($this->isOverdue()) {
                return $baseStatus . ' (Overdue)';
            } elseif ($this->isPastDue()) {
                return $baseStatus . ' (Past Due)';
            } elseif ($this->isDueToday()) {
                return $baseStatus . ' (Due Today)';
            } elseif ($this->isApproaching()) {
                return $baseStatus . ' (Approaching)';
            }
        }
        
        return $baseStatus;
    }

    /**
     * Scope a query to only include active reservations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'pending')
                     ->whereHas('donation', function ($q) {
                         $q->where('best_before', '>=', Carbon::today());
                     });
    }

    /**
     * Scope a query to only include overdue reservations
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                     ->whereHas('donation', function ($q) {
                         $q->where('best_before', '<', Carbon::today());
                     });
    }

    /**
     * Scope a query to only include reservations due today
     */
    public function scopeDueToday($query)
    {
        return $query->where('status', 'pending')
                     ->whereDate('pickup_date', Carbon::today());
    }

    /**
     * Boot method to handle model events
     * FIXED: Properly update donation status when reservations are deleted or updated
     */
    protected static function booted()
    {
        parent::boot();

        // When a reservation is deleted (cancelled), update the donation status
        static::deleting(function ($reservation) {
            // Only update if it was a pending reservation
            if ($reservation->status === 'pending') {
                $donation = $reservation->donation;
                
                // After this reservation is deleted, determine new status
                // We need to check what the status will be AFTER deletion
                $remainingPendingCount = $donation->reservations()
                    ->where('status', 'pending')
                    ->where('id', '!=', $reservation->id) // Exclude the one being deleted
                    ->count();
                
                $hasCompleted = $donation->reservations()
                    ->where('status', 'completed')
                    ->exists();
                
                // Determine new status
                if ($donation->isExpired()) {
                    $newStatus = 'expired';
                } elseif ($hasCompleted) {
                    $newStatus = 'completed';
                } elseif ($remainingPendingCount > 0) {
                    $newStatus = 'reserved';
                } else {
                    // No more pending reservations, back to available
                    $newStatus = 'available';
                }
                
                $donation->status = $newStatus;
                $donation->save();
            }
        });

        // When a reservation status is updated, update the donation status
        static::updated(function ($reservation) {
            $donation = $reservation->donation;
            $newStatus = $donation->determineStatus();
            
            if ($donation->status !== $newStatus) {
                $donation->status = $newStatus;
                $donation->save();
            }
        });

        // When a new reservation is created, update donation status
        static::created(function ($reservation) {
            if ($reservation->status === 'pending') {
                $donation = $reservation->donation;
                $newStatus = $donation->determineStatus();
                
                if ($donation->status !== $newStatus) {
                    $donation->status = $newStatus;
                    $donation->save();
                }
            }
        });
    }
}
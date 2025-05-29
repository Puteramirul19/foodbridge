<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Donation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Carbon\Carbon;

class DonationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can update the donation.
     */
    public function update(User $user, Donation $donation)
    {
        // Only the donor who created the donation can update it
        // And only if the donation is not completed or expired
        return $user->id === $donation->user_id && 
               $donation->status !== 'completed' && 
               !$donation->isExpired();
    }

    /**
     * Determine if the user can delete the donation.
     */
    public function delete(User $user, Donation $donation)
    {
        // Only the donor who created the donation can delete it
        // And only if the donation is available (not reserved or completed)
        return $user->id === $donation->user_id && 
               $donation->status === 'available';
    }

    /**
     * Determine if the user can view the donation.
     */
    public function view(User $user, Donation $donation)
    {
        // Donors can view their own donations
        // Recipients can view available donations
        return $user->id === $donation->user_id || 
               ($user->role === 'recipient' && $donation->status === 'available');
    }
}
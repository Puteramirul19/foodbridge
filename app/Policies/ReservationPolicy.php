<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can cancel the reservation
     */
    public function cancel(User $user, Reservation $reservation)
    {
        // Only the recipient who made the reservation can cancel it
        return $user->id === $reservation->recipient_id && 
               $reservation->status === 'pending';
    }

    /**
     * Determine if the user can confirm pickup
     */
    public function confirmPickup(User $user, Reservation $reservation)
    {
        // Only the donor or recipient involved in the reservation can confirm pickup
        return $user->id === $reservation->recipient_id || 
               $user->id === $reservation->donation->donor_id;
    }

    /**
     * Determine if the user can view the reservation
     */
    public function view(User $user, Reservation $reservation)
    {
        // Recipient can view their own reservations
        // Donor can view reservations for their donations
        return $user->id === $reservation->recipient_id || 
               $user->id === $reservation->donation->donor_id;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * List recipient's reservations
     */
    public function index()
    {
        $reservations = Auth::user()->reservations;
        return view('recipient.reservations', compact('reservations'));
    }

    /**
     * Create a new reservation for a donation
     */
    public function store(Request $request, Donation $donation)
    {
        // Validate donation is available
        if ($donation->status !== 'available') {
            return redirect()->back()
                ->with('error', 'This donation is no longer available');
        }

        // Validate quantity
        $validator = Validator::make($request->all(), [
            'quantity_requested' => [
                'required', 
                'integer', 
                'min:1', 
                "max:{$donation->quantity}"
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create reservation
        $reservation = Reservation::create([
            'donation_id' => $donation->id,
            'recipient_id' => Auth::id(),
            'quantity_requested' => $request->quantity_requested,
            'status' => 'pending'
        ]);

        // Update donation status
        $donation->status = 'reserved';
        $donation->save();

        return redirect()->route('recipient.reservations.index')
            ->with('success', 'Donation reserved successfully');
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Reservation $reservation)
    {
        // Ensure user can only cancel their own reservations
        $this->authorize('cancel', $reservation);

        // Update donation status back to available
        $donation = $reservation->donation;
        $donation->status = 'available';
        $donation->save();

        // Delete the reservation
        $reservation->delete();

        return redirect()->route('recipient.reservations.index')
            ->with('success', 'Reservation cancelled successfully');
    }

    /**
     * Confirm pickup of a reservation
     */
    public function confirmPickup(Reservation $reservation)
    {
        // Ensure only the donor or recipient can confirm
        $this->authorize('confirmPickup', $reservation);

        $reservation->status = 'completed';
        $reservation->save();

        // Update donation status
        $donation = $reservation->donation;
        $donation->status = 'completed';
        $donation->save();

        return redirect()->back()
            ->with('success', 'Pickup confirmed successfully');
    }
}
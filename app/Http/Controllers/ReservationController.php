<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Store a new reservation
     */
    public function store(Request $request, Donation $donation)
    {
        // Validate the donation is available
        if ($donation->status !== 'available') {
            return redirect()->back()
                ->with('error', 'This donation is no longer available.');
        }

        // IMPROVED: More explicit date validation
        $validator = Validator::make($request->all(), [
            'pickup_time' => 'required|date_format:H:i',
            'pickup_date' => [
                'required', 
                'date',
                'after_or_equal:today', // Must be today or later
                function ($attribute, $value, $fail) use ($donation) {
                    $bestBefore = Carbon::parse($donation->best_before)->startOfDay();
                    $pickupDate = Carbon::parse($value)->startOfDay();

                    // ALLOW pickup on the same day as best before date
                    if ($pickupDate->gt($bestBefore)) {
                        $fail('Pickup date cannot be after the food\'s best before date (' . $bestBefore->format('d M Y') . ').');
                    }
                }
            ]
        ], [
            'pickup_date.required' => 'Please select a pickup date.',
            'pickup_date.after_or_equal' => 'Pickup date must be today or later.',
            'pickup_time.required' => 'Please select a pickup time.',
            'pickup_time.date_format' => 'Please enter a valid time format.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if user already has a reservation for this donation
        $existingReservation = Reservation::where('donation_id', $donation->id)
            ->where('recipient_id', Auth::id())
            ->exists();

        if ($existingReservation) {
            return redirect()->back()
                ->with('error', 'You have already requested this donation.');
        }

        // Create reservation
        $reservation = Reservation::create([
            'donation_id' => $donation->id,
            'recipient_id' => Auth::id(),
            'status' => 'pending',
            'pickup_time' => $request->pickup_time,
            'pickup_date' => $request->pickup_date
        ]);

        // The model event will automatically update donation status to 'reserved'

        return redirect()->route('recipient.reservations')
            ->with('success', 'Donation reserved successfully. You can pick it up until the expiry date.');
    }

    /**
     * Cancel a reservation (by recipient)
     * This DELETES the reservation and makes donation available again (if not expired)
     */
    public function cancel(Reservation $reservation)
    {
        // Ensure the user can only cancel their own reservations
        $this->authorize('cancel', $reservation);

        // Delete the reservation completely
        // The model event will automatically update the donation status
        $reservation->delete();

        return redirect()->route('recipient.reservations')
            ->with('success', 'Reservation cancelled successfully. The donation is now available for others.');
    }

    /**
     * Confirm pickup of a reservation (by donor)
     * This marks both reservation and donation as completed
     */
    public function confirmPickup(Reservation $reservation)
    {
        // Ensure only the donor can confirm pickup
        if (Auth::id() !== $reservation->donation->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Update reservation status
        $reservation->status = 'completed';
        $reservation->save();

        // The model event will automatically update donation status to 'completed'

        return redirect()->back()
            ->with('success', 'Pickup confirmed successfully. Thank you for completing this donation!');
    }

    /**
     * Mark pickup as not collected (by donor)
     * This DELETES the reservation and makes donation available again (if not expired)
     */
    public function markNotCollected(Reservation $reservation)
    {
        // Ensure only the donor can mark as not collected
        if (Auth::id() !== $reservation->donation->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Get the donation before deleting reservation
        $donation = $reservation->donation;

        // Delete the reservation completely (same as recipient cancelling)
        // The model event will automatically determine and set the correct donation status
        $reservation->delete();

        // Refresh donation to get updated status
        $donation->refresh();
        
        $statusText = $donation->status === 'expired' ? 'expired' : 'available again';

        return redirect()->back()
            ->with('success', "Marked as not collected. The donation is now {$statusText}.");
    }

    /**
     * Generate a QR code for reservation
     */
    public function generateQRCode(Reservation $reservation)
    {
        // Ensure the user is either the donor or recipient
        $this->authorize('view', $reservation);

        // Generate QR code data
        $qrData = [
            'reservation_id' => $reservation->id,
            'donation_id' => $reservation->donation_id,
            'donor_name' => $reservation->donation->donor->name,
            'recipient_name' => $reservation->recipient->name,
            'food_description' => $reservation->donation->food_description,
            'pickup_date' => $reservation->pickup_date->format('d M Y'),
            'pickup_time' => $reservation->pickup_time,
            'pickup_location' => $reservation->donation->pickup_location
        ];

        // Use a QR code library to generate the QR code
        // For this example, we'll use a simple approach
        $qrCodeString = json_encode($qrData);

        return view('reservations.qr-code', [
            'reservation' => $reservation,
            'qrCodeString' => $qrCodeString
        ]);
    }
}
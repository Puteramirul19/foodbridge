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

        // Validate reservation details
        $validator = Validator::make($request->all(), [
            'pickup_time' => 'required|date_format:H:i',
            'pickup_date' => [
                'required', 
                'date', 
                function ($attribute, $value, $fail) use ($donation) {
                    // Convert best before and pickup dates to Carbon instances
                    $bestBefore = Carbon::parse($donation->best_before);
                    $pickupDate = Carbon::parse($value);

                    // Ensure pickup date is not after best before date
                    // Allow reservation on the best before date
                    if ($pickupDate->gt($bestBefore)) {
                        $fail('Pickup date cannot be after the donation\'s best before date.');
                    }

                    // Ensure pickup date is today or later
                    if ($pickupDate->lt(Carbon::today())) {
                        $fail('Pickup date must be today or later.');
                    }
                }
            ]
        ], [
            'pickup_date.required' => 'Please select a pickup date.',
            'pickup_date' => 'Invalid pickup date.'
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

        // Update donation status
        $donation->status = 'reserved';
        $donation->save();

        return redirect()->route('recipient.reservations')
            ->with('success', 'Donation reserved successfully.');
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Reservation $reservation)
    {
        // Ensure the user can only cancel their own reservations
        $this->authorize('cancel', $reservation);

        // Update donation status back to available
        $donation = $reservation->donation;
        $donation->status = $donation->determineStatus(); // Use the method to set correct status
        $donation->save();

        // Delete the reservation
        $reservation->delete();

        return redirect()->route('recipient.reservations')
            ->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * Confirm pickup of a reservation
     */
    public function confirmPickup(Reservation $reservation)
    {
        // Ensure only the donor or recipient can confirm
        $this->authorize('confirmPickup', $reservation);

        // Update reservation status
        $reservation->status = 'completed';
        $reservation->save();

        // Update donation status
        $donation = $reservation->donation;
        $donation->status = 'completed';
        $donation->save();

        return redirect()->back()
            ->with('success', 'Pickup confirmed successfully.');
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
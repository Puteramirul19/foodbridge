<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $reservations = $user->reservations;
        return view('recipient.dashboard', compact('reservations'));
    }

    public function browseDonations()
    {
        $donations = Donation::where('status', 'available')->get();
        return view('recipient.browse-donations', compact('donations'));
    }

    public function reserveDonation(Donation $donation)
    {
        // Check if donation is available
        if ($donation->status !== 'available') {
            return back()->with('error', 'This donation is no longer available');
        }

        // Create reservation
        Auth::user()->reservations()->create([
            'donation_id' => $donation->id,
            'status' => 'pending'
        ]);

        // Update donation status
        $donation->update(['status' => 'reserved']);

        return redirect()->route('recipient.dashboard')
            ->with('success', 'Donation reserved successfully');
    }
}
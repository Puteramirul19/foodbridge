<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $donations = $user->donations;
        return view('donor.dashboard', compact('donations'));
    }

    public function createDonation()
    {
        return view('donor.create-donation');
    }

    public function storeDonation(Request $request)
    {
        $validatedData = $request->validate([
            'food_name' => 'required|string|max:255',
            'food_type' => 'required|in:perishable,non-perishable,prepared_meals',
            'quantity' => 'required|integer|min:1',
            'pickup_location' => 'required|string|max:255',
            'pickup_date' => 'required|date|after_or_equal:today',
            'urgency_level' => 'required|in:low,medium,high'
        ]);

        $donation = Auth::user()->donations()->create($validatedData);

        return redirect()->route('donor.donations.index')
            ->with('success', 'Donation created successfully');
    }

    public function listDonations()
    {
        $donations = Auth::user()->donations;
        return view('donor.donations', compact('donations'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DonationController extends Controller
{
    public function create()
    {
        return view('donations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_category' => 'required|in:produce,bakery,prepared_meals,packaged_goods,dairy,other',
            'food_description' => 'required|string|max:500',
            'estimated_servings' => 'required|integer|min:1|max:1000',
            'best_before' => 'required|date|after_or_equal:today',
            'donation_type' => 'required|in:direct,dropoff',
            'pickup_location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'additional_instructions' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create donation with user_id instead of donor_id
        $donation = Donation::create([
            'user_id' => Auth::id(), // Use user_id from authenticated user
            'food_category' => $request->food_category,
            'food_description' => $request->food_description,
            'estimated_servings' => $request->estimated_servings,
            'best_before' => $request->best_before,
            'donation_type' => $request->donation_type,
            'pickup_location' => $request->pickup_location,
            'contact_number' => $request->contact_number,
            'additional_instructions' => $request->additional_instructions,
            'status' => 'available'
        ]);

        return redirect()->route('donor.donations.index')
            ->with('success', 'Donation created successfully');
    }

    public function index()
    {
        $donations = Auth::user()->donations;
        return view('donor.donations', compact('donations'));
    }

    public function edit(Donation $donation)
    {
        // Use $this->authorize() instead of authorize()
        $this->authorize('update', $donation);
        return view('donor.edit-donation', compact('donation'));
    }

    public function update(Request $request, Donation $donation)
    {
        // Use $this->authorize() instead of authorize()
        $this->authorize('update', $donation);

        $validator = Validator::make($request->all(), [
            'food_category' => 'required|in:produce,bakery,prepared_meals,packaged_goods,dairy,other',
            'food_description' => 'required|string|max:500',
            'estimated_servings' => 'required|integer|min:1|max:1000',
            'best_before' => 'required|date|after_or_equal:today',
            'donation_type' => 'required|in:direct,dropoff',
            'pickup_location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'additional_instructions' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $donation->update($request->except(['_token', '_method']));

        return redirect()->route('donor.donations.index')
            ->with('success', 'Donation updated successfully');
    }

    public function destroy(Donation $donation)
    {
        // Use $this->authorize() instead of authorize()
        $this->authorize('delete', $donation);

        $donation->delete();

        return redirect()->route('donor.donations.index')
            ->with('success', 'Donation deleted successfully');
    }
}
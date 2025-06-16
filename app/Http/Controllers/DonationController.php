<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class DonationController extends Controller
{
    /**
     * Get standardized food categories with emojis
     */
    private function getFoodCategories()
    {
        return [
            'fruits_vegetables' => '🥕 Fruits & Vegetables',
            'bread_rice' => '🍞 Bread, Rice & Grains',
            'cooked_food' => '🍲 Cooked Food & Meals',
            'canned_bottled' => '🥫 Canned & Bottled Items',
            'milk_eggs' => '🥛 Milk, Eggs & Dairy',
            'other' => '📦 Other Food Items'
        ];
    }

    /**
     * Display the create donation form
     */
    public function create()
    {
        $foodCategories = $this->getFoodCategories();
        return view('donations.create', compact('foodCategories'));
    }

    /**
     * Store a new donation
     */
    public function store(Request $request)
    {
        // Validate donation input - UPDATED: Removed contact_number validation
        $validator = Validator::make($request->all(), [
            'food_category' => 'required|in:fruits_vegetables,bread_rice,cooked_food,canned_bottled,milk_eggs,other',
            'food_description' => 'required|string|max:500',
            'estimated_servings' => 'required|integer|min:1|max:1000',
            'best_before' => 'required|date|after_or_equal:today',
            'donation_type' => 'required|in:direct,dropoff',
            'pickup_location' => 'required|string|max:255',
            // REMOVED: contact_number validation - using donor's phone from profile
            'additional_instructions' => 'nullable|string|max:500'
        ]);

        // Check validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create donation - UPDATED: Removed contact_number
        $donation = new Donation([
            'user_id' => Auth::id(),
            'food_category' => $request->food_category,
            'food_description' => $request->food_description,
            'estimated_servings' => $request->estimated_servings,
            'best_before' => $request->best_before,
            'donation_type' => $request->donation_type,
            'pickup_location' => $request->pickup_location,
            // REMOVED: contact_number - will use donor's phone_number from users table
            'additional_instructions' => $request->additional_instructions
        ]);
        $donation->status = $donation->determineStatus();
        $donation->save();

        // Clear any cached dashboard data for the user
        Cache::forget('donor_dashboard_' . Auth::id());

        // Redirect with success message
        return redirect()->route('donor.donations.index')
            ->with('success', 'Donation created successfully');
    }

    /**
     * Display list of donations for the donor
     */
    public function index()
    {
        // Fetch only the current user's donations
        $donations = Auth::user()->donations()->latest()->get();
        $foodCategories = $this->getFoodCategories();
        return view('donor.donations', compact('donations', 'foodCategories'));
    }

    /**
     * Show details of a specific donation
     */
    public function show(Donation $donation)
    {
        // Authorize the view action
        $this->authorize('view', $donation);

        $foodCategories = $this->getFoodCategories();
        return view('donor.donation-details', compact('donation', 'foodCategories'));
    }
    
    /**
     * Show edit form for a specific donation
     */
    public function edit(Donation $donation)
    {
        // Authorize the edit action
        $this->authorize('update', $donation);
        $foodCategories = $this->getFoodCategories();
        return view('donor.edit-donation', compact('donation', 'foodCategories'));
    }

    /**
     * Update a specific donation
     */
    public function update(Request $request, Donation $donation)
    {
        // Authorize the update action
        $this->authorize('update', $donation);

        // Validate donation input - UPDATED: Removed contact_number validation
        $validator = Validator::make($request->all(), [
            'food_category' => 'required|in:fruits_vegetables,bread_rice,cooked_food,canned_bottled,milk_eggs,other',
            'food_description' => 'required|string|max:500',
            'estimated_servings' => 'required|integer|min:1|max:1000',
            'best_before' => 'required|date|after_or_equal:today',
            'donation_type' => 'required|in:direct,dropoff',
            'pickup_location' => 'required|string|max:255',
            // REMOVED: contact_number validation
            'additional_instructions' => 'nullable|string|max:500'
        ]);

        // Check validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update donation - UPDATED: Removed contact_number
        $donation->fill($request->only([
            'food_category',
            'food_description',
            'estimated_servings',
            'best_before',
            'donation_type',
            'pickup_location',
            // REMOVED: contact_number
            'additional_instructions'
        ]));

        // Determine and set the status
        $donation->status = $donation->determineStatus();
        $donation->save();

        // Clear cached dashboard data
        Cache::forget('donor_dashboard_' . Auth::id());

        // Redirect with success message
        return redirect()->route('donor.donations.index')
            ->with('success', 'Donation updated successfully');
    }

    /**
     * Delete a donation
     */
    public function destroy(Donation $donation)
    {
        // Authorize the delete action
        $this->authorize('delete', $donation);

        $donation->delete();

        // Clear cached dashboard data
        Cache::forget('donor_dashboard_' . Auth::id());

        return redirect()->route('donor.donations.index')
            ->with('success', 'Donation deleted successfully');
    }

    /**
     * Get formatted food category display name
     */
    public static function getFormattedFoodCategory($category)
    {
        $categories = [
            'fruits_vegetables' => '🥕 Fruits & Vegetables',
            'bread_rice' => '🍞 Bread, Rice & Grains',
            'cooked_food' => '🍲 Cooked Food & Meals',
            'canned_bottled' => '🥫 Canned & Bottled Items',
            'milk_eggs' => '🥛 Milk, Eggs & Dairy',
            'other' => '📦 Other Food Items'
        ];

        return $categories[$category] ?? ucfirst(str_replace('_', ' ', $category));
    }

    /**
     * Get formatted food category for HTML safe output
     */
    public static function getFormattedFoodCategoryHtml($category)
    {
        return htmlspecialchars_decode(self::getFormattedFoodCategory($category), ENT_QUOTES);
    }
}
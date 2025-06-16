<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DonorController extends Controller
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
     * Show the donor dashboard with comprehensive donation insights
     * UPDATED: Total servings only count COMPLETED donations
     */
    public function dashboard()
    {
        // Get the authenticated donor
        $user = Auth::user();
        
        // Flush any existing cache for this user's dashboard
        Cache::forget('donor_dashboard_' . $user->id);

        // Get the donor's donations with eager loading to reduce queries
        $donations = $user->donations()->with(['donor', 'reservations.recipient'])->get();

        // Calculate donation statistics
        $stats = [
            'total' => $donations->count(),
            'completed' => $donations->where('status', 'completed')->count(),
            'reserved' => $donations->where('status', 'reserved')->count(),
            'available' => $donations->where('status', 'available')->count(),
            'expired' => $donations->where('status', 'expired')->count(),
            // UPDATED: Only count servings from COMPLETED donations
            'totalServings' => $donations->where('status', 'completed')->sum('estimated_servings'),
            // Active servings (excluding expired)
            'activeServings' => $donations->whereNotIn('status', ['expired'])->sum('estimated_servings')
        ];

        // Get recent donations (last 5, excluding expired for better UX)
        $recentDonations = $donations->sortByDesc('created_at')->take(5);

        // Get pending pickups (donations that are reserved and need completion)
        $pendingPickups = $donations->filter(function ($donation) {
            return $donation->status === 'reserved' && 
                   $donation->reservations->where('status', 'pending')->count() > 0;
        });

        // UPDATED: Calculate donations by food category (ONLY COMPLETED DONATIONS) with new categories
        $completedDonations = $donations->where('status', 'completed');
        $donationsByCategory = $completedDonations->groupBy('food_category')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'servings' => $group->sum('estimated_servings')
                ];
            });

        // UPDATED: Calculate monthly donation trend (ONLY COMPLETED DONATIONS)
        $monthlyDonations = $completedDonations->groupBy(function($donation) {
            return Carbon::parse($donation->created_at)->format('M');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'servings' => $group->sum('estimated_servings')
            ];
        });

        // Ensure we have data for the last 6 months
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $finalMonthlyDonations = collect();
        foreach ($monthNames as $monthName) {
            $finalMonthlyDonations[$monthName] = $monthlyDonations->get($monthName, ['count' => 0, 'servings' => 0]);
        }

        // Get food categories for consistent display
        $foodCategories = $this->getFoodCategories();

        return view('donor.dashboard', [
            'donations' => $donations,
            'recentDonations' => $recentDonations,
            'pendingPickups' => $pendingPickups,
            'stats' => $stats,
            'donationsByCategory' => $donationsByCategory,
            'monthlyDonations' => $finalMonthlyDonations,
            'foodCategories' => $foodCategories
        ]);
    }

    /**
     * Show pending pickups that need donor confirmation
     */
    public function pendingPickups()
    {
        $user = Auth::user();
        
        // Get donations that are reserved and have pending reservations
        $pendingPickups = $user->donations()
            ->with(['reservations.recipient'])
            ->where('status', 'reserved')
            ->whereHas('reservations', function ($query) {
                $query->where('status', 'pending');
            })
            ->latest()
            ->get();

        $foodCategories = $this->getFoodCategories();

        return view('donor.pending-pickups', compact('pendingPickups', 'foodCategories'));
    }
}
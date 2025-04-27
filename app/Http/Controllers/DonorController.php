<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DonorController extends Controller
{
    /**
     * Show the donor dashboard with comprehensive donation insights
     */
    public function dashboard()
    {
        // Get the authenticated donor
        $user = Auth::user();
        
        // Cache key based on user ID and current time (hourly)
        $cacheKey = 'donor_dashboard_' . $user->id . '_' . now()->format('YmdH');

        // Cache dashboard data for 1 hour
        $dashboardData = Cache::remember($cacheKey, now()->addHour(), function () use ($user) {
            // Get the donor's donations
            $donations = $user->donations ?? collect();

            // Calculate donation statistics
            $totalDonations = $donations->count();
            $completedDonations = $donations->where('status', 'completed')->count();
            $availableDonations = $donations->where('status', 'available')->count();
            $reservedDonations = $donations->where('status', 'reserved')->count();

            // Calculate total estimated servings
            $totalServings = $donations->sum('estimated_servings');

            // Get recent donations (last 5)
            $recentDonations = $donations->sortByDesc('created_at')->take(5);

            // Calculate donations by food category
            $donationsByCategory = $donations->groupBy('food_category')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'servings' => $group->sum('estimated_servings')
                    ];
                });

            // Calculate monthly donation trend
            $monthlyDonations = $donations->groupBy(function($donation) {
                return Carbon::parse($donation->created_at)->format('M Y');
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'servings' => $group->sum('estimated_servings')
                ];
            });

            return [
                'donations' => $donations,
                'recentDonations' => $recentDonations,
                'stats' => [
                    'total' => $totalDonations,
                    'completed' => $completedDonations,
                    'available' => $availableDonations,
                    'reserved' => $reservedDonations,
                    'totalServings' => $totalServings
                ],
                'donationsByCategory' => $donationsByCategory,
                'monthlyDonations' => $monthlyDonations
            ];
        });

        // Return the dashboard view with cached data
        return view('donor.dashboard', $dashboardData);
    }

    /**
     * Get detailed insights about donations with caching
     */
    public function insights()
    {
        $user = Auth::user();

        // Cache key based on user ID and current time (hourly)
        $cacheKey = 'donor_insights_' . $user->id . '_' . now()->format('YmdH');

        // Cache insights for 1 hour
        $insights = Cache::remember($cacheKey, now()->addHour(), function () use ($user) {
            $donations = $user->donations;

            return [
                'donations' => $donations,
                'insights' => [
                    'averageServingsPerDonation' => $donations->avg('estimated_servings'),
                    'mostDonatedCategory' => $donations->groupBy('food_category')
                        ->sortByDesc(function ($group) {
                            return $group->count();
                        })
                        ->keys()
                        ->first(),
                    'totalImpact' => [
                        'foodSaved' => $donations->sum('estimated_servings'),
                        'potentialWasteAvoided' => $donations->sum('estimated_servings') * 0.25 // Estimate
                    ]
                ]
            ];
        });

        return view('donor.insights', $insights);
    }
}
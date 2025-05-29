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
        
        // Flush any existing cache for this user's dashboard
        Cache::forget('donor_dashboard_' . $user->id);

        // Get the donor's donations with eager loading to reduce queries
        $donations = $user->donations()->with('donor')->get();

        // Calculate donation statistics
        $stats = [
            'total' => $donations->count(),
            'completed' => $donations->where('status', 'completed')->count(),
            'reserved' => $donations->where('status', 'reserved')->count(),
            'totalServings' => $donations->sum('estimated_servings')
        ];

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

        return view('donor.dashboard', [
            'donations' => $donations,
            'recentDonations' => $recentDonations,
            'stats' => $stats,
            'donationsByCategory' => $donationsByCategory,
            'monthlyDonations' => $finalMonthlyDonations
        ]);
    }

    /**
     * Get detailed insights about donations
     */
    public function insights()
    {
        $user = Auth::user();

        // Get donations with eager loading
        $donations = $user->donations;

        // Calculate insights
        $insights = [
            'averageServingsPerDonation' => $donations->avg('estimated_servings') ?? 0,
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
        ];

        return view('donor.insights', [
            'donations' => $donations,
            'insights' => $insights
        ]);
    }
}
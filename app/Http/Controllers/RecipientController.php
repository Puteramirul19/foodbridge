<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RecipientController extends Controller
{
    /**
     * Show the recipient dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Fetch recent reservations with donation details
        $reservations = $user->reservations()
            ->with('donation')
            ->latest()
            ->paginate(5);

        // Calculate statistics
        $stats = [
            'totalReservations' => $user->reservations->count(),
            'activeReservations' => $user->reservations()->where('status', 'pending')->count(),
            'completedReservations' => $user->reservations()->where('status', 'completed')->count()
        ];

        // Get the last 6 months dynamically
        $monthlyReservations = $user->reservations()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereRaw('created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->count;
            });

        // Prepare dynamic month names and data
        $monthNames = [];
        $reservationTrends = [];
        
        // Generate last 6 months dynamically
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthNames[] = $monthDate->format('M');
            $monthKey = $monthDate->month;
            $reservationTrends[] = $monthlyReservations->get($monthKey, 0);
        }

        return view('recipient.dashboard', [
            'reservations' => $reservations,
            'stats' => $stats,
            'reservationTrends' => $reservationTrends,
            'monthNames' => $monthNames
        ]);
    }

    /**
     * Browse available donations
     */
    public function browseDonations(Request $request)
    {
        // Force update expired donations first
        Donation::updateExpiredDonations();

        // Base query for available donations that are not expired
        $query = Donation::where('status', 'available')
                        ->where('best_before', '>=', Carbon::today());

        // Filter by food category if provided
        if ($request->has('food_category') && $request->food_category != '') {
            $query->where('food_category', $request->food_category);
        }

        // Search by description
        if ($request->has('search') && $request->search != '') {
            $query->where('food_description', 'LIKE', '%' . $request->search . '%');
        }

        // Order by best_before date (most urgent first)
        $query->orderBy('best_before', 'asc');

        // Paginate results
        $donations = $query->paginate(10);

        // UPDATED: Food categories for filtering with new emoji categories
        $foodCategories = [
            'fruits_vegetables' => '🥕 Fruits & Vegetables',
            'bread_rice' => '🍞 Bread, Rice & Grains',
            'cooked_food' => '🍲 Cooked Food & Meals',
            'canned_bottled' => '🥫 Canned & Bottled Items',
            'milk_eggs' => '🥛 Milk, Eggs & Dairy',
            'other' => '📦 Other Food Items'
        ];

        return view('recipient.browse-donations', compact('donations', 'foodCategories'));
    }

    /**
     * Show reservation details
     */
    public function showReservationDetails(Reservation $reservation)
    {
        // Ensure the user can only view their own reservations
        $this->authorize('view', $reservation);

        return view('recipient.reservation-details', compact('reservation'));
    }

    /**
     * Cancel a reservation
     */
    public function cancelReservation(Reservation $reservation)
    {
        // Ensure the user can only cancel their own reservations
        $this->authorize('cancel', $reservation);

        // Update donation status back to available
        $donation = $reservation->donation;
        $donation->status = 'available';
        $donation->save();

        // Delete the reservation
        $reservation->delete();

        return redirect()->route('recipient.reservations')
            ->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * List all reservations
     */
    public function listReservations(Request $request)
    {
        $query = Auth::user()->reservations()->with('donation.donor');

        // Status filtering
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Date range filtering
        if ($request->start_date) {
            $query->whereDate('pickup_date', '>=', $request->start_date);
        }

        // Paginate results
        $reservations = $query->latest()->paginate(10);

        return view('recipient.reservations', compact('reservations'));
    }

    /**
     * Get donations expiring soon (for dashboard alerts)
     */
    public function getExpiringSoonDonations()
    {
        $expiringSoon = Donation::where('status', 'available')
            ->whereBetween('best_before', [
                Carbon::today(),
                Carbon::today()->addDays(2)
            ])
            ->orderBy('best_before', 'asc')
            ->limit(5)
            ->get();

        return response()->json($expiringSoon);
    }

    /**
     * Clean up expired donations (could be called via cron job)
     */
    public function cleanupExpiredDonations()
    {
        $expiredCount = Donation::where('status', 'available')
            ->where('best_before', '<', Carbon::today())
            ->update(['status' => 'expired']);

        return response()->json([
            'message' => "Cleaned up {$expiredCount} expired donations"
        ]);
    }
}
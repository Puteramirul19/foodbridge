<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        // Base query for available donations
        $query = Donation::where('status', 'available');

        // Filter by food category if provided
        if ($request->has('food_category') && $request->food_category != '') {
            $query->where('food_category', $request->food_category);
        }

        // Filter by proximity (mock implementation, would need geolocation in real app)
        if ($request->has('max_distance')) {
            // TODO: Implement actual geolocation filtering
        }

        // Search by description
        if ($request->has('search') && $request->search != '') {
            $query->where('food_description', 'LIKE', '%' . $request->search . '%');
        }

        // Paginate results
        $donations = $query->paginate(10);

        // Food categories for filtering
        $foodCategories = [
            'produce' => 'Fresh Produce',
            'bakery' => 'Bakery Items',
            'prepared_meals' => 'Prepared Meals',
            'packaged_goods' => 'Packaged Goods',
            'dairy' => 'Dairy Products',
            'other' => 'Other'
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
}
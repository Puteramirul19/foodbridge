<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Donation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with enhanced platform statistics
     * UPDATED: Added more donor and recipient activity statistics
     */
    public function dashboard()
    {
        // Count total users by role
        $totalDonors = User::where('role', 'donor')->count();
        $totalRecipients = User::where('role', 'recipient')->count();

        // Count total donations (include all for total count)
        $totalDonations = Donation::count();

        // Count active donations (exclude expired)
        $activeDonations = Donation::whereNotIn('status', ['expired'])->count();

        // Get donations for the last 6 months (EXCLUDE EXPIRED)
        $monthlyDonations = Donation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereRaw('created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')
            ->whereNotIn('status', ['expired']) // EXCLUDE EXPIRED
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->count;
            });

        // Prepare data for chart (6 months)
        $donationTrends = [];
        for ($i = 1; $i <= 6; $i++) {
            $donationTrends[] = $monthlyDonations->get($i, 0);
        }

        // Enhanced statistics for better insights
        $donationStats = [
            'total' => $totalDonations,
            'active' => $activeDonations,
            'available' => Donation::where('status', 'available')->count(),
            'reserved' => Donation::where('status', 'reserved')->count(),
            'completed' => Donation::where('status', 'completed')->count(),
            'expired' => Donation::where('status', 'expired')->count(),
            'totalServings' => Donation::sum('estimated_servings'),
            'activeServings' => Donation::whereNotIn('status', ['expired'])->sum('estimated_servings')
        ];

        // NEW: Enhanced donor activity statistics
        $donorStats = [
            'activeDonors' => User::where('role', 'donor')->where('is_active', true)->count(),
            'inactiveDonors' => User::where('role', 'donor')->where('is_active', false)->count(),
            'donorsWithDonations' => User::where('role', 'donor')->whereHas('donations')->count(),
            'topDonors' => User::where('role', 'donor')
                ->withCount('donations')
                ->orderBy('donations_count', 'desc')
                ->limit(5)
                ->get(),
            'avgDonationsPerDonor' => round(Donation::count() / max($totalDonors, 1), 2),
            'totalServingsProvided' => Donation::sum('estimated_servings'),
            'completedServings' => Donation::where('status', 'completed')->sum('estimated_servings')
        ];

        // NEW: Enhanced recipient activity statistics
        $recipientStats = [
            'activeRecipients' => User::where('role', 'recipient')->where('is_active', true)->count(),
            'inactiveRecipients' => User::where('role', 'recipient')->where('is_active', false)->count(),
            'recipientsWithReservations' => User::where('role', 'recipient')->whereHas('reservations')->count(),
            'totalReservations' => Reservation::count(),
            'completedReservations' => Reservation::whereHas('donation', function($query) {
                $query->where('status', 'completed');
            })->count(),
            'topRecipients' => User::where('role', 'recipient')
                ->withCount('reservations')
                ->orderBy('reservations_count', 'desc')
                ->limit(5)
                ->get(),
            'avgReservationsPerRecipient' => round(Reservation::count() / max($totalRecipients, 1), 2),
            'totalServingsReceived' => Reservation::whereHas('donation', function($query) {
                $query->where('status', 'completed');
            })->with('donation')->get()->sum('donation.estimated_servings')
        ];

        // Recent activity summary
        $recentActivity = [
            'recentDonations' => Donation::with('donor')->latest()->limit(5)->get(),
            'recentReservations' => Reservation::with(['recipient', 'donation.donor'])->latest()->limit(5)->get(),
            'todayDonations' => Donation::whereDate('created_at', today())->count(),
            'todayReservations' => Reservation::whereDate('created_at', today())->count()
        ];

        return view('admin.dashboard', [
            'totalDonors' => $totalDonors,
            'totalRecipients' => $totalRecipients,
            'totalDonations' => $activeDonations, // Show active donations in main dashboard
            'donationTrends' => $donationTrends,
            'donationStats' => $donationStats,
            'donorStats' => $donorStats, // NEW: Enhanced donor statistics
            'recipientStats' => $recipientStats, // NEW: Enhanced recipient statistics
            'recentActivity' => $recentActivity // NEW: Recent activity data
        ]);
    }

    /**
     * Manage users page
     */
    public function manageUsers()
    {
        $users = User::all();
        return view('admin.manage-users', compact('users'));
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user)
    {
        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account.');
        }

        // Toggle user's active status
        $previousStatus = $user->is_active;
        $user->is_active = !$user->is_active;
        
        try {
            $user->save();

            // Log the status change with more details
            Log::info('User status updated', [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
                'previous_status' => $previousStatus ? 'Active' : 'Inactive',
                'new_status' => $user->is_active ? 'Active' : 'Inactive',
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
                'timestamp' => now()
            ]);

            // Provide specific success message based on action
            $action = $user->is_active ? 'activated' : 'deactivated';
            $message = "User account has been {$action} successfully.";
            
            if (!$user->is_active) {
                $message .= " The user will be logged out on their next request.";
            }

            return redirect()->route('admin.users.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            // Log any errors during status update
            Log::error('User status update failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'admin_id' => Auth::id(),
                'timestamp' => now()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update user status. Please try again.');
        }
    }

    /**
     * Show report generation form
     */
    public function showReportForm()
    {
        return view('admin.generate-reports');
    }

    /**
     * Generate reports (PDF ONLY - CSV removed)
     * UPDATED: Removed CSV option, only PDF format. REMOVED DONATIONS REPORT TYPE.
     */
    public function generateReports(Request $request)
    {
        // Extended validation - removed CSV format option and donations report type
        $validator = Validator::make($request->all(), [
            'report_type' => [
                'required', 
                Rule::in(['users', 'donors', 'recipients']) // REMOVED 'donations'
            ],
            'format' => [
                'required', 
                Rule::in(['pdf']) // UPDATED: Only PDF allowed
            ],
            'start_date' => 'nullable|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
            'include_expired' => 'nullable|boolean'
        ]);

        // Custom error messages
        $validator->setCustomMessages([
            'report_type.in' => 'Invalid report type selected.',
            'format.in' => 'Only PDF format is supported.',
            'start_date.before_or_equal' => 'Start date cannot be in the future.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'end_date.before_or_equal' => 'End date cannot be in the future.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get data based on report type with date filtering
            switch($request->report_type) {
                case 'users':
                    $query = User::query();
                    break;
                case 'donors':
                    $query = User::where('role', 'donor')->with('donations');
                    break;
                case 'recipients':
                    $query = User::where('role', 'recipient')->with('reservations.donation');
                    break;
            }

            // Apply date filters if provided
            if ($request->start_date) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->end_date) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            $data = $query->get();

            // Check if data exists
            if ($data->isEmpty()) {
                return redirect()->back()
                    ->with('warning', 'No data found for the selected criteria. Please adjust your filters and try again.');
            }

            // Log report generation
            Log::info('Report Generated', [
                'type' => $request->report_type,
                'format' => $request->format,
                'records_count' => $data->count(),
                'include_expired' => $request->include_expired,
                'user' => Auth::user()->name
            ]);

            // Generate PDF report only
            return $this->generatePDFReport($data, $request->report_type);

        } catch (\Exception $e) {
            // Log any unexpected errors
            Log::error('Report Generation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['_token'])
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred while generating the report. Please try again.');
        }
    }

    /**
     * Generate PDF Report
     */
    private function generatePDFReport($data, $type)
    {
        $filename = $type . '_report_' . now()->format('YmdHis') . '.pdf';
        
        $pdf = PDF::loadView('admin.reports.pdf', [
            'data' => $data,
            'type' => $type,
            'title' => 'FoodBridge ' . ucfirst($type) . ' Report'
        ]);

        return $pdf->download($filename);
    }
}
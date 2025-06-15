<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Donation;
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
* Show the admin dashboard with platform statistics
* UPDATED: Exclude expired donations from main statistics
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

    // Additional statistics for better insights
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

    return view('admin.dashboard', [
        'totalDonors' => $totalDonors,
        'totalRecipients' => $totalRecipients,
        'totalDonations' => $activeDonations, // Show active donations in main dashboard
        'donationTrends' => $donationTrends,
        'donationStats' => $donationStats // Pass detailed stats for advanced view
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
 * Generate reports (CSV or PDF)
 * UPDATED: Handle expired donations appropriately in reports
 */
public function generateReports(Request $request)
{
    // Extended validation with more specific rules
    $validator = Validator::make($request->all(), [
        'report_type' => [
            'required', 
            Rule::in(['users', 'donations', 'donors', 'recipients'])
        ],
        'format' => [
            'required', 
            Rule::in(['csv', 'pdf'])
        ],
        'start_date' => 'nullable|date|before_or_equal:today',
        'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
        'include_expired' => 'nullable|boolean' // New option to include/exclude expired
    ]);

    // Custom error messages
    $validator->setCustomMessages([
        'report_type.in' => 'Invalid report type selected.',
        'format.in' => 'Invalid export format selected.',
        'start_date.before_or_equal' => 'Start date cannot be in the future.',
        'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        'end_date.before_or_equal' => 'End date cannot be in the future.'
    ]);

    // Check validation
    if ($validator->fails()) {
        // Log validation errors
        Log::warning('Report Generation Validation Failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $request->except(['_token'])
        ]);

        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        // Build query based on report type with eager loading
        $query = match($request->report_type) {
            'users' => User::query(),
            'donations' => Donation::with('donor')
                // OPTION: Include or exclude expired donations
                ->when(!$request->include_expired, function ($q) {
                    return $q->whereNotIn('status', ['expired']);
                })
                ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                    return $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }),
            'donors' => User::with(['donations' => function($q) use ($request) {
                    // For donor reports, optionally exclude expired donations
                    if (!$request->include_expired) {
                        $q->whereNotIn('status', ['expired']);
                    }
                }])
                ->where('role', 'donor')
                ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                    return $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }),
            'recipients' => User::with(['reservations.donation' => function($q) use ($request) {
                    // For recipient reports, optionally exclude expired donations
                    if (!$request->include_expired) {
                        $q->whereNotIn('status', ['expired']);
                    }
                }])
                ->where('role', 'recipient')
                ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                    return $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
                })
        };

        // Get filtered data
        $data = $query->get();

        // Check if data is empty
        if ($data->isEmpty()) {
            Log::info('No data found for report generation', [
                'report_type' => $request->report_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'include_expired' => $request->include_expired
            ]);

            return redirect()->back()
                ->with('warning', 'No data found for the selected criteria.');
        }

        // Log report generation
        Log::info('Report Generated', [
            'type' => $request->report_type,
            'format' => $request->format,
            'records_count' => $data->count(),
            'include_expired' => $request->include_expired,
            'user' => Auth::user()->name
        ]);

        // Generate report based on format
        return $request->format === 'csv' 
            ? $this->generateCSVReport($data, $request->report_type)
            : $this->generatePDFReport($data, $request->report_type);

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
     * Generate CSV Report
     */
    private function generateCSVReport($data, $type)
    {
        // Determine CSV columns and data based on report type
        $csvRows = [];
        
        switch($type) {
            case 'users':
                $csvRows[] = ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Registration Date'];
                foreach ($data as $user) {
                    $csvRows[] = [
                        $user->id, 
                        $user->name, 
                        $user->email,
                        $user->phone_number ?? 'Not provided',
                        $user->role,
                        $user->is_active ? 'Active' : 'Inactive',
                        $user->created_at->format('Y-m-d H:i:s')
                    ];
                }
                break;
            
            case 'donations':
                $csvRows[] = ['ID', 'Donor', 'Food Category', 'Servings', 'Status', 'Date'];
                foreach ($data as $donation) {
                    $csvRows[] = [
                        $donation->id,
                        $donation->donor->name,
                        \App\Http\Controllers\DonationController::getFormattedFoodCategory($donation->food_category),
                        $donation->estimated_servings,
                        $donation->status,
                        $donation->created_at->format('Y-m-d H:i:s')
                    ];
                }
                break;
            
            case 'donors':
                $csvRows[] = ['ID', 'Name', 'Email', 'Phone', 'Status', 'Total Donations', 'Total Servings'];
                foreach ($data as $donor) {
                    $csvRows[] = [
                        $donor->id,
                        $donor->name,
                        $donor->email,
                        $donor->phone_number ?? 'Not provided',
                        $donor->is_active ? 'Active' : 'Inactive',
                        $donor->donations->count(),
                        $donor->donations->sum('estimated_servings')
                    ];
                }
                break;
            
            case 'recipients':
                $csvRows[] = ['ID', 'Name', 'Email', 'Phone', 'Status', 'Total Reservations', 'Total Servings Received'];
                foreach ($data as $recipient) {
                    $csvRows[] = [
                        $recipient->id,
                        $recipient->name,
                        $recipient->email,
                        $recipient->phone_number ?? 'Not provided',
                        $recipient->is_active ? 'Active' : 'Inactive',
                        $recipient->reservations->count(),
                        $recipient->reservations->sum('donation.estimated_servings')
                    ];
                }
                break;
        }

        // Generate CSV file
        $filename = $type . '_report_' . now()->format('YmdHis') . '.csv';
        $handle = fopen($filename, 'w');
        
        foreach ($csvRows as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend();
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
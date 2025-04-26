<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDonors = User::where('role', 'donor')->count();
        $totalRecipients = User::where('role', 'recipient')->count();
        $totalDonations = Donation::count(); // Updated to use actual donation count

        return view('admin.dashboard', [
            'totalDonors' => $totalDonors,
            'totalRecipients' => $totalRecipients,
            'totalDonations' => $totalDonations
        ]);
    }

    public function manageUsers()
    {
        $users = User::all();
        return view('admin.manage-users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        // Toggle user's active status
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User status updated successfully');
    }
    public function generateReports(Request $request)
    {
        // Validate input
        $request->validate([
            'report_type' => 'required|in:users,donations,donors,recipients',
            'format' => 'required|in:csv,pdf',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        // Build query based on report type
        $query = match($request->report_type) {
            'users' => User::query(),
            'donations' => Donation::query(),
            'donors' => User::where('role', 'donor'),
            'recipients' => User::where('role', 'recipient')
        };

        // Apply date filtering if dates are provided
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Get data
        $data = $query->get();

        // Generate report based on format
        if ($request->format === 'csv') {
            return $this->generateCSVReport($data, $request->report_type);
        } else {
            return $this->generatePDFReport($data, $request->report_type);
        }
    }

    private function generateCSVReport($data, $type)
    {
        // Determine CSV columns based on report type
        $columns = match($type) {
            'users' => ['ID', 'Name', 'Email', 'Role', 'Created At'],
            'donations' => ['ID', 'Donor', 'Type', 'Quantity', 'Status', 'Created At'],
            'donors' => ['ID', 'Name', 'Email', 'Total Donations', 'Created At'],
            'recipients' => ['ID', 'Name', 'Email', 'Total Reservations', 'Created At']
        };

        // Prepare CSV content
        $csvContent = implode(',', $columns) . "\n";
        
        foreach ($data as $item) {
            $row = [];
            foreach ($columns as $column) {
                $row[] = '"' . str_replace('"', '""', $this->getColumnValue($item, $column)) . '"';
            }
            $csvContent .= implode(',', $row) . "\n";
        }

        // Generate CSV file
        $filename = $type . '_report_' . now()->format('YmdHis') . '.csv';
        
        return Response::stream(
            function() use ($csvContent) { echo $csvContent; }, 
            200, 
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]
        );
    }

    private function getColumnValue($item, $column)
    {
        return match($column) {
            'ID' => $item->id,
            'Name' => $item->name,
            'Email' => $item->email,
            'Role' => $item->role,
            'Created At' => $item->created_at->format('Y-m-d H:i:s'),
            default => ''
        };
    }

    private function generatePDFReport($data, $type)
    {
        $filename = $type . '_report_' . now()->format('YmdHis') . '.pdf';
        
        $pdf = PDF::loadView('admin.reports.pdf', [
            'data' => $data,
            'type' => $type,
            'title' => ucfirst($type) . ' Report'
        ]);

        return $pdf->download($filename);
    }

    public function showReportForm()
    {
        return view('admin.generate-reports');
    }

}
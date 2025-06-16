<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }} - FoodBridge Report</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            line-height: 1.6; 
            margin: 0; 
            padding: 20px; 
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2575fc;
        }
        .header h1 {
            color: #2575fc;
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .header .generation-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            font-size: 12px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-size: 12px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
            vertical-align: top;
        }
        th { 
            background-color: #f8f9fa; 
            font-weight: bold; 
            color: #2575fc;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 30px;
            background-color: #f0f4ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #2575fc;
        }
        .summary h3 {
            color: #2575fc;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #ddd;
        }
        .summary-item:last-child {
            border-right: none;
        }
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #2575fc;
        }
        .summary-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-available { background-color: #d4edda; color: #155724; }
        .status-reserved { background-color: #fff3cd; color: #856404; }
        .status-completed { background-color: #d1ecf1; color: #0c5460; }
        .status-expired { background-color: #f8d7da; color: #721c24; }
        .status-active { background-color: #d4edda; color: #155724; }
        .status-inactive { background-color: #f8d7da; color: #721c24; }
        .section-title {
            color: #2575fc;
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e9ecef;
        }
        .food-category {
            font-size: 11px;
            background-color: #e7f3ff;
            padding: 2px 6px;
            border-radius: 4px;
            color: #2575fc;
        }
        .page-break {
            page-break-before: always;
        }
        .insights-section {
            margin-top: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        .insights-section h4 {
            color: #28a745;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .insights-list {
            margin: 0;
            padding-left: 20px;
        }
        .insights-list li {
            margin-bottom: 5px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">FoodBridge Platform Analytics Report</div>
        <div class="generation-info">
            <strong>Generated:</strong> {{ now()->format('F d, Y \a\t g:i A') }} | 
            <strong>Total Records:</strong> {{ $data->count() }}
        </div>
    </div>

    @switch($type)
        @case('users')
            <div class="section-title">Platform Users Overview</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone_number ?? 'Not provided' }}</td>
                            <td>{{ ucfirst($item->role) }}</td>
                            <td>
                                <span class="status-badge status-{{ $item->is_active ? 'active' : 'inactive' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>User Statistics Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->count() }}</div>
                        <div class="summary-label">Total Users</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('role', 'donor')->count() }}</div>
                        <div class="summary-label">Donors</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('role', 'recipient')->count() }}</div>
                        <div class="summary-label">Recipients</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('is_active', true)->count() }}</div>
                        <div class="summary-label">Active Users</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->count() > 0 ? round(($data->where('is_active', true)->count() / $data->count()) * 100, 1) : 0 }}%</div>
                        <div class="summary-label">Active Rate</div>
                    </div>
                </div>
            </div>

            <div class="insights-section">
                <h4>Key Insights</h4>
                <ul class="insights-list">
                    <li>Total platform users: {{ $data->count() }} ({{ $data->where('role', 'donor')->count() }} donors, {{ $data->where('role', 'recipient')->count() }} recipients)</li>
                    <li>User activity rate: {{ $data->count() > 0 ? round(($data->where('is_active', true)->count() / $data->count()) * 100, 1) : 0 }}% of users are currently active</li>
                    <li>Most recent registration: {{ $data->sortByDesc('created_at')->first()->created_at->format('F d, Y') ?? 'No registrations' }}</li>
                    <li>Donor to recipient ratio: {{ $data->where('role', 'recipient')->count() > 0 ? round($data->where('role', 'donor')->count() / $data->where('role', 'recipient')->count(), 2) : 'N/A' }}:1</li>
                </ul>
            </div>
            @break

        @case('donations')
            <div class="section-title">Food Donations Analysis</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donor</th>
                        <th>Food Category</th>
                        <th>Servings</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Expires</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->donor->name }}</td>
                            <td>
                                <span class="food-category">
                                    {{ str_replace(['🥕', '🍞', '🍲', '🥫', '🥛', '📦'], '', \App\Http\Controllers\DonationController::getFormattedFoodCategory($item->food_category)) }}
                                </span>
                            </td>
                            <td>{{ number_format($item->estimated_servings) }}</td>
                            <td>
                                <span class="status-badge status-{{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                            <td>{{ $item->expires_at ? $item->expires_at->format('M d, Y') : 'No expiry' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>Donation Statistics Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->count() }}</div>
                        <div class="summary-label">Total Donations</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('status', 'available')->count() }}</div>
                        <div class="summary-label">Available</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('status', 'reserved')->count() }}</div>
                        <div class="summary-label">Reserved</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('status', 'completed')->count() }}</div>
                        <div class="summary-label">Completed</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ number_format($data->sum('estimated_servings')) }}</div>
                        <div class="summary-label">Total Servings</div>
                    </div>
                </div>
            </div>

            <div class="insights-section">
                <h4>Key Insights</h4>
                <ul class="insights-list">
                    <li>Total food donations: {{ $data->count() }} donations providing {{ number_format($data->sum('estimated_servings')) }} servings</li>
                    <li>Completion rate: {{ $data->count() > 0 ? round(($data->where('status', 'completed')->count() / $data->count()) * 100, 1) : 0 }}%</li>
                    <li>Average servings per donation: {{ $data->count() > 0 ? round($data->sum('estimated_servings') / $data->count(), 1) : 0 }}</li>
                    <li>Most popular food category: {{ $data->groupBy('food_category')->map->count()->sortDesc()->keys()->first() ?? 'None' }}</li>
                    @if($data->where('status', 'expired')->count() > 0)
                    <li>Expired donations: {{ $data->where('status', 'expired')->count() }} ({{ round(($data->where('status', 'expired')->count() / $data->count()) * 100, 1) }}%)</li>
                    @endif
                </ul>
            </div>
            @break

        @case('donors')
            <div class="section-title">Donor Contribution Analysis</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Total Donations</th>
                        <th>Total Servings</th>
                        <th>Avg Servings/Donation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone_number ?? 'Not provided' }}</td>
                            <td>
                                <span class="status-badge status-{{ $item->is_active ? 'active' : 'inactive' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $item->donations->count() }}</td>
                            <td>{{ number_format($item->donations->sum('estimated_servings')) }}</td>
                            <td>{{ $item->donations->count() > 0 ? round($item->donations->sum('estimated_servings') / $item->donations->count(), 1) : 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>Donor Performance Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->count() }}</div>
                        <div class="summary-label">Total Donors</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('is_active', true)->count() }}</div>
                        <div class="summary-label">Active Donors</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->sum(function($donor) { return $donor->donations->count(); }) }}</div>
                        <div class="summary-label">Total Donations</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ number_format($data->sum(function($donor) { return $donor->donations->sum('estimated_servings'); })) }}</div>
                        <div class="summary-label">Total Servings</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->count() > 0 ? round($data->sum(function($donor) { return $donor->donations->count(); }) / $data->count(), 1) : 0 }}</div>
                        <div class="summary-label">Avg Donations/Donor</div>
                    </div>
                </div>
            </div>

            <div class="insights-section">
                <h4>Key Insights</h4>
                @php
                    $activeDonors = $data->filter(function($donor) { return $donor->donations->count() > 0; });
                    $topDonor = $data->sortByDesc(function($donor) { return $donor->donations->count(); })->first();
                    $topServingsDonor = $data->sortByDesc(function($donor) { return $donor->donations->sum('estimated_servings'); })->first();
                @endphp
                <ul class="insights-list">
                    <li>Active donor rate: {{ $data->count() > 0 ? round(($activeDonors->count() / $data->count()) * 100, 1) : 0 }}% of donors have made donations</li>
                    <li>Most active donor: {{ $topDonor->name ?? 'None' }} ({{ $topDonor->donations->count() ?? 0 }} donations)</li>
                    <li>Highest servings contributor: {{ $topServingsDonor->name ?? 'None' }} ({{ number_format($topServingsDonor->donations->sum('estimated_servings') ?? 0) }} servings)</li>
                    <li>Average donations per active donor: {{ $activeDonors->count() > 0 ? round($activeDonors->sum(function($donor) { return $donor->donations->count(); }) / $activeDonors->count(), 1) : 0 }}</li>
                </ul>
            </div>
            @break

        @case('recipients')
            <div class="section-title">Food Recipients Activity Report</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Total Reservations</th>
                        <th>Completed Reservations</th>
                        <th>Servings Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone_number ?? 'Not provided' }}</td>
                            <td>
                                <span class="status-badge status-{{ $item->is_active ? 'active' : 'inactive' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $item->reservations->count() }}</td>
                            <td>{{ $item->reservations->filter(function($reservation) { return $reservation->donation && $reservation->donation->status == 'completed'; })->count() }}</td>
                            <td>{{ number_format($item->reservations->filter(function($reservation) { return $reservation->donation && $reservation->donation->status == 'completed'; })->sum(function($reservation) { return $reservation->donation->estimated_servings; })) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>Recipient Engagement Summary</h3>
                @php
                    $totalReservations = $data->sum(function($recipient) { return $recipient->reservations->count(); });
                    $completedReservations = $data->sum(function($recipient) { 
                        return $recipient->reservations->filter(function($reservation) { 
                            return $reservation->donation && $reservation->donation->status == 'completed'; 
                        })->count(); 
                    });
                    $totalServingsReceived = $data->sum(function($recipient) { 
                        return $recipient->reservations->filter(function($reservation) { 
                            return $reservation->donation && $reservation->donation->status == 'completed'; 
                        })->sum(function($reservation) { 
                            return $reservation->donation->estimated_servings; 
                        }); 
                    });
                @endphp
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->count() }}</div>
                        <div class="summary-label">Total Recipients</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->where('is_active', true)->count() }}</div>
                        <div class="summary-label">Active Recipients</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $totalReservations }}</div>
                        <div class="summary-label">Total Reservations</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $completedReservations }}</div>
                        <div class="summary-label">Completed</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $data->count() > 0 ? round($totalReservations / $data->count(), 1) : 0 }}</div>
                        <div class="summary-label">Avg Reservations</div>
                    </div>
                </div>
            </div>

            <div class="insights-section">
                <h4>Key Insights</h4>
                @php
                    $activeRecipients = $data->filter(function($recipient) { return $recipient->reservations->count() > 0; });
                    $topRecipient = $data->sortByDesc(function($recipient) { return $recipient->reservations->count(); })->first();
                    $completionRate = $totalReservations > 0 ? round(($completedReservations / $totalReservations) * 100, 1) : 0;
                @endphp
                <ul class="insights-list">
                    <li>Active recipient rate: {{ $data->count() > 0 ? round(($activeRecipients->count() / $data->count()) * 100, 1) : 0 }}% of recipients have made reservations</li>
                    <li>Reservation completion rate: {{ $completionRate }}%</li>
                    <li>Most active recipient: {{ $topRecipient->name ?? 'None' }} ({{ $topRecipient->reservations->count() ?? 0 }} reservations)</li>
                    <li>Total servings received: {{ number_format($totalServingsReceived) }} servings across all completed reservations</li>
                    <li>Average servings per completed reservation: {{ $completedReservations > 0 ? round($totalServingsReceived / $completedReservations, 1) : 0 }}</li>
                </ul>
            </div>
            @break
    @endswitch

    <div class="footer">
        <p>
            <strong>FoodBridge Platform Report</strong> | 
            Generated on {{ now()->format('F d, Y \a\t g:i A') }} | 
        </p>
    </div>
</body>
</html>
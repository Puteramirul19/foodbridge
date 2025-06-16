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
                </div>
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
                        <th>Total Food Requests</th>
                        <th>Completed Requests</th>
                        <th>Food Received</th>
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
                            <td>{{ number_format($item->reservations->filter(function($reservation) { return $reservation->donation && $reservation->donation->status == 'completed'; })->sum(function($reservation) { return $reservation->donation->estimated_servings; })) }} servings</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>Food Request Summary</h3>
                @php
                    $totalRequests = $data->sum(function($recipient) { return $recipient->reservations->count(); });
                    $completedRequests = $data->sum(function($recipient) { 
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
                        <div class="summary-number">{{ $totalRequests }}</div>
                        <div class="summary-label">Total Food Requests</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $completedRequests }}</div>
                        <div class="summary-label">Completed</div>
                    </div>
                </div>
            </div>
            @break
    @endswitch

    <div class="footer">
        <p>
            © 2025 FoodBridge. All rights reserved. | Building bridges between surplus and need.
        </p>
    </div>
</body>
</html>
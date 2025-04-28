<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }} - FoodBridge Report</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            margin: 0; 
            padding: 20px; 
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2575fc;
        }
        .header h1 {
            color: #2575fc;
            margin: 0;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 10px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        .summary {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('foodbridge-icon.svg') }}" alt="FoodBridge Logo" class="logo">
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    @switch($type)
        @case('users')
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ ucfirst($item->role) }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>User Summary</h3>
                <p>Total Users: {{ $data->count() }}</p>
                <p>Donors: {{ $data->where('role', 'donor')->count() }}</p>
                <p>Recipients: {{ $data->where('role', 'recipient')->count() }}</p>
            </div>
            @break

        @case('donations')
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donor</th>
                        <th>Food Category</th>
                        <th>Servings</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->donor->name }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $item->food_category)) }}</td>
                            <td>{{ $item->estimated_servings }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>Donation Summary</h3>
                <p>Total Donations: {{ $data->count() }}</p>
                <p>Available Donations: {{ $data->where('status', 'available')->count() }}</p>
                <p>Reserved Donations: {{ $data->where('status', 'reserved')->count() }}</p>
                <p>Total Estimated Servings: {{ $data->sum('estimated_servings') }}</p>
            </div>
            @break

        @case('donors')
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
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
                            <td>{{ $item->donations->count() }}</td>
                            <td>{{ $item->donations->sum('estimated_servings') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>Donors Summary</h3>
                <p>Total Donors: {{ $data->count() }}</p>
                <p>Total Donations Made: {{ $data->sum(function($donor) { return $donor->donations->count(); }) }}</p>
                <p>Total Food Servings: {{ $data->sum(function($donor) { return $donor->donations->sum('estimated_servings'); }) }}</p>
            </div>
            @break

        @case('recipients')
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total Reservations</th>
                        <th>Total Servings Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->reservations->count() }}</td>
                            <td>{{ $item->reservations->sum('donation.estimated_servings') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                <h3>Recipients Summary</h3>
                <p>Total Recipients: {{ $data->count() }}</p>
                <p>Total Reservations: {{ $data->sum(function($recipient) { return $recipient->reservations->count(); }) }}</p>
                <p>Total Food Servings Received: {{ $data->sum(function($recipient) { return $recipient->reservations->sum('donation.estimated_servings'); }) }}</p>
            </div>
            @break
    @endswitch

    <div class="footer">
        <p>© {{ now()->year }} FoodBridge - Connecting Food Donors and Recipients</p>
        <p>Report generated for internal use only</p>
    </div>
</body>
</html>
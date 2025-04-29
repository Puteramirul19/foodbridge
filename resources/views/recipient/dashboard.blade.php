<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Dashboard - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    {{-- Chart.js for visualizations --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }
        .stat-card h3 {
            color: #2575fc;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .stat-card p {
            color: #4A5568;
            font-weight: bold;
            margin-bottom: 0;
        }
        .stat-card i {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #2575fc;
            opacity: 0.5;
            font-size: 2rem;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .action-buttons .btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .action-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .chart-container {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .recent-reservations {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            padding: 20px;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('recipient.donations.browse') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-utensils me-2"></i>Browse Donations
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid dashboard-container">
        {{-- Welcome Section --}}
        <div class="dashboard-header">
            <div>
                <h1 class="mb-2">Recipient Dashboard</h1>
                <p class="mb-0">Track and manage your food donations</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark p-2">
                    <i class="fas fa-clock me-2"></i>
                    {{ now()->format('D, M d Y') }}
                </span>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-shopping-basket"></i>
                <h3>{{ $stats['totalReservations'] }}</h3>
                <p>Total Donations</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-spinner"></i>
                <h3>{{ $stats['activeReservations'] }}</h3>
                <p>Pending Pickups</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>{{ $stats['completedReservations'] }}</h3>
                <p>Collected Donations</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-buttons">
            <a href="{{ route('recipient.donations.browse') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Browse Donations
            </a>
            <a href="{{ route('recipient.reservations') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list-alt me-2"></i>My Reservations
            </a>
        </div>

        {{-- Recent Reservations --}}
        <div class="recent-reservations">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Recent Reservations</h3>
                <a href="{{ route('recipient.reservations') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>

            @if($reservations->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                    <h3>No Donations Yet</h3>
                    <p>Start by browsing available donations in your area.</p>
                    <a href="{{ route('recipient.donations.browse') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Browse Donations
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Food Description</th>
                                <th>Donor</th>
                                <th>Pickup Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td>
                                        {{ Str::limit($reservation->donation->food_description, 30) }}
                                        <small class="d-block text-muted">
                                            {{ ucfirst(str_replace('_', ' ', $reservation->donation->food_category)) }}
                                        </small>
                                    </td>
                                    <td>{{ $reservation->donation->donor->name }}</td>
                                    <td>
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ $reservation->pickup_date->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span class="badge 
                                        {{ $reservation->status == 'pending' ? 'bg-warning' : 
                                           ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('recipient.reservations.details', $reservation) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Reservation Trends --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h3 class="mb-3">Reservation Status</h3>
                    <canvas id="reservationStatusChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h3 class="mb-3">Monthly Reservations</h3>
                    <canvas id="monthlyReservationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Charts Script --}}
    <script>
        // Reservation Status Chart
        const statusCtx = document.getElementById('reservationStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Completed', 'Other'],
                datasets: [{
                    data: [
                        {{ $stats['activeReservations'] }},
                        {{ $stats['completedReservations'] }},
                        {{ $stats['totalReservations'] - ($stats['activeReservations'] + $stats['completedReservations']) }}
                    ],
                    backgroundColor: [
                        '#FFC107', // Pending (Yellow)
                        '#28A745', // Completed (Green)
                        '#6C757D'  // Other (Gray)
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Reservations Chart
        const monthlyCtx = document.getElementById('monthlyReservationsChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($monthNames as $monthName)
                        '{{ $monthName }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Reservations',
                    data: [
                        @foreach($reservationTrends as $count)
                            {{ $count }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(37, 117, 252, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Reservations'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Reservation Trends'
                    }
                }
            }
        });
    </script>
</body>
</html>
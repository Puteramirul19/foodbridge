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
            background: linear-gradient(135deg, #FAF0E6 0%, #F5E6D3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .dashboard-header .content {
            position: relative;
            z-index: 2;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card .stat-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .stat-card h3 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .stat-card p {
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0;
            font-size: 1.1rem;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-action-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }
        
        .btn-action-secondary:hover {
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
        }
        
        .section-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px 30px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            font-size: 1.4rem;
        }
        
        .section-body {
            padding: 30px;
        }
        
        .table-modern {
            border: none;
            margin: 0;
        }
        
        .table-modern thead th {
            border: none;
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            padding: 15px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-modern tbody td {
            border: none;
            padding: 20px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .badge-pending {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            color: #d63031;
        }
        
        .badge-completed {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            margin: 20px 0;
        }
        
        .empty-icon {
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .chart-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .btn-eye {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }
        
        .btn-eye:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 10px;
            }
            
            .dashboard-header {
                padding: 20px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('recipient.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('profile.show') }}" class="btn btn-outline-info me-2">
                    <i class="fas fa-user me-2"></i>Profile
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
            <div class="content d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h1 class="mb-3">Welcome, {{ Auth::user()->name }}!</h1>
                        <p class="mb-0 fs-5">Track your food requests and discover new opportunities to get nutritious food</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-heart fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <h3>{{ $stats['totalReservations'] }}</h3>
                <p>Total Food Requests</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>{{ $stats['activeReservations'] }}</h3>
                <p>Pending Pickups</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>{{ $stats['completedReservations'] }}</h3>
                <p>Collected Food</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-buttons">
            <a href="{{ route('recipient.donations.browse') }}" class="btn btn-action">
                <i class="fas fa-search me-2"></i>Browse Donations
            </a>
            <a href="{{ route('recipient.reservations') }}" class="btn btn-action btn-action-secondary">
                <i class="fas fa-list-alt me-2"></i>My Food Requests
            </a>
        </div>

        {{-- Recent Food Requests --}}
        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-history me-2"></i>Recent Food Requests
                </h3>
            </div>

            <div class="section-body">
                @if($reservations->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-box-open fa-5x empty-icon"></i>
                        <h3 class="mt-3 mb-2">No Food Requests Yet</h3>
                        <p class="text-muted fs-5 mb-4">Start by browsing available donations in your area and make your first food request.</p>
                        <a href="{{ route('recipient.donations.browse') }}" class="btn btn-action">
                            <i class="fas fa-search me-2"></i>Browse Donations
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern">
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
                                            <div>
                                                <strong>{{ Str::limit($reservation->donation->food_description, 40) }}</strong>
                                                <small class="d-block text-muted mt-1">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $reservation->donation->food_category)) }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                    {{ strtoupper(substr($reservation->donation->donor->name, 0, 2)) }}
                                                </div>
                                                {{ $reservation->donation->donor->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                {{ $reservation->pickup_date->format('d M Y') }}
                                                <small class="d-block text-muted mt-1">
                                                    <i class="fas fa-clock me-1"></i>{{ $reservation->formatted_pickup_time }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $reservation->status == 'pending' ? 'badge-pending' : 'badge-completed' }}">
                                                <i class="fas {{ $reservation->status == 'pending' ? 'fa-clock' : 'fa-check' }} me-1"></i>
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('recipient.reservations.details', $reservation) }}" 
                                               class="btn btn-eye btn-sm">
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
        </div>

        {{-- Charts Row --}}
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie me-2"></i>Request Status
                    </h3>
                    <canvas id="reservationStatusChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-line me-2"></i>Monthly Activity
                    </h3>
                    <canvas id="monthlyReservationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Charts Script --}}
    <script>
        // Modern color palette
        const colors = {
            primary: ['#667eea', '#764ba2'],
            success: ['#00b894', '#00cec9'],
            warning: ['#fdcb6e', '#e17055'],
            info: ['#74b9ff', '#0984e3'],
            gradient: function(ctx, color1, color2) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, color1);
                gradient.addColorStop(1, color2);
                return gradient;
            }
        };

        // Request Status Chart - Only show Pending and Completed
        const statusCtx = document.getElementById('reservationStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Completed'],
                datasets: [{
                    data: [
                        {{ $stats['activeReservations'] }},
                        {{ $stats['completedReservations'] }}
                    ],
                    backgroundColor: [
                        colors.gradient(statusCtx, '#fdcb6e', '#e17055'),
                        colors.gradient(statusCtx, '#00b894', '#00cec9')
                    ],
                    borderWidth: 0,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });

        // Monthly Food Requests Chart
        const monthlyCtx = document.getElementById('monthlyReservationsChart').getContext('2d');
        const gradient = monthlyCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient.addColorStop(1, 'rgba(118, 75, 162, 0.1)');

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($monthNames as $monthName)
                        '{{ $monthName }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Food Requests',
                    data: [
                        @foreach($reservationTrends as $count)
                            {{ $count }},
                        @endforeach
                    ],
                    borderColor: '#667eea',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            color: '#6c757d',
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d',
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
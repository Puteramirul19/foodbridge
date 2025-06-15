<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - FoodBridge</title>
    
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
        
        .btn-action-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }
        
        .btn-action-warning:hover {
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
        }
        
        .btn-action-pink {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }
        
        .btn-action-pink:hover {
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .pending-pickup-alert {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffc107;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);
        }
        
        .pickup-item {
            background: rgba(255,255,255,0.8);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #ffc107;
            transition: all 0.3s ease;
        }
        
        .pickup-item:hover {
            background: rgba(255,255,255,1);
            transform: translateX(5px);
        }
        
        .pickup-item.urgent {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, rgba(255,255,255,0.9) 100%);
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
        
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            margin: 20px 0;
        }
        
        .empty-icon {
            color: #00b894;
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
        
        .btn-view-all {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #2c3e50;
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-view-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            color: #2c3e50;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .btn-disabled {
            pointer-events: none;
            opacity: 0.5;
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
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
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
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Welcome Section --}}
        <div class="dashboard-header">
            <div class="content d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h1 class="mb-3">Welcome, {{ Auth::user()->name }}!</h1>
                        <p class="mb-0 fs-5">Thank you for helping reduce food waste and supporting your community</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-hands-helping fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Pickups Alert --}}
        @if($pendingPickups->count() > 0)
            <div class="pending-pickup-alert">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pending Pickups Require Attention</h5>
                        <p class="mb-3">You have {{ $pendingPickups->count() }} donation(s) awaiting pickup confirmation.</p>
                        
                        @foreach($pendingPickups->take(3) as $donation)
                            @php
                                $reservation = $donation->reservations->where('status', 'pending')->first();
                                $isUrgent = $donation->isExpiringSoon() || $reservation->pickup_date->isToday();
                            @endphp
                            <div class="pickup-item {{ $isUrgent ? 'urgent' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ Str::limit($donation->food_description, 50) }}</strong>
                                        <small class="d-block text-muted">
                                            Pickup: {{ $reservation->pickup_date->format('d M Y') }} at {{ $reservation->formatted_pickup_time }}
                                            by {{ $reservation->recipient->name }}
                                        </small>
                                    </div>
                                    @if($isUrgent)
                                        <span class="badge bg-danger">
                                            @if($donation->isExpired())
                                                Expired
                                            @elseif($reservation->pickup_date->isToday())
                                                Due Today
                                            @else
                                                Expires Soon
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if($pendingPickups->count() > 3)
                            <small class="text-muted">...and {{ $pendingPickups->count() - 3 }} more</small>
                        @endif
                    </div>
                    <div class="ms-3">
                        <a href="{{ route('donor.pending-pickups') }}" class="btn btn-warning">
                            <i class="fas fa-eye me-2"></i>Review All
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Donations</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>{{ $stats['completed'] }}</h3>
                <p>Completed Donations</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>{{ $stats['reserved'] }}</h3>
                <p>Reserved Donations</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bread-slice"></i>
                </div>
                <h3>{{ $stats['totalServings'] }}</h3>
                <p>Total Servings</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-buttons">
            <a href="{{ route('donor.donations.create') }}" class="btn btn-action">
                <i class="fas fa-plus me-2"></i>Create New Donation
            </a>
            @if($pendingPickups->count() > 0)
                <a href="{{ route('donor.pending-pickups') }}" class="btn btn-action btn-action-warning position-relative">
                    <i class="fas fa-clock me-2"></i>Pending Pickups
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $pendingPickups->count() }}
                    </span>
                </a>
            @endif
            <a href="{{ route('donor.donations.index') }}" class="btn btn-action btn-action-pink">
                <i class="fas fa-list me-2"></i>View All Donations
            </a>
        </div>

        {{-- Recent Donations Section (Separate Card) --}}
        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-history me-2"></i>Recent Donations
                </h3>
            </div>
            <div class="section-body">
                @if($recentDonations->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-box-open fa-5x empty-icon"></i>
                        <h3 class="mt-3 mb-2">No Donations Yet</h3>
                        <p class="text-muted fs-5 mb-4">Start making a difference by creating your first donation and helping your community.</p>
                        <a href="{{ route('donor.donations.create') }}" class="btn btn-action">
                            <i class="fas fa-plus me-2"></i>Create Your First Donation
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>Food Description</th>
                                    <th>Category</th>
                                    <th>Servings</th>
                                    <th>Best Before</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDonations as $donation)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ Str::limit($donation->food_description, 40) }}</strong>
                                                <small class="d-block text-muted mt-1">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    Created {{ $donation->created_at->format('d M Y') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: #667eea; color: white;">
                                                {!! \App\Http\Controllers\DonationController::getFormattedFoodCategory($donation->food_category) !!}
                                            </span>
                                        </td>
                                        <td>{{ $donation->estimated_servings }}</td>
                                        <td>
                                            <div>
                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                {{ \Carbon\Carbon::parse($donation->best_before)->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $donation->status == 'available' ? 'success' : 
                                                ($donation->status == 'reserved' ? 'warning' : 
                                                ($donation->status == 'completed' ? 'info' : 'danger'))
                                            }}">
                                                {{ ucfirst($donation->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- Edit Button --}}
                                                @if($donation->canBeEdited())
                                                    <a href="{{ route('donor.donations.edit', $donation) }}" 
                                                    class="btn btn-edit btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-edit btn-sm btn-disabled" 
                                                            disabled 
                                                            title="{{ $donation->isExpired() ? 'Cannot edit expired donation' : 'Cannot edit reserved/completed donation' }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Charts Row (Separate from Recent Donations) --}}
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie me-2"></i>Donations by Category
                    </h3>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-line me-2"></i>Monthly Donation Trend
                    </h3>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Charts Script --}}
    <script>
        // Food category formatter function - server-side data
        const foodCategoryLabels = {
            @foreach($donationsByCategory as $category => $data)
                '{{ $category }}': '{!! addslashes(\App\Http\Controllers\DonationController::getFormattedFoodCategory($category)) !!}',
            @endforeach
        };

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

        // Donations by Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($donationsByCategory as $category => $data)
                        '{!! addslashes(\App\Http\Controllers\DonationController::getFormattedFoodCategory($category)) !!}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($donationsByCategory as $category => $data)
                            {{ $data['count'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        colors.gradient(categoryCtx, '#FF6384', '#FF4569'),
                        colors.gradient(categoryCtx, '#36A2EB', '#258DDB'),
                        colors.gradient(categoryCtx, '#FFCE56', '#FFB142'),
                        colors.gradient(categoryCtx, '#4BC0C0', '#36A8A8'),
                        colors.gradient(categoryCtx, '#9966FF', '#7B52F0'),
                        colors.gradient(categoryCtx, '#FF9F40', '#FF8C2C')
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

        // Monthly Donations Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const gradient1 = monthlyCtx.createLinearGradient(0, 0, 0, 400);
        gradient1.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient1.addColorStop(1, 'rgba(118, 75, 162, 0.1)');

        const gradient2 = monthlyCtx.createLinearGradient(0, 0, 0, 400);
        gradient2.addColorStop(0, 'rgba(255, 99, 132, 0.8)');
        gradient2.addColorStop(1, 'rgba(255, 99, 132, 0.1)');

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($monthlyDonations as $month => $data)
                        '{{ $month }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Number of Donations',
                    data: [
                        @foreach($monthlyDonations as $month => $data)
                            {{ $data['count'] }},
                        @endforeach
                    ],
                    borderColor: '#667eea',
                    backgroundColor: gradient1,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }, {
                    label: 'Total Servings',
                    data: [
                        @foreach($monthlyDonations as $month => $data)
                            {{ $data['servings'] }},
                        @endforeach
                    ],
                    borderColor: '#ff6384',
                    backgroundColor: gradient2,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ff6384',
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
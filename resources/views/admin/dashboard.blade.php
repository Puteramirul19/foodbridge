<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    {{-- Chart.js --}}
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
        
        /* Action buttons centered */
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
            text-decoration: none;
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
        
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .activity-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        
        .activity-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .activity-item .value {
            font-size: 1.4rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 3px;
        }
        
        .activity-item .label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
        }
        
        .recent-activity-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            border-left: 3px solid #28a745;
            transition: all 0.3s ease;
        }
        
        .recent-activity-item:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .top-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }
        
        .top-item-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 12px;
            border-radius: 8px;
            border-left: 3px solid #667eea;
            transition: all 0.3s ease;
        }
        
        .top-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        /* Chart containers */
        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            height: 350px;
        }
        
        .chart-container h3 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .chart-wrapper {
            height: 250px;
            position: relative;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-size: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                margin: 15px auto;
                padding: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .charts-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="navbar-nav ms-auto">
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
                        <h1 class="mb-3">Admin Dashboard</h1>
                        <p class="mb-0 fs-5">Platform Overview and Management</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-cog fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Statistics Cards --}}
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>{{ $totalDonors }}</h3>
                <p>Total Donors</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>{{ $totalRecipients }}</h3>
                <p>Total Recipients</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-donate"></i>
                </div>
                <h3>{{ $totalDonations }}</h3>
                <p>Active Donations</p>
            </div>
        </div>

        {{-- Action Buttons (Centered) --}}
        <div class="action-buttons">
            <a href="{{ route('admin.users.index') }}" class="btn btn-action">
                <i class="fas fa-users-cog me-2"></i>Manage Users
            </a>
            <a href="{{ route('admin.show-reports') }}" class="btn btn-action btn-action-secondary">
                <i class="fas fa-file-alt me-2"></i>Generate Reports
            </a>
        </div>

        {{-- Donor Activity Section (Simplified) --}}
        <div class="section-card">
            <div class="section-header">
                <h4 class="section-title"><i class="fas fa-user-plus me-2"></i>Donor Activity Statistics</h4>
            </div>
            <div class="section-body">
                <div class="activity-grid">
                    <div class="activity-item">
                        <div class="value">{{ $donorStats['activeDonors'] }}</div>
                        <div class="label">Active Donors</div>
                    </div>
                    <div class="activity-item">
                        <div class="value">{{ $donorStats['inactiveDonors'] }}</div>
                        <div class="label">Inactive Donors</div>
                    </div>
                </div>
                
                {{-- Top 5 Donors --}}
                <div class="mt-4">
                    <h6 class="section-title" style="font-size: 1.1rem;">Top 5 Most Active Donors</h6>
                    <div class="top-items-grid">
                        @foreach($donorStats['topDonors'] as $donor)
                        <div class="top-item-card">
                            <strong>{{ $donor->name }}</strong><br>
                            <small class="text-muted">{{ $donor->donations_count }} donations</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Recipient Activity Section (Simplified) --}}
        <div class="section-card">
            <div class="section-header">
                <h4 class="section-title"><i class="fas fa-hand-holding-heart me-2"></i>Recipient Activity Statistics</h4>
            </div>
            <div class="section-body">
                <div class="activity-grid">
                    <div class="activity-item">
                        <div class="value">{{ $recipientStats['activeRecipients'] }}</div>
                        <div class="label">Active Recipients</div>
                    </div>
                    <div class="activity-item">
                        <div class="value">{{ $recipientStats['inactiveRecipients'] }}</div>
                        <div class="label">Inactive Recipients</div>
                    </div>
                </div>
                
                {{-- Top 5 Recipients --}}
                <div class="mt-4">
                    <h6 class="section-title" style="font-size: 1.1rem;">Top 5 Most Active Recipients</h6>
                    <div class="top-items-grid">
                        @foreach($recipientStats['topRecipients'] as $recipient)
                        <div class="top-item-card">
                            <strong>{{ $recipient->name }}</strong><br>
                            <small class="text-muted">{{ $recipient->reservations_count }} reservations</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activity Section (Simplified) --}}
        <div class="section-card">
            <div class="section-header">
                <h4 class="section-title"><i class="fas fa-clock me-2"></i>Recent Activity</h4>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="section-title" style="font-size: 1.1rem;">Recent Donations</h6>
                        @foreach($recentActivity['recentDonations'] as $donation)
                        <div class="recent-activity-item">
                            <strong>{{ $donation->donor->name }}</strong> donated 
                            <span class="text-primary">{{ $donation->estimated_servings }} servings</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-md-6">
                        <h6 class="section-title" style="font-size: 1.1rem;">Recent Reservations</h6>
                        @foreach($recentActivity['recentReservations'] as $reservation)
                        <div class="recent-activity-item">
                            <strong>{{ $reservation->recipient->name }}</strong> reserved food from 
                            <span class="text-success">{{ $reservation->donation->donor->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section (Resized) --}}
        <div class="charts-section">
            <div class="chart-container">
                <h3>User Distribution</h3>
                <div class="chart-wrapper">
                    <canvas id="userDistributionChart"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <h3>Donation Trends</h3>
                <div class="chart-wrapper">
                    <canvas id="donationTrendsChart"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <h3>Donation Status</h3>
                <div class="chart-wrapper">
                    <canvas id="donationStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Charts Script --}}
    <script>
        // User Distribution Chart
        const userCtx = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(userCtx, {
            type: 'doughnut',
            data: {
                labels: ['Donors', 'Recipients'],
                datasets: [{
                    data: [{{ $totalDonors }}, {{ $totalRecipients }}],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 14,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });

        // Donation Trends Chart
        const trendsCtx = document.getElementById('donationTrendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Donations',
                    data: @json(array_values($donationTrends)),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Donation Status Chart
        const statusCtx = document.getElementById('donationStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Available', 'Reserved', 'Completed', 'Expired'],
                datasets: [{
                    data: [
                        {{ $donationStats['available'] }},
                        {{ $donationStats['reserved'] }},
                        {{ $donationStats['completed'] }},
                        {{ $donationStats['expired'] }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#17a2b8',
                        '#dc3545'
                    ],
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FoodBridge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        .stat-card h3 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .stat-card p {
            margin: 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .activity-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .activity-header {
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .activity-header h4 {
            color: #2575fc;
            margin: 0;
        }
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .activity-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #2575fc;
        }
        .activity-item .value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2575fc;
        }
        .activity-item .label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .recent-activity-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 3px solid #28a745;
        }
        .recent-activity-item .time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }
        .btn-action {
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .section-title {
            color: #2575fc;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
    </style>
</head>
<body style="background-color: #f8f9fa;">
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 mb-0" style="color: #2575fc;">Admin Dashboard</h1>
                <p class="mb-0">Platform Overview and Management</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark p-2">
                    <i class="fas fa-clock me-2"></i>
                    {{ now()->format('D, M d Y') }}
                </span>
            </div>
        </div>

        {{-- Main Statistics Cards --}}
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>{{ $totalDonors }}</h3>
                <p>Total Donors</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>{{ $totalRecipients }}</h3>
                <p>Total Recipients</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-donate"></i>
                <h3>{{ $totalDonations }}</h3>
                <p>Active Donations</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-utensils"></i>
                <h3>{{ number_format($donationStats['totalServings']) }}</h3>
                <p>Total Servings</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-buttons">
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-action">
                <i class="fas fa-users-cog me-2"></i>Manage Users
            </a>
            <a href="{{ route('admin.show-reports') }}" class="btn btn-outline-primary btn-action">
                <i class="fas fa-file-alt me-2"></i>Generate Reports
            </a>
        </div>

        {{-- Enhanced Donor Activity Section --}}
        <div class="activity-section">
            <div class="activity-header">
                <h4><i class="fas fa-user-plus me-2"></i>Donor Activity Statistics</h4>
            </div>
            <div class="activity-grid">
                <div class="activity-item">
                    <div class="value">{{ $donorStats['activeDonors'] }}</div>
                    <div class="label">Active Donors</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $donorStats['inactiveDonors'] }}</div>
                    <div class="label">Inactive Donors</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $donorStats['donorsWithDonations'] }}</div>
                    <div class="label">Donors with Donations</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $donorStats['avgDonationsPerDonor'] }}</div>
                    <div class="label">Avg Donations/Donor</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ number_format($donorStats['completedServings']) }}</div>
                    <div class="label">Completed Servings</div>
                </div>
            </div>
            
            {{-- Top Donors --}}
            <div class="mt-4">
                <h6 class="section-title">Top 5 Most Active Donors</h6>
                <div class="row">
                    @foreach($donorStats['topDonors'] as $donor)
                    <div class="col-md-4 mb-2">
                        <div class="recent-activity-item">
                            <strong>{{ $donor->name }}</strong><br>
                            <small>{{ $donor->donations_count }} donations</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Enhanced Recipient Activity Section --}}
        <div class="activity-section">
            <div class="activity-header">
                <h4><i class="fas fa-hand-holding-heart me-2"></i>Recipient Activity Statistics</h4>
            </div>
            <div class="activity-grid">
                <div class="activity-item">
                    <div class="value">{{ $recipientStats['activeRecipients'] }}</div>
                    <div class="label">Active Recipients</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $recipientStats['inactiveRecipients'] }}</div>
                    <div class="label">Inactive Recipients</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $recipientStats['recipientsWithReservations'] }}</div>
                    <div class="label">Recipients with Reservations</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $recipientStats['totalReservations'] }}</div>
                    <div class="label">Total Reservations</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $recipientStats['completedReservations'] }}</div>
                    <div class="label">Completed Reservations</div>
                </div>
                <div class="activity-item">
                    <div class="value">{{ $recipientStats['avgReservationsPerRecipient'] }}</div>
                    <div class="label">Avg Reservations/Recipient</div>
                </div>
            </div>
            
            {{-- Top Recipients --}}
            <div class="mt-4">
                <h6 class="section-title">Top 5 Most Active Recipients</h6>
                <div class="row">
                    @foreach($recipientStats['topRecipients'] as $recipient)
                    <div class="col-md-4 mb-2">
                        <div class="recent-activity-item">
                            <strong>{{ $recipient->name }}</strong><br>
                            <small>{{ $recipient->reservations_count }} reservations</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Activity Section --}}
        <div class="activity-section">
            <div class="activity-header">
                <h4><i class="fas fa-clock me-2"></i>Recent Activity</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="section-title">Recent Donations</h6>
                    @foreach($recentActivity['recentDonations'] as $donation)
                    <div class="recent-activity-item">
                        <strong>{{ $donation->donor->name }}</strong> donated 
                        <span class="text-primary">{{ $donation->estimated_servings }} servings</span>
                        <div class="time">{{ $donation->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <h6 class="section-title">Recent Reservations</h6>
                    @foreach($recentActivity['recentReservations'] as $reservation)
                    <div class="recent-activity-item">
                        <strong>{{ $reservation->recipient->name }}</strong> reserved food from 
                        <span class="text-success">{{ $reservation->donation->donor->name }}</span>
                        <div class="time">{{ $reservation->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            {{-- Today's Activity --}}
            <div class="mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="activity-item text-center">
                            <div class="value">{{ $recentActivity['todayDonations'] }}</div>
                            <div class="label">Donations Today</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="activity-item text-center">
                            <div class="value">{{ $recentActivity['todayReservations'] }}</div>
                            <div class="label">Reservations Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="chart-container">
                    <h3 class="mb-3">User Distribution</h3>
                    <canvas id="userDistributionChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="chart-container">
                    <h3 class="mb-3">Donation Trends (Last 6 Months)</h3>
                    <canvas id="donationTrendsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Donation Status Chart --}}
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="chart-container">
                    <h3 class="mb-3">Donation Status Overview</h3>
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
            type: 'pie',
            data: {
                labels: ['Donors', 'Recipients'],
                datasets: [{
                    data: [{{ $totalDonors }}, {{ $totalRecipients }}],
                    backgroundColor: ['#FF6384', '#36A2EB']
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

        // Donation Trends Chart
        const donationCtx = document.getElementById('donationTrendsChart').getContext('2d');
        new Chart(donationCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Donations',
                    data: [@foreach($donationTrends as $count){{ $count }},@endforeach],
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
                            text: 'Number of Donations'
                        }
                    }
                }
            }
        });

        // Donation Status Chart
        const statusCtx = document.getElementById('donationStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Reserved', 'Completed', 'Expired'],
                datasets: [{
                    data: [
                        {{ $donationStats['available'] }},
                        {{ $donationStats['reserved'] }},
                        {{ $donationStats['completed'] }},
                        {{ $donationStats['expired'] }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
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
    </script>
</body>
</html>
                        
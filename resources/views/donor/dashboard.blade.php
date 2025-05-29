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
            background-color: #F5F5DC;
            font-family: 'Arial', sans-serif;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .welcome-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn-disabled {
            pointer-events: none;
            opacity: 0.5;
        }
        .expiration-warning {
            font-size: 0.8rem;
            color: #dc3545;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('donor.donations.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>New Donation
                </a>
                <a href="{{ route('donor.insights') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-chart-line me-2"></i>Insights
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

    <div class="container dashboard-container">
        {{-- Welcome Section --}}
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h2>Welcome, {{ Auth::user()->name }}!</h2>
                    <p>Thank you for helping reduce food waste and support your community.</p>
                </div>
                <i class="fas fa-hands-helping fa-3x"></i>
            </div>
        </div>

        {{-- Donation Statistics --}}
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-utensils text-primary mb-3"></i>
                    <h4>{{ $stats['total'] }}</h4>
                    <p>Total Donations</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-check-circle text-success mb-3"></i>
                    <h4>{{ $stats['completed'] }}</h4>
                    <p>Completed Donations</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-sync text-warning mb-3"></i>
                    <h4>{{ $stats['reserved'] }}</h4>
                    <p>Reserved Donations</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-bread-slice text-info mb-3"></i>
                    <h4>{{ $stats['totalServings'] }}</h4>
                    <p>Total Servings</p>
                </div>
            </div>
        </div>

        {{-- Recent Donations --}}
        <div class="stat-card">
            <div class="section-header">
                <h3>Recent Donations</h3>
                <a href="{{ route('donor.donations.index') }}" class="btn btn-sm btn-outline-primary">
                    View All Donations
                </a>
            </div>
            
            @if($recentDonations->isEmpty())
                <div class="text-center py-4">
                    <p>You haven't made any donations yet.</p>
                    <a href="{{ route('donor.donations.create') }}" class="btn btn-primary">
                        Create Your First Donation
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
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
                                    <td>{{ $donation->food_description }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $donation->food_category)) }}</td>
                                    <td>{{ $donation->estimated_servings }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($donation->best_before)->format('d M Y') }}
                                        @if($donation->isExpired())
                                            <br><span class="expiration-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Expired
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $donation->status == 'available' ? 'success' : 
                                            ($donation->status == 'reserved' ? 'warning' : 'secondary')
                                        }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            {{-- Edit Button --}}
                                            @if(!$donation->isExpired() && $donation->status === 'available')
                                                <a href="{{ route('donor.donations.edit', $donation) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-outline-primary btn-disabled" 
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

        {{-- Donations by Category --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="stat-card">
                    <h3>Donations by Category</h3>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <h3>Monthly Donation Trend</h3>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Charts --}}
    <script>
        // Donations by Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($donationsByCategory as $category => $data)
                        '{{ ucfirst(str_replace('_', ' ', $category)) }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($donationsByCategory as $category => $data)
                            {{ $data['count'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', 
                        '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Donations by Food Category'
                }
            }
        });

        // Monthly Donations Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
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
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }, {
                    label: 'Total Servings',
                    data: [
                        @foreach($monthlyDonations as $month => $data)
                            {{ $data['servings'] }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Donations & Servings'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Donation Trends'
                    }
                }
            }
        });
    </script>
</body>
</html>
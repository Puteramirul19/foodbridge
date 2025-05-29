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
    
    {{-- Chart.js for visualizations --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .admin-dashboard {
            max-width: 1400px;
            margin: 0 auto;
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
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid admin-dashboard">
        <div class="dashboard-header">
            <div>
                <h1 class="mb-2">Admin Dashboard</h1>
                <p class="mb-0">Platform Overview and Management</p>
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
                <p>Total Donations</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-buttons mb-4">
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                <i class="fas fa-users-cog"></i>Manage Users
            </a>
            <a href="{{ route('admin.show-reports') }}" class="btn btn-outline-primary">
                <i class="fas fa-file-alt"></i>Generate Reports
            </a>
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
                    <h3 class="mb-3">Donation Trends</h3>
                    <canvas id="donationTrendsChart"></canvas>
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
                labels: ['Donors', 'Recipients', 'Admins'],
                datasets: [{
                    data: [
                        {{ $totalDonors }}, 
                        {{ $totalRecipients }}, 
                        1 // Assuming a single admin
                    ],
                    backgroundColor: [
                        '#FF6384', 
                        '#36A2EB', 
                        '#FFCE56'
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
    </script>

    {{-- Donation Trends Chart --}}
    <script>
        const donationCtx = document.getElementById('donationTrendsChart').getContext('2d');
        new Chart(donationCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Donations',
                    data: [
                        @foreach($donationTrends as $count)
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
                            text: 'Number of Donations'
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
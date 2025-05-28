<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Insights - FoodBridge</title>
    
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
        .insights-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .insights-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .impact-metric {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
        }
        .impact-metric:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('donor.dashboard') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
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

    <div class="container insights-container">
        <div class="row">
            <div class="col-md-8">
                <div class="insights-card">
                    <h2>Donation Performance</h2>
                    <canvas id="donationPerformanceChart"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="insights-card">
                    <h3>Key Metrics</h3>
                    <div class="impact-metric">
                        <span>Avg. Servings per Donation</span>
                        <strong>{{ number_format($insights['averageServingsPerDonation'], 2) }}</strong>
                    </div>
                    <div class="impact-metric">
                        <span>Most Donated Category</span>
                        <strong>{{ ucfirst(str_replace('_', ' ', $insights['mostDonatedCategory'] ?? 'N/A')) }}</strong>
                    </div>
                    <div class="impact-metric">
                        <span>Food Saved (Servings)</span>
                        <strong>{{ number_format($insights['totalImpact']['foodSaved'], 0) }}</strong>
                    </div>
                    <div class="impact-metric">
                        <span>Waste Avoided (Estimate)</span>
                        <strong>{{ number_format($insights['totalImpact']['potentialWasteAvoided'], 0) }} lbs</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="insights-card mt-4">
            <h3>Detailed Donation Log</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Food Category</th>
                            <th>Description</th>
                            <th>Servings</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations->sortByDesc('created_at') as $donation)
                            <tr>
                                <td>{{ $donation->created_at->format('d M Y') }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $donation->food_category)) }}</td>
                                <td>{{ $donation->food_description }}</td>
                                <td>{{ $donation->estimated_servings }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $donation->status == 'available' ? 'success' : 
                                        ($donation->status == 'reserved' ? 'warning' : 'secondary')
                                    }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No donations yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Charts --}}
    <script>
        // Donation Performance Chart
        const ctx = document.getElementById('donationPerformanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Available', 'Reserved', 'Completed'],
                datasets: [{
                    label: 'Donations',
                    data: [
                        {{ $donations->where('status', 'available')->count() }},
                        {{ $donations->where('status', 'reserved')->count() }},
                        {{ $donations->where('status', 'completed')->count() }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ]
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
                        text: 'Donation Status Distribution'
                    }
                }
            }
        });
    </script>
</body>
</html>
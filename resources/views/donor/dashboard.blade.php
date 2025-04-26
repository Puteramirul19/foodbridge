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
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .donor-dashboard {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .welcome-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-10px);
        }
        .stat-card i {
            font-size: 2.5rem;
            color: #2575fc;
            margin-bottom: 15px;
        }
        .donations-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .donation-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
        }
        .donation-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('donor.donations.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>New Donation
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container donor-dashboard">
        {{-- Welcome Section --}}
        <div class="welcome-section">
            <h1>Welcome, {{ Auth::user()->name }}!</h1>
            <p>Thank you for helping reduce food waste and feed communities.</p>
        </div>

        {{-- Quick Stats --}}
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-utensils"></i>
                <h3>{{ $donations->count() }}</h3>
                <p>Total Donations</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>{{ $donations->where('status', 'completed')->count() }}</h3>
                <p>Completed Donations</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-sync"></i>
                <h3>{{ $donations->where('status', 'available')->count() }}</h3>
                <p>Available Donations</p>
            </div>
        </div>

        {{-- Recent Donations --}}
        <div class="donations-section">
            <h2 class="mb-4">Recent Donations</h2>
            @if($donations->isEmpty())
                <div class="text-center py-4">
                    <p>You haven't made any donations yet.</p>
                    <a href="{{ route('donor.donations.create') }}" class="btn btn-primary">
                        Create Your First Donation
                    </a>
                </div>
            @else
                @foreach($donations->take(5) as $donation)
                    <div class="donation-item">
                        <div>
                            <h5>{{ $donation->food_description }}</h5>
                            <small>{{ $donation->best_before }} | {{ $donation->estimated_servings }} servings</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ 
                                $donation->status == 'available' ? 'success' : 
                                ($donation->status == 'reserved' ? 'warning' : 'secondary')
                            }}">
                                {{ ucfirst($donation->status) }}
                            </span>
                            <a href="{{ route('donor.donations.edit', $donation) }}" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
                <div class="text-center mt-3">
                    <a href="{{ route('donor.donations.index') }}" class="btn btn-outline-primary">
                        View All Donations
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
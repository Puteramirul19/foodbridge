<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Food Requests - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #FAF0E6 0%, #F5E6D3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .reservations-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .stats-badge {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        .filter-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .empty-state {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 60px 40px;
            text-align: center;
            margin-top: 30px;
        }
        
        .empty-icon {
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        
        .pagination {
            justify-content: center;
            margin-top: 40px;
        }
        
        .page-link {
            border-radius: 10px;
            margin: 0 5px;
            border: none;
            background: white;
            color: #667eea;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
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
        
        .btn-group .btn {
            background: white;
            border: 2px solid #e9ecef;
            color: #667eea;
            transition: all 0.3s ease;
        }
        
        .btn-group .btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }
        
        @media (max-width: 768px) {
            .reservations-container {
                padding: 10px;
            }
            
            .page-header {
                padding: 20px;
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
                <a href="{{ route('recipient.dashboard') }}" class="btn btn-outline-primary me-2">
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

    <div class="container-fluid reservations-container">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-3">
                        <i class="fas fa-shopping-basket me-3"></i>My Food Requests
                    </h1>
                    <p class="mb-0 fs-5">Track and manage your food donation requests</p>
                </div>
                <div class="stats-badge">
                    <i class="fas fa-donate me-2"></i>
                    Total Requests: {{ $reservations->total() }}
                </div>
            </div>
        </div>
        
        {{-- Filters --}}
        <div class="filter-section">
            <form action="{{ route('recipient.reservations') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-filter me-2"></i>Status Filter
                        </label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar me-2"></i>Date Range
                        </label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-filter w-100">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Food Requests --}}
        @if($reservations->isEmpty())
            <div class="empty-state">
                <i class="fas fa-search fa-5x empty-icon"></i>
                @if(request()->hasAny(['status', 'start_date']))
                    {{-- When filters are applied but no results --}}
                    <h3 class="mt-3 mb-2">No Food Requests Found</h3>
                    <p class="text-muted fs-5 mb-4">There are currently no food requests matching your search criteria.</p>
                    <p class="text-muted">Try adjusting your filters or check back later for new requests.</p>
                    <a href="{{ route('recipient.reservations') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a>
                @else
                    {{-- When no food requests exist at all --}}
                    <h3 class="mt-3 mb-2">No Food Requests Yet</h3>
                    <p class="text-muted fs-5 mb-4">Start by browsing available food donations in your area and make your first request.</p>
                    <p class="text-muted">Help reduce food waste while getting nutritious meals for your family!</p>
                    <a href="{{ route('recipient.donations.browse') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Browse Food Donations
                    </a>
                @endif
            </div>
        @else
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                                        {{ Str::limit($reservation->donation->food_description, 50) }}
                                        <small class="d-block text-muted mt-1">
                                            {!! \App\Http\Controllers\DonationController::getFormattedFoodCategory($reservation->donation->food_category) !!}
                                        </small>
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
                                        <span class="status-badge 
                                        {{ $reservation->status == 'pending' ? 'bg-warning' : 
                                           ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('recipient.reservations.details', $reservation) }}" 
                                               class="btn btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($reservation->status == 'pending')
                                                <form action="{{ route('recipient.reservations.cancel', $reservation) }}" 
                                                      method="POST" class="d-inline cancel-reservation-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-5">
                {{ $reservations->appends(request()->input())->links() }}
            </div>
        @endif
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Food Request Cancellation Confirmation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm food request cancellation
            const cancelForms = document.querySelectorAll('.cancel-reservation-form');
            cancelForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const confirmCancel = confirm('Are you sure you want to cancel this food request?');
                    if (!confirmCancel) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
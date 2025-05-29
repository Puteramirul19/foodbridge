<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .reservations-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 20px;
        }
        .page-header {
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
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .table {
            margin-bottom: 0;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .filter-section {
            margin-bottom: 20px;
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
                <a href="{{ route('recipient.donations.browse') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-utensils me-2"></i>Browse Donations
                </a>
                <a href="{{ route('recipient.dashboard') }}" class="btn btn-outline-secondary me-2">
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
            <div>
                <h1 class="mb-2">My Reservations</h1>
                <p class="mb-0">Track and manage your food donations</p>
            </div>
            <div>
                <span class="badge bg-light text-dark p-2">
                    <i class="fas fa-shopping-basket me-2"></i>
                    Total Reservations: {{ $reservations->total() }}
                </span>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-section">
            <form action="{{ route('recipient.reservations') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status Filter</label>
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
                        <label class="form-label">Date Range</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Reservations --}}
        @if($reservations->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                <h3>No Reservations Yet</h3>
                <p>Start by browsing available donations in your area.</p>
                <a href="{{ route('recipient.donations.browse') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Browse Donations
                </a>
            </div>
        @else
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Food Description</th>
                                <th>Donor</th>
                                <th>Pickup Details</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td>
                                        {{ Str::limit($reservation->donation->food_description, 50) }}
                                        <small class="d-block text-muted">
                                            {{ ucfirst(str_replace('_', ' ', $reservation->donation->food_category)) }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user me-2"></i>
                                            {{ $reservation->donation->donor->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ $reservation->pickup_date->format('d M Y') }}
                                        <small class="d-block">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $reservation->pickup_time }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge 
                                        {{ $reservation->status == 'pending' ? 'bg-warning' : 
                                           ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('recipient.reservations.details', $reservation) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($reservation->status == 'pending')
                                                <form action="{{ route('recipient.reservations.cancel', $reservation) }}" 
                                                      method="POST" class="d-inline cancel-reservation-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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
                
                <div class="card-footer d-flex justify-content-between align-items-center">
                    {{ $reservations->appends(request()->input())->links() }}
                    <a href="{{ route('recipient.donations.browse') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-2"></i>New Reservation
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Reservation Cancellation Confirmation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm reservation cancellation
            const cancelForms = document.querySelectorAll('.cancel-reservation-form');
            cancelForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const confirmCancel = confirm('Are you sure you want to cancel this reservation?');
                    if (!confirmCancel) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
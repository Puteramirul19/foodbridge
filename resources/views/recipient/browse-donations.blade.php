<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Donations - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .donations-form-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .donation-card {
            transition: transform 0.3s ease;
        }
        .donation-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
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

    <div class="container-fluid">
        <div class="donations-form-container">
            <div class="form-header">
                <div>
                    <h2 class="mb-0">Browse Available Donations</h2>
                    <p class="text-white-50 mb-0">Find and reserve surplus food in your community</p>
                </div>
                <div class="badge bg-primary">
                    {{ $donations->total() }} Donations Available
                </div>
            </div>
            
            <div class="row p-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0">
                                <i class="fas fa-filter me-2"></i>Filter Donations
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('recipient.donations.browse') }}" method="GET">
                                <div class="mb-3">
                                    <label class="form-label">Search</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Search food description"
                                               value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Food Category</label>
                                    <select name="food_category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($foodCategories as $key => $category)
                                            <option value="{{ $key }}" 
                                                {{ request('food_category') == $key ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>Apply Filters
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    @if($donations->isEmpty())
                        <div class="card border-0 shadow-sm text-center p-5">
                            <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                            <h3>No Donations Available</h3>
                            <p class="lead">There are currently no donations matching your search criteria.</p>
                        </div>
                    @else
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            @foreach($donations as $donation)
                                <div class="col">
                                    <div class="card donation-card h-100">
                                        <div class="card-header">
                                            <span class="badge" style="background-color: #6a70ff;">
                                                {{ $foodCategories[$donation->food_category] }}
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ Str::limit($donation->food_description, 50) }}</h5>
                                            <p class="card-text">
                                                <strong>Servings:</strong> {{ $donation->estimated_servings }}<br>
                                                <strong>Best Before:</strong> 
                                                @php 
                                                    $bestBeforeDate = \Carbon\Carbon::parse($donation->best_before);
                                                    $daysLeft = $bestBeforeDate->diffInDays(now(), false);
                                                @endphp
                                                {{ $bestBeforeDate->format('d M Y') }}
                                                @if($daysLeft <= 1)
                                                    <span class="text-danger">(Expiring Soon)</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" 
                                                    class="btn btn-primary w-100 reserve-btn" 
                                                    data-donation-id="{{ $donation->id }}">
                                                <i class="fas fa-shopping-basket me-2"></i>Reserve
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $donations->appends(request()->input())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Reservation Modal --}}
    <div class="modal fade" id="reservationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reserve Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="reservationForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pickup Date</label>
                            <input type="date" name="pickup_date" class="form-control" required 
                                   min="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pickup Time</label>
                            <input type="time" name="pickup_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Reservation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Reservation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
            const reservationForm = document.getElementById('reservationForm');
            const reserveButtons = document.querySelectorAll('.reserve-btn');

            reserveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const donationId = this.dataset.donationId;
                    reservationForm.action = `/recipient/donations/${donationId}/reserve`;
                    reservationModal.show();
                });
            });
        });
    </script>
</body>
</html>
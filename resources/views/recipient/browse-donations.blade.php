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
            background: linear-gradient(135deg, #FAF0E6 0%, #F5E6D3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .browse-container {
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
        
        .page-header .content {
            position: relative;
            z-index: 2;
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
        
        .filter-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: none;
            position: sticky;
            top: 20px;
        }
        
        .filter-card .card-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
            padding: 20px;
        }
        
        .filter-card .card-body {
            padding: 25px;
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
        }
        
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .donations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .donation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.4s ease;
            overflow: hidden;
            position: relative;
        }
        
        .donation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .donation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .category-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
        }
        
        .urgency-badge {
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .card-body-custom {
            padding: 25px;
        }
        
        .card-title-custom {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .donation-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
        }
        
        .detail-icon {
            width: 20px;
            color: #667eea;
            margin-right: 10px;
        }
        
        .btn-request {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 20px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-request:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
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
        
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
            padding: 25px;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .modal-footer {
            border: none;
            padding: 20px 30px 30px;
        }
        
        .btn-modal-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
        }
        
        .btn-modal-secondary {
            background: #6c757d;
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
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
        
        @media (max-width: 768px) {
            .donations-grid {
                grid-template-columns: 1fr;
            }
            
            .browse-container {
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

    <div class="container-fluid browse-container">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-3">
                        <i class="fas fa-search me-3"></i>Browse Available Donations
                    </h1>
                    <p class="mb-0 fs-5">Discover surplus food in your community and help reduce waste</p>
                </div>
                <div class="stats-badge">
                    <i class="fas fa-donate me-2"></i>
                    {{ $donations->total() }} Available
                </div>
            </div>
        </div>
        
        <div class="row">
            {{-- Filters Sidebar --}}
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card filter-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-filter me-2"></i>Filter Donations
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('recipient.donations.browse') }}" method="GET">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tags me-2"></i>Food Category
                                </label>
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

                            <button type="submit" class="btn btn-filter w-100">
                                <i class="fas fa-filter me-2"></i>Apply Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Donations Grid --}}
            <div class="col-lg-9 col-md-8">
                @if($donations->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-box-open fa-5x empty-icon"></i>
                        <h3 class="mt-3 mb-2">No Donations Found</h3>
                        <p class="text-muted fs-5">There are currently no donations matching your filter criteria.</p>
                        <p class="text-muted">Try adjusting your filters or check back later for new donations.</p>
                        <a href="{{ route('recipient.donations.browse') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-refresh me-2"></i>Clear Filters
                        </a>
                    </div>
                @else
                    <div class="donations-grid">
                        @foreach($donations as $donation)
                            <div class="donation-card">
                                <div class="card-header-custom">
                                    <span class="category-badge">
                                        {{ $foodCategories[$donation->food_category] }}
                                    </span>
                                    @php 
                                        $bestBeforeDate = \Carbon\Carbon::parse($donation->best_before);
                                        $daysLeft = $bestBeforeDate->diffInDays(now(), false);
                                    @endphp
                                    @if($daysLeft <= 1)
                                        <span class="urgency-badge">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Urgent
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body-custom">
                                    <h5 class="card-title-custom">
                                        {{ Str::limit($donation->food_description, 60) }}
                                    </h5>
                                    
                                    <div class="donation-details">
                                        <div class="detail-item">
                                            <i class="fas fa-users detail-icon"></i>
                                            <span><strong>Servings:</strong> {{ $donation->estimated_servings }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-alt detail-icon"></i>
                                            <span><strong>Best Before:</strong> 
                                                {{ $bestBeforeDate->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt detail-icon"></i>
                                            <span><strong>Location:</strong> {{ Str::limit($donation->pickup_location, 30) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-truck detail-icon"></i>
                                            <span><strong>Type:</strong> 
                                                <span class="badge bg-info">{{ ucfirst($donation->donation_type) }}</span>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-user detail-icon"></i>
                                            <span><strong>Donor:</strong> {{ $donation->donor->name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-phone detail-icon"></i>
                                            <span><strong>Contact:</strong> {{ $donation->donor->phone_number ?? 'No phone provided' }}</span>
                                        </div>
                                    </div>
                                    
                                    <button type="button" 
                                            class="btn btn-request mt-3" 
                                            data-donation-id="{{ $donation->id }}"
                                            data-best-before="{{ $donation->best_before->format('Y-m-d') }}">
                                        <i class="fas fa-hand-holding-heart me-2"></i>Request This Donation
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $donations->appends(request()->input())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Request Modal --}}
    <div class="modal fade" id="reservationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-check me-2"></i>Request Donation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="reservationForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Please select your preferred pickup date and time. Make sure to coordinate with the donor.
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar me-2"></i>Pickup Date
                            </label>
                            <input type="date" name="pickup_date" class="form-control" required 
                                   min="{{ now()->format('Y-m-d') }}">
                            <div class="form-text">Select a date before the food expires</div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-clock me-2"></i>Pickup Time
                            </label>
                            <input type="time" name="pickup_time" class="form-control" required>
                            <div class="form-text">Choose a convenient pickup time</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-modal-primary">
                            <i class="fas fa-check me-2"></i>Confirm Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Request Modal Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
        const reservationForm = document.getElementById('reservationForm');
        const reserveButtons = document.querySelectorAll('.btn-request');
        const pickupDateInput = document.querySelector('input[name="pickup_date"]');

        // Handle request button clicks
        reserveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const donationId = this.dataset.donationId;
                const bestBeforeDate = this.dataset.bestBefore;
                const today = new Date().toISOString().split('T')[0];

                // Set date constraints - ALLOW pickup on best before date
                pickupDateInput.min = today;
                pickupDateInput.max = bestBeforeDate; // This should allow same day as best before

                // Update form action
                reservationForm.action = `/recipient/donations/${donationId}/accept`;
                
                // Show modal
                reservationModal.show();
            });
        });

        // FIXED: Form validation - allow pickup on best before date
        reservationForm.addEventListener('submit', function(e) {
            const pickupDate = new Date(pickupDateInput.value);
            const today = new Date();
            const bestBefore = new Date(pickupDateInput.max);
            
            // Normalize dates to avoid time zone issues
            pickupDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            bestBefore.setHours(0, 0, 0, 0);

            // FIXED: Use <= instead of < to allow pickup ON the best before date
            if (pickupDate < today || pickupDate > bestBefore) {
                e.preventDefault();
                
                // Show error alert
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Please select a pickup date between today and the food's expiry date (inclusive).
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // Remove any existing alerts first
                document.querySelectorAll('.modal-body .alert').forEach(alert => alert.remove());
                document.querySelector('.modal-body').insertAdjacentHTML('afterbegin', alertHtml);
                
                return false;
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.classList.contains('show')) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            });
        }, 5000);
    });
    </script>
</body>
</html>
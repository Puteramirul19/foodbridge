<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Pickups - FoodBridge</title>
    
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
        
        .pending-container {
            max-width: 1200px;
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
        
        .pickup-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.4s ease;
            position: relative;
        }
        
        .pickup-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .pickup-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .pickup-card.urgent::before {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        }
        
        .pickup-card.expired::before {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-body-custom {
            padding: 25px;
        }
        
        .card-title-custom {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.2rem;
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
        
        .recipient-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            border: 2px solid rgba(0,0,0,0.1);
        }
        
        .contact-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .pickup-time-info {
            background: linear-gradient(135deg, #fff3e0 0%, #ffcc80 100%);
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 20px;
            font-weight: 600;
            color: white;
            flex: 1;
            transition: all 0.3s ease;
        }
        
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-not-collected {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 20px;
            font-weight: 600;
            color: white;
            flex: 1;
            transition: all 0.3s ease;
        }
        
        .btn-not-collected:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
            color: white;
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
        
        .urgency-badge.expired {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            animation: none;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
            color: #00b894;
            margin-bottom: 20px;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
        }
        
        .category-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .pending-container {
                padding: 10px;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
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

    <div class="container-fluid pending-container">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-3">
                        <i class="fas fa-clock me-3"></i>Pending Pickups
                    </h1>
                    <p class="mb-0 fs-5">Donations awaiting pickup confirmation from recipients</p>
                </div>
                <div class="stats-badge">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $pendingPickups->count() }} Pending
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Instructions --}}
        <div class="alert alert-info">
            <h6><i class="fas fa-info-circle me-2"></i>How to manage pickups:</h6>
            <ul class="mb-0">
                <li><strong>Confirm Pickup:</strong> Mark when the recipient has successfully collected the donation</li>
                <li><strong>Not Collected:</strong> Use when the recipient didn't show up or couldn't collect the donation</li>
            </ul>
        </div>

        {{-- Pending Pickups --}}
        @if($pendingPickups->isEmpty())
            <div class="empty-state">
                <i class="fas fa-check-circle fa-5x empty-icon"></i>
                <h3 class="text-success">All Caught Up!</h3>
                <p class="lead">You have no pending pickups at the moment.</p>
                <p>Keep up the great work in helping reduce food waste!</p>
                <div class="mt-4">
                    <a href="{{ route('donor.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        @else
            {{-- Pickup Cards --}}
            @foreach($pendingPickups as $donation)
                @php
                    $reservation = $donation->reservations->where('status', 'pending')->first();
                    $isExpired = $donation->isExpired();
                    $isUrgent = $donation->isExpiringSoon() && !$isExpired;
                    $isDueToday = $reservation && $reservation->pickup_date->isToday();
                    
                    $cardClass = 'normal';
                    $urgencyText = '';
                    
                    if ($isExpired) {
                        $cardClass = 'expired';
                        $urgencyText = 'Expired';
                    } elseif ($isDueToday || $isUrgent) {
                        $cardClass = 'urgent';
                        $urgencyText = $isDueToday ? 'Due Today' : 'Expires Soon';
                    }
                @endphp
                
                <div class="pickup-card {{ $cardClass }}">
                    <div class="card-header-custom">
                        <span class="category-badge">
                            <i class="fas fa-utensils me-1"></i>
                            {!! \App\Http\Controllers\DonationController::getFormattedFoodCategory($donation->food_category) !!}
                        </span>
                        @if($urgencyText)
                            <span class="urgency-badge {{ $isExpired ? 'expired' : '' }}">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $urgencyText }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-lg-8">
                                {{-- Donation Details --}}
                                <h4 class="card-title-custom">{{ $donation->food_description }}</h4>
                                
                                <div class="donation-details">
                                    <div class="detail-item">
                                        <i class="fas fa-users detail-icon"></i>
                                        <span><strong>Servings:</strong> {{ $donation->estimated_servings }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-alt detail-icon"></i>
                                        <span><strong>Best Before:</strong> 
                                            {{ $donation->best_before->format('d M Y') }}
                                            @if($isExpired)
                                                <span class="text-danger fw-bold ms-2">(Expired)</span>
                                            @elseif($isUrgent)
                                                <span class="text-warning fw-bold ms-2">(Expires Soon)</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt detail-icon"></i>
                                        <span><strong>Location:</strong> {{ $donation->pickup_location }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-truck detail-icon"></i>
                                        <span><strong>Type:</strong> {{ ucfirst($donation->donation_type) }}</span>
                                    </div>
                                </div>

                                {{-- Recipient Information --}}
                                @if($reservation)
                                <div class="recipient-info">
                                    <h6 class="mb-3">
                                        <i class="fas fa-user me-2"></i>Recipient & Pickup Details
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="contact-info">
                                                <strong><i class="fas fa-user me-2"></i>{{ $reservation->recipient->name }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>
                                                    {{ $reservation->recipient->phone_number ?? 'No phone provided' }}
                                                </small><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    {{ $reservation->recipient->email }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="pickup-time-info">
                                                <strong>
                                                    <i class="fas fa-calendar me-2"></i>
                                                    {{ $reservation->pickup_date->format('l, d M Y') }}
                                                </strong><br>
                                                <strong>
                                                    <i class="fas fa-clock me-2"></i>
                                                    {{ \Carbon\Carbon::parse($reservation->pickup_time)->format('g:i A') }}
                                                </strong>
                                                @if($isDueToday)
                                                    <span class="badge bg-warning text-dark ms-2">Today</span>
                                                @elseif($reservation->pickup_date->isPast())
                                                    <span class="badge bg-danger ms-2">
                                                        Overdue
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Additional Instructions --}}
                                @if($donation->additional_instructions)
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-sticky-note me-1"></i>
                                            <strong>Instructions:</strong> {{ $donation->additional_instructions }}
                                        </small>
                                    </div>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-lg-4">
                                <div class="d-flex flex-column h-100 justify-content-center">
                                    <div class="action-buttons">
                                        {{-- Confirm Pickup Button --}}
                                        <form action="{{ route('donor.confirm-pickup', $reservation) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-confirm w-100" 
                                                    onclick="return confirm('Confirm that {{ $reservation->recipient->name }} has successfully picked up this donation?')">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <strong>Confirm Pickup</strong>
                                                <br><small>Mark as completed</small>
                                            </button>
                                        </form>
                                        
                                        {{-- Not Collected Button --}}
                                        <form action="{{ route('donor.mark-not-collected', $reservation) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-not-collected w-100" 
                                                    onclick="return confirm('Mark this donation as not collected by {{ $reservation->recipient->name }}?')">
                                                <i class="fas fa-times-circle me-2"></i>
                                                <strong>Not Collected</strong>
                                                <br><small>Cancel reservation</small>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Contact Recipient Button --}}
                                    @if($reservation->recipient->phone_number)
                                    <div class="mt-3 text-center">
                                        <a href="tel:{{ $reservation->recipient->phone_number }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-phone me-1"></i>Call Recipient
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Back to Dashboard --}}
            <div class="text-center mt-4">
                <a href="{{ route('donor.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        @endif
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Auto-refresh for real-time updates --}}
    <script>
        // Auto-refresh page every 5 minutes to keep pickup status current
        setTimeout(function() {
            window.location.reload();
        }, 300000); // 5 minutes

        // Add loading states to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                    
                    // Re-enable after 3 seconds as fallback
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }, 3000);
                });
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
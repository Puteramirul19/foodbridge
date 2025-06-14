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
            background-color: #F5F5DC;
            font-family: 'Arial', sans-serif;
        }
        .pending-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .pending-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pickup-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
        }
        .pickup-card.urgent {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
        }
        .pickup-card.normal {
            border-left-color: #ffc107;
        }
        .pickup-card.expired {
            border-left-color: #6c757d;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }
        .recipient-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-complete {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-complete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
            color: white;
        }
        .btn-not-collected {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-not-collected:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .urgency-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .urgency-urgent {
            background: #dc3545;
            color: white;
            animation: pulse 2s infinite;
        }
        .urgency-today {
            background: #ffc107;
            color: #000;
        }
        .urgency-expired {
            background: #6c757d;
            color: white;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .contact-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }
        .pickup-time-info {
            background: linear-gradient(135deg, #fff3e0 0%, #ffcc80 100%);
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
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
                <a href="{{ route('donor.donations.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-list me-2"></i>All Donations
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

    <div class="container pending-container">
        {{-- Header --}}
        <div class="pending-header">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-clock me-2"></i>Pending Pickups
                </h1>
                <p class="text-white-50 mb-0">Donations awaiting pickup confirmation from recipients</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark p-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $pendingPickups->count() }} Pending
                </span>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Instructions --}}
        <div class="alert alert-info mb-4">
            <h6><i class="fas fa-info-circle me-2"></i>How to manage pickups:</h6>
            <ul class="mb-0">
                <li><strong>Confirm Pickup:</strong> Mark when the recipient has successfully collected the donation</li>
                <li><strong>Not Collected:</strong> Use when the recipient didn't show up or couldn't collect the donation</li>
                <li><strong>Contact Recipients:</strong> Use the contact information provided to coordinate pickups</li>
            </ul>
        </div>

        {{-- Pending Pickups --}}
        @if($pendingPickups->isEmpty())
            <div class="empty-state">
                <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                <h3 class="text-success">All Caught Up!</h3>
                <p class="lead">You have no pending pickups at the moment.</p>
                <p>All your donations are either available, completed, or expired. Keep up the great work in helping reduce food waste!</p>
                <div class="mt-4">
                    <a href="{{ route('donor.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="{{ route('donor.donations.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Create New Donation
                    </a>
                </div>
            </div>
        @else
            {{-- Summary Statistics --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-danger">{{ $pendingPickups->filter(function($d) { return $d->isExpired(); })->count() }}</h4>
                            <small class="text-muted">Expired Items</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-warning">{{ $pendingPickups->filter(function($d) { $r = $d->reservations->where('status', 'pending')->first(); return $r && $r->pickup_date->isToday(); })->count() }}</h4>
                            <small class="text-muted">Due Today</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-info">{{ $pendingPickups->filter(function($d) { return $d->isExpiringSoon() && !$d->isExpired(); })->count() }}</h4>
                            <small class="text-muted">Expiring Soon</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-success">{{ $pendingPickups->sum('estimated_servings') }}</h4>
                            <small class="text-muted">Total Servings</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pickup Cards --}}
            @foreach($pendingPickups as $donation)
                @php
                    $reservation = $donation->reservations->where('status', 'pending')->first();
                    $isExpired = $donation->isExpired();
                    $isUrgent = $donation->isExpiringSoon() && !$isExpired;
                    $isDueToday = $reservation && $reservation->pickup_date->isToday();
                    $isPastDue = $reservation && $reservation->pickup_date->isPast() && !$reservation->pickup_date->isToday();
                    
                    $cardClass = 'normal';
                    $urgencyClass = '';
                    $urgencyText = '';
                    
                    if ($isExpired) {
                        $cardClass = 'expired';
                        $urgencyClass = 'urgency-expired';
                        $urgencyText = 'Expired';
                    } elseif ($isDueToday || $isPastDue) {
                        $cardClass = 'urgent';
                        $urgencyClass = $isDueToday ? 'urgency-today' : 'urgency-urgent';
                        $urgencyText = $isDueToday ? 'Due Today' : 'Overdue';
                    } elseif ($isUrgent) {
                        $cardClass = 'urgent';
                        $urgencyClass = 'urgency-urgent';
                        $urgencyText = 'Expires Soon';
                    }
                @endphp
                
                <div class="pickup-card {{ $cardClass }} position-relative">
                    @if($urgencyText)
                        <span class="urgency-indicator {{ $urgencyClass }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $urgencyText }}
                        </span>
                    @endif
                    
                    <div class="row">
                        <div class="col-lg-8">
                            {{-- Donation Details --}}
                            <div class="mb-3">
                                <h4 class="mb-2">{{ $donation->food_description }}</h4>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-utensils me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $donation->food_category)) }}
                                    </span>
                                    <span class="badge bg-info">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $donation->estimated_servings }} servings
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-truck me-1"></i>
                                        {{ ucfirst($donation->donation_type) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Expiry Information --}}
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <strong>Best Before:</strong> {{ $donation->best_before->format('l, d M Y') }}
                                    @if($isExpired)
                                        <span class="text-danger fw-bold ms-2">
                                            (Expired {{ $donation->best_before->diffForHumans() }})
                                        </span>
                                    @elseif($isUrgent)
                                        <span class="text-warning fw-bold ms-2">
                                            (Expires {{ $donation->best_before->diffForHumans() }})
                                        </span>
                                    @else
                                        <span class="text-success ms-2">
                                            ({{ $donation->best_before->diffForHumans() }})
                                        </span>
                                    @endif
                                </small>
                            </div>

                            {{-- Recipient Information --}}
                            @if($reservation)
                            <div class="recipient-info">
                                <h6 class="mb-3">
                                    <i class="fas fa-user me-2 text-primary"></i>Recipient & Pickup Details
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
                                            @elseif($isPastDue)
                                                <span class="badge bg-danger ms-2">
                                                    {{ $reservation->pickup_date->diffForHumans() }}
                                                </span>
                                            @elseif($reservation->pickup_date->isFuture())
                                                <br><small class="text-muted">{{ $reservation->pickup_date->diffForHumans() }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Location Information --}}
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <strong>Pickup Location:</strong> {{ $donation->pickup_location }}
                                </small>
                            </div>

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
                                    <form action="{{ route('donor.confirm-pickup', $reservation) }}" method="POST" class="w-100 mb-2">
                                        @csrf
                                        <button type="submit" class="btn btn-complete w-100 py-3" 
                                                onclick="return confirm('✅ Confirm that {{ $reservation->recipient->name }} has successfully picked up this donation?\n\nThis will mark the donation as COMPLETED.')">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Confirm Pickup</strong>
                                            <br><small>Mark as completed</small>
                                        </button>
                                    </form>
                                    
                                    {{-- Not Collected Button --}}
                                    <form action="{{ route('donor.mark-not-collected', $reservation) }}" method="POST" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-not-collected w-100 py-3" 
                                                onclick="return confirm('❌ Mark as NOT COLLECTED?\n\nThis will:\n• Cancel the current reservation\n• Make donation available again (if not expired)\n• Mark as expired (if past best before date)\n\nRecipient: {{ $reservation->recipient->name }}')">
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

        // Add confirmation dialogs with better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to buttons
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
        });
    </script>
</body>
</html>
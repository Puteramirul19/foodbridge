<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Details - FoodBridge</title>
    
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
        
        .details-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .details-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .details-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .details-header .content {
            position: relative;
            z-index: 2;
        }
        
        .details-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .details-section {
            display: flex;
            gap: 25px;
            padding: 30px;
        }
        
        .main-details {
            flex: 2;
        }
        
        .sidebar-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
        }
        
        .info-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        
        .info-card-header i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .info-card-header h4 {
            margin: 0;
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .info-table {
            margin: 0;
        }
        
        .info-table th {
            width: 40%;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 15px;
            background: #f8f9fa;
            border: none;
            font-size: 0.9rem;
        }
        
        .info-table td {
            color: #212529;
            padding: 12px 15px;
            border: none;
            font-weight: 500;
        }
        
        .info-table tr {
            border-bottom: 1px solid #f1f3f4;
        }
        
        .info-table tr:last-child {
            border-bottom: none;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .action-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            transition: all 0.3s ease;
            padding: 12px 20px;
            font-weight: 600;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            color: white;
        }
        
        .btn-edit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
        }
        
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
            color: white;
        }
        
        .status-badge {
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .status-available {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
        }
        
        .status-reserved {
            background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
            color: white;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
        }
        
        .status-expired {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .category-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .type-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .expiry-info {
            padding: 10px 15px;
            border-radius: 10px;
            margin-top: 5px;
        }
        
        .expiry-warning {
            background: rgba(255, 193, 7, 0.15);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #856404;
        }
        
        .expiry-danger {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #721c24;
        }
        
        .instructions-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 15px;
            padding: 20px;
            border: 2px solid rgba(33, 150, 243, 0.2);
        }
        
        .instructions-card h4 {
            color: #1976d2;
            margin-bottom: 10px;
        }
        
        .instructions-card p {
            color: #1565c0;
            margin: 0;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .details-section {
                flex-direction: column;
            }
            
            .main-details, .sidebar-details {
                flex: none;
            }
            
            .details-container {
                padding: 10px;
            }
            
            .details-header {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
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

    <div class="container details-container">
        {{-- Details Header --}}
        <div class="details-header">
            <div class="content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-info-circle me-3"></i>Donation Details
                    </h1>
                    <p class="mb-0 fs-5">Complete information about your food donation</p>
                </div>
                <div>
                    <span class="status-badge status-{{ $donation->status }}">
                        <i class="fas {{ 
                            $donation->status == 'available' ? 'fa-check-circle' : 
                            ($donation->status == 'reserved' ? 'fa-clock' : 
                            ($donation->status == 'completed' ? 'fa-thumbs-up' : 'fa-times-circle'))
                        }}"></i>
                        {{ ucfirst($donation->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="details-card">
            <div class="details-section">
                <!-- Main Details Column -->
                <div class="main-details">
                    <div class="info-card mb-4">
                        <div class="info-card-header">
                            <i class="fas fa-utensils"></i>
                            <h4>Food Information</h4>
                        </div>
                        <table class="table info-table">
                            <tr>
                                <th>Food Category</th>
                                <td>
                                    <span class="category-badge">
                                        {!! \App\Http\Controllers\DonationController::getFormattedFoodCategory($donation->food_category) !!}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Food Description</th>
                                <td>{{ $donation->food_description }}</td>
                            </tr>
                            <tr>
                                <th>Estimated Servings</th>
                                <td>
                                    <strong>{{ $donation->estimated_servings }}</strong>
                                    <small class="text-muted ms-2">people</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Best Before Date</th>
                                <td>
                                    @php 
                                        $bestBeforeDate = \Carbon\Carbon::parse($donation->best_before);
                                        $today = \Carbon\Carbon::today();
                                        $isExpired = $bestBeforeDate->lt($today); // Use today(), not now()
                                        $isExpiringSoon = $bestBeforeDate->isSameDay($today) || ($bestBeforeDate->diffInDays($today, false) <= 1 && !$isExpired);
                                    @endphp
                                    <strong>{{ $bestBeforeDate->format('d M Y') }}</strong>
                                    @if($donation->status === 'completed')
                                        <div class="expiry-info" style="background: rgba(116, 185, 255, 0.15); border: 1px solid rgba(116, 185, 255, 0.3); color: #0984e3;">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Completed</strong>
                                        </div>
                                    @elseif($isExpired)
                                        <div class="expiry-info expiry-danger">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Expired</strong>
                                        </div>
                                    @elseif($isExpiringSoon)
                                        <div class="expiry-info expiry-warning">
                                            <i class="fas fa-clock me-2"></i>
                                            <strong>Expiring Soon</strong>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Collection Method</th>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $donation->donation_type == 'direct' ? 'Self-Pickup' : 'Home Delivery' }}               
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-card mb-4">
                        <div class="info-card-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Pickup Information</h4>
                        </div>
                        <table class="table info-table">
                            <tr>
                                <th>Pickup Location</th>
                                <td>{{ $donation->pickup_location }}</td>
                            </tr>
                            <tr>
                                <th>Your Number</th>
                                <td>
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    <strong>{{ $donation->donor->phone_number ?? 'No phone provided' }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>

                    @if($donation->additional_instructions)
                        <div class="instructions-card">
                            <h4>
                                <i class="fas fa-sticky-note me-2"></i>Additional Instructions
                            </h4>
                            <p>{{ $donation->additional_instructions }}</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Details Column -->
                <div class="sidebar-details">
                    @if($donation->status == 'reserved' || $donation->status == 'completed')
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-users"></i>
                                <h4>Reservation Info</h4>
                            </div>
                            <div class="p-3">
                                @php
                                    $reservation = $donation->reservations->first();
                                @endphp
                                @if($reservation)
                                    <p class="mb-2">
                                        <strong><i class="fas fa-user me-2"></i>Recipient:</strong><br>
                                        {{ $reservation->recipient->name }}
                                    </p>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-phone me-2"></i>Phone:</strong><br>
                                        {{ $reservation->recipient->phone_number ?? 'No phone provided' }}
                                    </p>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-calendar me-2"></i>Pickup Date:</strong><br>
                                        {{ $reservation->pickup_date->format('d M Y') }}
                                    </p>
                                    <p class="mb-0">
                                        <strong><i class="fas fa-clock me-2"></i>Pickup Time:</strong><br>
                                        {{ \Carbon\Carbon::parse($reservation->pickup_time)->format('g:i A') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="info-card">
                        <div class="info-card-header">
                            <i class="fas fa-cogs"></i>
                            <h4>Quick Actions</h4>
                        </div>
                        <div class="p-3">
                            <div class="action-buttons">
                                @if($donation->canBeEdited())
                                    <a href="{{ route('donor.donations.edit', $donation) }}" class="btn btn-edit">
                                        <i class="fas fa-edit me-2"></i>Edit Donation
                                    </a>
                                @else
                                    <div class="alert alert-secondary mb-3" role="alert">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <small>
                                            @if($donation->isExpired())
                                                This donation cannot be edited because it has completed/expired.
                                            @else
                                                This donation cannot be edited because it has been {{ $donation->status }}.
                                            @endif
                                        </small>
                                    </div>
                                @endif
                                
                                <a href="{{ route('donor.donations.index') }}" class="btn btn-back">
                                    <i class="fas fa-arrow-left me-2"></i>Back to My Donations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Enhanced interactions --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth hover effects
            const infoCards = document.querySelectorAll('.info-card');
            infoCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });

            // Add click-to-copy functionality for contact number
            const contactNumber = document.querySelector('td strong');
            if (contactNumber && contactNumber.textContent.match(/[\d\-\+\(\)\s]+/)) {
                contactNumber.style.cursor = 'pointer';
                contactNumber.title = 'Click to copy';
                contactNumber.addEventListener('click', function() {
                    navigator.clipboard.writeText(this.textContent).then(() => {
                        // Show temporary feedback
                        const originalText = this.textContent;
                        this.textContent = 'Copied!';
                        setTimeout(() => {
                            this.textContent = originalText;
                        }, 1000);
                    });
                });
            }
        });
    </script>
</body>
</html>
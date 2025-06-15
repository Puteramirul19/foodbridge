<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Request Details - FoodBridge</title>
    
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
        
        .container {
            max-width: 1100px;
        }
        
        .donations-form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .info-table {
            margin: 0;
        }
        
        .info-table th {
            width: 40%;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 15px;
            background: #f8f9fa;
        }
        
        .info-table td {
            color: #212529;
            padding: 12px 15px;
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
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            border: none;
            color: white;
        }
        
        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255,107,107,0.4);
        }
        
        .btn-outline-secondary {
            border-color: #667eea;
            color: #667eea;
        }
        
        .btn-outline-secondary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
        
        @media (max-width: 768px) {
            .details-section {
                flex-direction: column;
            }
            
            .main-details, .sidebar-details {
                flex: none;
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

    <div class="container">
        <div class="donations-form-container">
            <div class="form-header">
                <div>
                    <h2 class="mb-2">Food Request Details</h2>
                    <p class="text-white-50 mb-0">Detailed information about your food donation request</p>
                </div>
                <span class="badge 
                    {{ $reservation->status == 'pending' ? 'bg-warning' : 
                       ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </div>

            <div class="details-section">
                <!-- Main Details Column -->
                <div class="main-details">
                    <div class="info-card mb-4">
                        <div class="info-card-header">
                            <i class="fas fa-shopping-basket"></i>
                            <h4 class="mb-0">Donation Information</h4>
                        </div>
                        <table class="table info-table">
                            <tr>
                                <th>Food Description</th>
                                <td>{{ $reservation->donation->food_description }}</td>
                            </tr>
                            <tr>
                                <th>Food Category</th>
                                <td>
                                    <span class="category-badge">
                                        {!! \App\Http\Controllers\DonationController::getFormattedFoodCategory($reservation->donation->food_category) !!}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Estimated Servings</th>
                                <td>{{ $reservation->donation->estimated_servings }}</td>
                            </tr>
                            <tr>
                                <th>Best Before Date</th>
                                <td>
                                    @php 
                                        $bestBeforeDate = \Carbon\Carbon::parse($reservation->donation->best_before);
                                        $today = \Carbon\Carbon::today();
                                        $isCompleted = $reservation->donation->status === 'completed';
                                        $isExpired = $bestBeforeDate->lt($today);
                                        $isExpiringSoon = $bestBeforeDate->diffInDays($today, false) <= 1 && !$isExpired;
                                    @endphp
                                    <div>
                                        <strong>{{ $bestBeforeDate->format('d M Y') }}</strong>
                                        @if($isCompleted)
                                            {{-- No expiry message for completed donations --}}
                                        @elseif($isExpired)
                                            <small class="d-block text-danger fw-bold">(Expired)</small>
                                        @elseif($isExpiringSoon)
                                            <small class="d-block text-warning fw-bold">(Expiring Soon)</small>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    @if($reservation->donation->additional_instructions)
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-sticky-note"></i>
                                <h4 class="mb-0">Additional Instructions</h4>
                            </div>
                            <div class="p-3">
                                <p class="mb-0">{{ $reservation->donation->additional_instructions }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Details Column -->
                <div class="sidebar-details">
                    <div class="info-card">
                        <div class="info-card-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4 class="mb-0">Pickup Details</h4>
                        </div>
                        <table class="table info-table">
                            <tr>
                                <th>Donor</th>
                                <td>{{ $reservation->donation->donor->name }}</td>
                            </tr>
                            <tr>
                                <th>Pickup Type</th>
                                <td>
                                    <span class="badge" style="background-color: #667eea; color: white;">
                                        {{ ucfirst($reservation->donation->donation_type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Pickup Date</th>
                                <td>{{ $reservation->pickup_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Pickup Time</th>
                                <td>{{ $reservation->formatted_pickup_time }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $reservation->donation->pickup_location }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-card">
                        <div class="info-card-header">
                            <i class="fas fa-phone"></i>
                            <h4 class="mb-0">Contact Information</h4>
                        </div>
                        <div class="p-3">
                            <div class="mb-2">
                                <strong>Donor:</strong> {{ $reservation->donation->donor->name }}
                            </div>
                            <div>
                                <strong>Contact:</strong> {{ $reservation->donation->contact_number }}
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        @if($reservation->status == 'pending')
                            <form action="{{ route('recipient.reservations.cancel', $reservation) }}" 
                                  method="POST" class="cancel-reservation-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-times me-2"></i>Cancel Request
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('recipient.reservations') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to My Food Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
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
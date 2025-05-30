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
            background-color: #FAF0E6;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 1100px; /* Constrain maximum width */
        }
        .donations-form-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .form-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
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
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.1);
        }
        .info-card-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        .info-card-header i {
            margin-right: 10px;
            color: #6a11cb;
        }
        .info-table {
            margin: 0;
        }
        .info-table th {
            width: 40%;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 15px;
        }
        .info-table td {
            color: #212529;
            padding: 12px 15px;
        }
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .action-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
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
                                    <span class="badge" style="background-color: #6a70ff;">
                                        {{ ucfirst(str_replace('_', ' ', $reservation->donation->food_category)) }}
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
                                        $daysLeft = now()->diffInDays($reservation->donation->best_before, false);
                                    @endphp
                                    <span class="{{ $daysLeft <= 1 ? 'text-danger' : 'text-warning' }}">
                                        {{ $reservation->donation->best_before->format('d M Y') }}
                                        @if($daysLeft <= 1)
                                            <small class="d-block">(Expiring Soon)</small>
                                        @endif
                                    </span>
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
                                    <span class="badge bg-info">
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
                                <td>{{ $reservation->pickup_time }}</td>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #FAF0E6;
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
                        <i class="fas fa-sign-out-alt me-2">
                            Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="donations-form-container">
            <div class="form-header">
                <div>
                    <h2 class="mb-0">Reservation Details</h2>
                    <p class="text-white-50 mb-0">Details of your food donation reservation</p>
                </div>
                <span class="badge 
                    {{ $reservation->status == 'pending' ? 'bg-warning' : 
                       ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </div>

            <div class="row p-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0">
                                <i class="fas fa-shopping-basket me-2"></i>Donation Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Food Details</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $reservation->donation->food_description }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
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
                                            <th>Best Before</th>
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
                                <div class="col-md-6">
                                    <h5 class="text-primary">Pickup Details</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Donor</th>
                                            <td>
                                                <i class="fas fa-user me-2"></i>
                                                {{ $reservation->donation->donor->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Donation Type</th>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($reservation->donation->donation_type) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pickup Date</th>
                                            <td>
                                                <i class="fas fa-calendar me-2"></i>
                                                {{ $reservation->pickup_date->format('d M Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pickup Time</th>
                                            <td>
                                                <i class="fas fa-clock me-2"></i>
                                                {{ $reservation->pickup_time }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td>
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                {{ $reservation->donation->pickup_location }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($reservation->donation->additional_instructions)
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h4 class="mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>Additional Instructions
                                </h4>
                            </div>
                            <div class="card-body">
                                <p>{{ $reservation->donation->additional_instructions }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Reservation Status
                            </h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <span class="badge 
                                {{ $reservation->status == 'pending' ? 'bg-warning' : 
                                   ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }} fs-5">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                            
                            @if($reservation->status == 'pending')
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Please ensure you can pick up the donation at the specified time and location.
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                @if($reservation->status == 'pending')
                                    <form action="{{ route('recipient.reservations.cancel', $reservation) }}" 
                                          method="POST" class="cancel-reservation-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="fas fa-times me-2"></i>Cancel Reservation
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('recipient.reservations') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Reservations
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information Card --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="mb-0">
                                <i class="fas fa-phone me-2"></i>Contact Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user me-3 text-primary"></i>
                                <div>
                                    <strong>Donor:</strong> {{ $reservation->donation->donor->name }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone me-3 text-success"></i>
                                <div>
                                    <strong>Contact:</strong> {{ $reservation->donation->contact_number }}
                                </div>
                            </div>
                        </div>
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
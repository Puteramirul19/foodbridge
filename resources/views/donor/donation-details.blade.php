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
            background-color: #F5F5DC;
            font-family: 'Arial', sans-serif;
        }
        .donation-details-container {
            max-width: 800px;
            margin: 30px auto;
        }
        .donation-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .details-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .details-body {
            padding: 30px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f1f1f1;
        }
        .detail-row:last-child {
            border-bottom: none;
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
                <a href="{{ route('donor.donations.index') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-list me-2"></i>My Donations
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

    <div class="container donation-details-container">
        <div class="details-card">
            <div class="donation-header">
                <div>
                    <h2 class="mb-0">Donation Details</h2>
                    <p class="text-white-50 mb-0">Detailed view of your food donation</p>
                </div>
                <span class="badge 
                    {{ $donation->status == 'available' ? 'bg-success' : 
                       ($donation->status == 'reserved' ? 'bg-warning' : 'bg-secondary') }}">
                    {{ ucfirst($donation->status) }}
                </span>
            </div>

            <div class="details-body">
                <div class="detail-row">
                    <div class="fw-bold">Food Category</div>
                    <div>
                        <span class="badge bg-primary">
                            {{ ucfirst(str_replace('_', ' ', $donation->food_category)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="fw-bold">Food Description</div>
                    <div>{{ $donation->food_description }}</div>
                </div>

                <div class="detail-row">
                    <div class="fw-bold">Estimated Servings</div>
                    <div>{{ $donation->estimated_servings }}</div>
                </div>

                <div class="detail-row">
                    <div class="fw-bold">Best Before Date</div>
                    <div>
                        @php 
                            $bestBeforeDate = \Carbon\Carbon::parse($donation->best_before);
                            $daysLeft = $bestBeforeDate->diffInDays(now(), false);
                        @endphp
                        {{ $bestBeforeDate->format('d M Y') }}
                        @if($daysLeft <= 1)
                            <span class="text-danger ms-2">(Expiring Soon)</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="fw-bold">Donation Type</div>
                    <div>
                        <span class="badge bg-info">
                            {{ ucfirst($donation->donation_type) }}
                        </span>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="fw-bold">Pickup Location</div>
                    <div>{{ $donation->pickup_location }}</div>
                </div>

                <div class="detail-row">
                    <div class="fw-bold">Contact Number</div>
                    <div>{{ $donation->contact_number }}</div>
                </div>

                @if($donation->additional_instructions)
                    <div class="detail-row">
                        <div class="fw-bold">Additional Instructions</div>
                        <div>{{ $donation->additional_instructions }}</div>
                    </div>
                @endif

                <div class="detail-row">
                    <div class="fw-bold">Created At</div>
                    <div>{{ $donation->created_at->format('d M Y H:i:s') }}</div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('donor.donations.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Donations
                    </a>
                    @if($donation->status == 'available')
                        <a href="{{ route('donor.donations.edit', $donation) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Donation
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
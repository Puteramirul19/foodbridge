<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Donations - FoodBridge</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .donations-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .donations-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .donations-table {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('donor.donations.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>New Donation
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container donations-container">
        {{-- Donations Header --}}
        <div class="donations-header">
            <div>
                <h1 class="mb-0">My Donations</h1>
                <p class="mb-0">Track and manage your food donations</p>
            </div>
            <div>
                <span class="badge bg-light text-dark">Total Donations: {{ $donations->count() }}</span>
            </div>
        </div>

        {{-- Donations Table --}}
        @if($donations->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                <h3>No Donations Yet</h3>
                <p>You haven't created any food donations. Start helping your community!</p>
                <a href="{{ route('donor.donations.create') }}" class="btn btn-primary">
                    Create Your First Donation
                </a>
            </div>
        @else
            <div class="donations-table">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Food Category</th>
                            <th>Description</th>
                            <th>Servings</th>
                            <th>Best Before</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $donation->food_category)) }}</td>
                                <td>{{ Str::limit($donation->food_description, 30) }}</td>
                                <td>{{ $donation->estimated_servings }}</td>
                                <td>
                                    @php 
                                        $bestBefore = is_string($donation->best_before) 
                                            ? \Carbon\Carbon::parse($donation->best_before) 
                                            : $donation->best_before;
                                        
                                        $daysLeft = now()->diffInDays($bestBefore, false);
                                    @endphp
                                    <span class="{{ $daysLeft <= 1 ? 'text-danger' : 'text-warning' }}">
                                        {{ $bestBefore->format('d M Y') }}
                                        @if($daysLeft <= 1)
                                            <small class="d-block">(Expiring Soon)</small>
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        {{ $donation->status == 'available' ? 'bg-success' : 
                                           ($donation->status == 'reserved' ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary view-donation-btn"
                                                data-donation-details='@json([
                                                    "id" => $donation->id,
                                                    "food_description" => $donation->food_description,
                                                    "food_category" => ucfirst(str_replace('_', ' ', $donation->food_category)),
                                                    "estimated_servings" => $donation->estimated_servings,
                                                    "best_before" => $bestBefore->format('d M Y'),
                                                    "donation_type" => ucfirst($donation->donation_type),
                                                    "pickup_location" => $donation->pickup_location,
                                                    "contact_number" => $donation->contact_number,
                                                    "additional_instructions" => $donation->additional_instructions,
                                                    "status" => $donation->status,
                                                    "donor" => [
                                                        "name" => $donation->donor->name,
                                                        "email" => $donation->donor->email,
                                                        "phone" => $donation->contact_number,
                                                        "role" => ucfirst($donation->donor->role)
                                                    ]
                                                ])'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('donor.donations.edit', $donation) }}" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('donor.donations.destroy', $donation) }}" 
                                              method="POST" class="d-inline delete-donation-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Donation Details Modal --}}
    <div class="modal fade" id="donationDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Donation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card border-0 mb-3">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0">
                                        <i class="fas fa-utensils me-2"></i>Donation Information
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Food Details</h5>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th>Description</th>
                                                    <td id="modal-food-description"></td>
                                                </tr>
                                                <tr>
                                                    <th>Category</th>
                                                    <td id="modal-food-category"></td>
                                                </tr>
                                                <tr>
                                                    <th>Estimated Servings</th>
                                                    <td id="modal-estimated-servings"></td>
                                                </tr>
                                                <tr>
                                                    <th>Best Before</th>
                                                    <td id="modal-best-before"></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Pickup Details</h5>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th>Donation Type</th>
                                                    <td id="modal-donation-type"></td>
                                                </tr>
                                                <tr>
                                                    <th>Pickup Location</th>
                                                    <td id="modal-pickup-location"></td>
                                                </tr>
                                                <tr>
                                                    <th>Contact Number</th>
                                                    <td id="modal-contact-number"></td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td id="modal-donation-status"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="modal-additional-instructions" class="mt-3">
                                        <h5 class="text-primary">Additional Instructions</h5>
                                        <p id="modal-instructions-text"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0">
                                        <i class="fas fa-user me-2"></i>Donor Profile
                                    </h4>
                                </div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('images/default-avatar.png') }}" 
                                         class="rounded-circle mb-3" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <h5 id="modal-donor-name" class="mb-1"></h5>
                                    <p id="modal-donor-role" class="text-muted"></p>
                                    
                                    <div class="mt-3">
                                        <h6 class="text-primary">Contact Information</h6>
                                        <div class="mb-2">
                                            <strong><i class="fas fa-envelope me-2"></i>Email:</strong>
                                            <span id="modal-donor-email"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // View Donation Details
            const viewButtons = document.querySelectorAll('.view-donation-btn');
            const donationDetailsModal = new bootstrap.Modal(document.getElementById('donationDetailsModal'));

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const donationData = JSON.parse(this.dataset.donationDetails);
                    
                    // Populate Modal Fields
                    document.getElementById('modal-food-description').textContent = donationData.food_description;
                    document.getElementById('modal-food-category').textContent = donationData.food_category;
                    document.getElementById('modal-estimated-servings').textContent = donationData.estimated_servings;
                    document.getElementById('modal-best-before').textContent = donationData.best_before;
                    document.getElementById('modal-donation-type').textContent = donationData.donation_type;
                    document.getElementById('modal-pickup-location').textContent = donationData.pickup_location;
                    document.getElementById('modal-contact-number').textContent = donationData.contact_number;
                    document.getElementById('modal-donation-status').textContent = donationData.status;
                    
                    // Additional Instructions
                    const additionalInstructionsEl = document.getElementById('modal-additional-instructions');
                    const instructionsTextEl = document.getElementById('modal-instructions-text');
                    if (donationData.additional_instructions) {
                        additionalInstructionsEl.style.display = 'block';
                        instructionsTextEl.textContent = donationData.additional_instructions;
                    } else {
                        additionalInstructionsEl.style.display = 'none';
                    }

                    // Donor Details
                    document.getElementById('modal-donor-name').textContent = donationData.donor.name;
                    document.getElementById('modal-donor-role').textContent = donationData.donor.role;
                    document.getElementById('modal-donor-email').textContent = donationData.donor.email;

                    // Show Modal
                    donationDetailsModal.show();
                });
            });

            // Delete Donation Confirmation
            const deleteForms = document.querySelectorAll('.delete-donation-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const confirmDelete = confirm('Are you sure you want to delete this donation? This action cannot be undone.');
                    if (!confirmDelete) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
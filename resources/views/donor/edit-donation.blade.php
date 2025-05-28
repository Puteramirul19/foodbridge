<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donation - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .donation-form-container {
            max-width: 700px;
            margin: 30px auto;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            margin: -40px -40px 30px;
            padding: 20px 40px;
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
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('donor.donations.index') }}" class="btn btn-outline-primary me-2">My Donations</a>
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
        <div class="donation-form-container">
            <div class="form-header">
                <h2 class="mb-0">Edit Donation</h2>
                <span class="badge bg-{{ 
                    $donation->status == 'available' ? 'success' : 
                    ($donation->status == 'reserved' ? 'warning' : 'secondary')
                }}">
                    {{ ucfirst($donation->status) }}
                </span>
            </div>
            
            <form action="{{ route('donor.donations.update', $donation) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="food_category" class="form-label">Food Category</label>
                    <select class="form-select @error('food_category') is-invalid @enderror" id="food_category" name="food_category" required>
                        <option value="">Select Food Category</option>
                        <option value="produce" {{ $donation->food_category == 'produce' ? 'selected' : '' }}>Fresh Produce</option>
                        <option value="bakery" {{ $donation->food_category == 'bakery' ? 'selected' : '' }}>Bakery Items</option>
                        <option value="prepared_meals" {{ $donation->food_category == 'prepared_meals' ? 'selected' : '' }}>Prepared Meals</option>
                        <option value="packaged_goods" {{ $donation->food_category == 'packaged_goods' ? 'selected' : '' }}>Packaged Goods</option>
                        <option value="dairy" {{ $donation->food_category == 'dairy' ? 'selected' : '' }}>Dairy Products</option>
                        <option value="other" {{ $donation->food_category == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('food_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="food_description" class="form-label">Food Description</label>
                    <textarea class="form-control @error('food_description') is-invalid @enderror" id="food_description" name="food_description" rows="3" required placeholder="Describe the food items you're donating">{{ old('food_description', $donation->food_description) }}</textarea>
                    @error('food_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estimated_servings" class="form-label">Estimated Servings</label>
                        <input type="number" class="form-control @error('estimated_servings') is-invalid @enderror" id="estimated_servings" name="estimated_servings" required min="1" max="1000" value="{{ old('estimated_servings', $donation->estimated_servings) }}">
                        @error('estimated_servings')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="best_before" class="form-label">Best Before Date</label>
                        <input type="date" class="form-control @error('best_before') is-invalid @enderror" id="best_before" name="best_before" required value="{{ old('best_before', $donation->best_before) }}">
                        @error('best_before')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="donation_type" class="form-label">Donation Type</label>
                    <select class="form-select @error('donation_type') is-invalid @enderror" id="donation_type" name="donation_type" required>
                        <option value="">Select Donation Method</option>
                        <option value="direct" {{ $donation->donation_type == 'direct' ? 'selected' : '' }}>Direct Pickup</option>
                        <option value="dropoff" {{ $donation->donation_type == 'dropoff' ? 'selected' : '' }}>Drop-off at Location</option>
                    </select>
                    @error('donation_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="pickup_location" class="form-label">Pickup/Drop-off Location</label>
                    <input type="text" class="form-control @error('pickup_location') is-invalid @enderror" id="pickup_location" name="pickup_location" required placeholder="Full address or specific location" value="{{ old('pickup_location', $donation->pickup_location) }}">
                    @error('pickup_location')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="tel" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" required placeholder="Contact number for coordination" value="{{ old('contact_number', $donation->contact_number) }}">
                    @error('contact_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="additional_instructions" class="form-label">Additional Instructions (Optional)</label>
                    <textarea class="form-control" id="additional_instructions" name="additional_instructions" rows="2" placeholder="Any special instructions or notes for pickup">{{ old('additional_instructions', $donation->additional_instructions) }}</textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Update Donation
                    </button>
                    <a href="{{ route('donor.donations.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
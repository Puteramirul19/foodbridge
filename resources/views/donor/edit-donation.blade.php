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
            background: linear-gradient(135deg, #FAF0E6 0%, #F5E6D3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .edit-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .edit-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .edit-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .edit-header .content {
            position: relative;
            z-index: 2;
        }
        
        .edit-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .edit-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .edit-card-title {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            font-size: 1.4rem;
        }
        
        .edit-card-body {
            padding: 30px;
        }
        
        .form-control, .form-select, .form-control-textarea {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            color: #667eea;
        }
        
        .btn-update {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
            width: 100%;
            font-size: 1.1rem;
        }
        
        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
            width: 100%;
            font-size: 1.1rem;
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
            color: white;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px dashed #dee2e6;
        }
        
        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .form-help {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .status-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .status-display i {
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        
        .original-info {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .original-info small {
            color: #667eea;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .edit-container {
                padding: 10px;
            }
            
            .edit-header {
                padding: 20px;
            }
            
            .edit-card-body {
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

    <div class="container edit-container">
        {{-- Edit Header --}}
        <div class="edit-header">
            <div class="content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-edit me-3"></i>Edit Donation
                    </h1>
                    <p class="mb-0 fs-5">Update your food donation details</p>
                </div>
                <div>
                    <span class="badge bg-{{ 
                        $donation->status == 'available' ? 'success' : 
                        ($donation->status == 'reserved' ? 'warning' : 'secondary')
                    }}" style="font-size: 1rem; padding: 10px 20px;">
                        {{ ucfirst($donation->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Edit Form --}}
        <div class="edit-card">
            <div class="edit-card-header">
                <h3 class="edit-card-title">
                    <i class="fas fa-utensils me-2"></i>Donation Information
                </h3>
            </div>
            <div class="edit-card-body">
                <form action="{{ route('donor.donations.update', $donation) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Food Information Section --}}
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-apple-alt"></i>Food Information
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="food_category" class="form-label">
                                    <i class="fas fa-tags"></i>Food Type
                                </label>
                                <select class="form-select @error('food_category') is-invalid @enderror" 
                                        id="food_category" name="food_category" required>
                                    <option value="">Select Food Type</option>
                                    <option value="fruits_vegetables" {{ $donation->food_category == 'fruits_vegetables' ? 'selected' : '' }}>🥕 Fruits & Vegetables</option>
                                    <option value="bread_rice" {{ $donation->food_category == 'bread_rice' ? 'selected' : '' }}>🍞 Bread, Rice & Grains</option>
                                    <option value="cooked_food" {{ $donation->food_category == 'cooked_food' ? 'selected' : '' }}>🍲 Cooked Food & Meals</option>
                                    <option value="canned_bottled" {{ $donation->food_category == 'canned_bottled' ? 'selected' : '' }}>🥫 Canned & Bottled Items</option>
                                    <option value="milk_eggs" {{ $donation->food_category == 'milk_eggs' ? 'selected' : '' }}>🥛 Milk, Eggs & Dairy</option>
                                    <option value="other" {{ $donation->food_category == 'other' ? 'selected' : '' }}>📦 Other Food Items</option>
                                </select>
                                @error('food_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="estimated_servings" class="form-label">
                                    <i class="fas fa-users"></i>Estimated Servings
                                </label>
                                <input type="number" 
                                       class="form-control @error('estimated_servings') is-invalid @enderror" 
                                       id="estimated_servings" 
                                       name="estimated_servings" 
                                       required min="1" max="1000"
                                       value="{{ old('estimated_servings', $donation->estimated_servings) }}"
                                       placeholder="How many people can this feed?">
                                @error('estimated_servings')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="food_description" class="form-label">
                                <i class="fas fa-clipboard-list"></i>Food Description
                            </label>
                            <textarea class="form-control @error('food_description') is-invalid @enderror" 
                                      id="food_description" 
                                      name="food_description" 
                                      rows="4" required 
                                      placeholder="Describe the food items you're donating in detail...">{{ old('food_description', $donation->food_description) }}</textarea>
                            @error('food_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="best_before" class="form-label">
                                <i class="fas fa-calendar-alt"></i>Best Before Date
                            </label>
                            <input type="date" 
                                   class="form-control @error('best_before') is-invalid @enderror" 
                                   id="best_before" 
                                   name="best_before" required
                                   value="{{ old('best_before', $donation->best_before->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}">
                            <div class="original-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Original Date: {{ $donation->best_before->format('d M Y') }}
                                </small>
                            </div>
                            @error('best_before')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Pickup Information Section --}}
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-map-marker-alt"></i>Pickup Information
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="donation_type" class="form-label">
                                    <i class="fas fa-truck"></i>Donation Type
                                </label>
                                <select class="form-select @error('donation_type') is-invalid @enderror" 
                                        id="donation_type" name="donation_type" required>
                                    <option value="">Select Donation Method</option>
                                    <option value="direct" {{ $donation->donation_type == 'direct' ? 'selected' : '' }}>Direct Pickup</option>
                                    <option value="dropoff" {{ $donation->donation_type == 'dropoff' ? 'selected' : '' }}>Drop-off at Location</option>
                                </select>
                                @error('donation_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                        </div>

                        <div class="mb-3">
                            <label for="pickup_location" class="form-label">
                                <i class="fas fa-location-dot"></i>Pickup/Drop-off Location
                            </label>
                            <input type="text" 
                                   class="form-control @error('pickup_location') is-invalid @enderror" 
                                   id="pickup_location" 
                                   name="pickup_location" required 
                                   value="{{ old('pickup_location', $donation->pickup_location) }}"
                                   placeholder="Full address or specific location details">
                            @error('pickup_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="additional_instructions" class="form-label">
                                <i class="fas fa-sticky-note"></i>Additional Instructions (Optional)
                            </label>
                            <textarea class="form-control" 
                                      id="additional_instructions" 
                                      name="additional_instructions" 
                                      rows="3" 
                                      placeholder="Any special pickup instructions, parking details, or other notes...">{{ old('additional_instructions', $donation->additional_instructions) }}</textarea>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-update">
                                <i class="fas fa-save me-2"></i>Update Donation
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('donor.donations.index') }}" class="btn btn-cancel">
                                <i class="fas fa-times me-2"></i>Cancel Changes
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Date Validation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bestBeforeInput = document.getElementById('best_before');
            
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            bestBeforeInput.min = today;
            
            // Add event listener to prevent past dates
            bestBeforeInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const todayDate = new Date();
                
                // Remove timezone information for accurate comparison
                selectedDate.setHours(0, 0, 0, 0);
                todayDate.setHours(0, 0, 0, 0);
                
                // Check if selected date is before today
                if (selectedDate < todayDate) {
                    // Reset to today's date
                    this.value = today;
                    alert('Please select a date today or in the future.');
                }
            });

            // Existing form enhancement code...
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-save me-2"></i>Update Donation';
                }, 3000);
            });

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('show')) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                });
            }, 8000);
        });
    </script>
</body>
</html>
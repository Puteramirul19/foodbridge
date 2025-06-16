<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Donation - FoodBridge</title>
    
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
        
        .donation-container {
            max-width: 800px;
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
        
        .donation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-title-custom {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            font-size: 1.4rem;
        }
        
        .card-body-custom {
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
        
        .btn-create {
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
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
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
        
        .category-icons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .category-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: white;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .category-item i {
            margin-right: 8px;
            color: #667eea;
        }
        
        @media (max-width: 768px) {
            .donation-container {
                padding: 10px;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .card-body-custom {
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

    <div class="container donation-container">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="content">
                <h1 class="mb-2">
                    <i class="fas fa-plus-circle me-3"></i>Create New Donation
                </h1>
                <p class="mb-0 fs-5">Share your surplus food with those in need and help reduce waste</p>
            </div>
        </div>

        {{-- Donation Form --}}
        <div class="donation-card">
            <div class="card-header-custom">
                <h3 class="card-title-custom">
                    <i class="fas fa-utensils me-2"></i>Donation Details
                </h3>
            </div>
            
            <div class="card-body-custom">
                <form action="{{ route('donor.donations.store') }}" method="POST">
                    @csrf
                    
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
                                    <option value="fruits_vegetables">🥕 Fruits & Vegetables</option>
                                    <option value="bread_rice">🍞 Bread, Rice & Grains</option>
                                    <option value="cooked_food">🍲 Cooked Food & Meals</option>
                                    <option value="canned_bottled">🥫 Canned & Bottled Items</option>
                                    <option value="milk_eggs">🥛 Milk, Eggs & Dairy</option>
                                    <option value="other">📦 Other Food Items</option>
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
                                    placeholder="How many people can this feed?">
                                <div class="form-help">Enter the approximate number of people this food can serve (or quantity for ingredients like rice, oil, etc.)</div>
                                @error('estimated_servings')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        <div class="mb-3">
                            <label for="food_description" class="form-label">
                                <i class="fas fa-clipboard-list"></i>Food Description
                            </label>
                            <textarea class="form-control @error('food_description') is-invalid @enderror" 
                                      id="food_description" 
                                      name="food_description" 
                                      rows="4" required 
                                      placeholder="Describe the food items you're donating in detail..."></textarea>
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
                                   name="best_before" required>
                            <div class="form-help">When should this food be consumed by?</div>
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
                                    <option value="direct">Direct Pickup</option>
                                    <option value="dropoff">Drop-off at Location</option>
                                </select>
                                <div class="form-help">How will the food be transferred?</div>
                                @error('donation_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- REMOVED: Contact Number Field - Using donor's registered phone number instead -->
                            
                        </div>

                        <div class="mb-3">
                            <label for="pickup_location" class="form-label">
                                <i class="fas fa-location-dot"></i>Pickup/Drop-off Location
                            </label>
                            <input type="text" 
                                   class="form-control @error('pickup_location') is-invalid @enderror" 
                                   id="pickup_location" 
                                   name="pickup_location" required 
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
                                      placeholder="Any special pickup instructions, parking details, or other notes..."></textarea>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-create">
                            <i class="fas fa-heart me-2"></i>Create Donation & Help Community
                        </button>
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

            // Form enhancement
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Donation...';
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-heart me-2"></i>Create Donation & Help Community';
                }, 3000);
            });
        });
    </script>
</body>
</html>
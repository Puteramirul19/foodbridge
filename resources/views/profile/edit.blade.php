<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - FoodBridge</title>
    
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
            max-width: 700px;
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
            font-size: 1.3rem;
        }
        
        .edit-card-body {
            padding: 30px;
        }
        
        .form-control, .form-select {
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
        
        .btn-save {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }
        
        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
            color: white;
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
        
        .password-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }
        
        .password-section h5 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .password-section h5 i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .password-help {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .role-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .role-display i {
            font-size: 1.5rem;
            margin-right: 15px;
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
            <a class="navbar-brand d-flex align-items-center" href="{{ 
                auth()->user()->role === 'donor' ? route('donor.dashboard') : route('recipient.dashboard') 
            }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('profile.show') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-user me-2"></i>View Profile
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
            <div class="content">
                <h1 class="mb-2">
                    <i class="fas fa-edit me-3"></i>Edit Profile
                </h1>
                <p class="mb-0 fs-5">Update your personal information and account settings</p>
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
                    <i class="fas fa-user-edit me-2"></i>Profile Information
                </h3>
            </div>
            <div class="edit-card-body">
                {{-- Account Type Display --}}
                <div class="role-display">
                    <i class="fas {{ $user->role === 'donor' ? 'fa-hand-holding-heart' : 'fa-utensils' }}"></i>
                    <div>
                        <strong>Account Type: {{ ucfirst($user->role) }}</strong>
                        <div style="font-size: 0.9rem; opacity: 0.8;">
                            Account type cannot be changed
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    {{-- Name Field --}}
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            <i class="fas fa-signature"></i>Full Name
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               placeholder="Enter your full name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Email Field --}}
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>Email Address
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               placeholder="Enter your email address">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Phone Number Field --}}
                    <div class="mb-4">
                        <label for="phone_number" class="form-label">
                            <i class="fas fa-phone"></i>Phone Number
                        </label>
                        <input type="tel" 
                               class="form-control @error('phone_number') is-invalid @enderror" 
                               id="phone_number" 
                               name="phone_number" 
                               value="{{ old('phone_number', $user->phone_number) }}" 
                               required 
                               placeholder="Enter your phone number">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Password Section --}}
                    <div class="password-section">
                        <h5>
                            <i class="fas fa-lock"></i>Change Password
                        </h5>
                        <p class="text-muted">Leave blank to keep your current password</p>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-key"></i>New Password
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter new password (optional)">
                            <div class="password-help">Password must be at least 8 characters long</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-0">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-check"></i>Confirm New Password
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirm new password">
                        </div>
                    </div>
                    
                    {{-- Submit Buttons --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('profile.show') }}" class="btn btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Form Validation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');
            
            // Real-time password confirmation validation
            confirmPasswordField.addEventListener('input', function() {
                if (passwordField.value && this.value) {
                    if (passwordField.value !== this.value) {
                        this.setCustomValidity('Passwords do not match');
                        this.classList.add('is-invalid');
                    } else {
                        this.setCustomValidity('');
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid', 'is-valid');
                }
            });

            // Password field validation
            passwordField.addEventListener('input', function() {
                if (this.value) {
                    if (this.value.length < 8) {
                        this.setCustomValidity('Password must be at least 8 characters long');
                        this.classList.add('is-invalid');
                    } else {
                        this.setCustomValidity('');
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                    
                    // Re-validate confirmation field
                    if (confirmPasswordField.value) {
                        confirmPasswordField.dispatchEvent(new Event('input'));
                    }
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid', 'is-valid');
                    
                    // Clear confirmation field validation
                    confirmPasswordField.setCustomValidity('');
                    confirmPasswordField.classList.remove('is-invalid', 'is-valid');
                }
            });

            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (passwordField.value && passwordField.value !== confirmPasswordField.value) {
                    e.preventDefault();
                    alert('Passwords do not match. Please check and try again.');
                    confirmPasswordField.focus();
                }
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
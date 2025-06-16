<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #FEFAE0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }
        .register-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header img {
            height: 60px;
            margin-bottom: 15px;
        }
        .register-header h2 {
            color: #4A5568;
            font-weight: bold;
        }
        .form-control {
            border-radius: 6px;
            padding: 12px;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #2575fc;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1a5adf;
        }
        .form-select {
            border-radius: 6px;
            padding: 12px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .phone-hint {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
        .phone-hint i {
            color: #28a745;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo">
            <h2>Register for FoodBridge</h2>
        </div>
        
        {{-- Display validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                    id="phone_number" 
                    name="phone_number" 
                    required 
                    value="{{ old('phone_number') }}">
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" 
                       name="password_confirmation" required>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">Register as</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="">Choose your role</option>
                    <option value="donor" {{ old('role') === 'donor' ? 'selected' : '' }}>Donor</option>
                    <option value="recipient" {{ old('role') === 'recipient' ? 'selected' : '' }}>Recipient</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Register</button>
            
            <div class="login-link">
                <p class="mt-3">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-primary">Login here</a>
                </p>
            </div>
        </form>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Font Awesome for icons --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    
    {{-- Phone number formatting script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone_number');
            
            // Auto-format phone number as user types
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
                
                // Format Malaysian phone number
                if (value.length >= 3) {
                    if (value.length <= 6) {
                        value = value.replace(/(\d{3})(\d+)/, '$1-$2');
                    } else {
                        value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1-$2-$3');
                    }
                }
                
                e.target.value = value;
            });
            
            // Limit to reasonable phone number length
            phoneInput.addEventListener('keypress', function(e) {
                if (e.target.value.replace(/\D/g, '').length >= 11 && e.key !== 'Backspace' && e.key !== 'Delete') {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
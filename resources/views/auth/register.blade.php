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
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo">
            <h2>Register for FoodBridge</h2>
        </div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required autofocus>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                    id="phone_number" 
                    name="phone_number" 
                    required 
                    value="{{ old('phone_number') }}"
                    placeholder="Enter your phone number">
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">Register as</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="donor">Donor</option>
                    <option value="recipient">Recipient</option>
                </select>
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
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FoodBridge</title>
    
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
        
        .profile-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .profile-header .content {
            position: relative;
            z-index: 2;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 20px;
            border: 4px solid rgba(255,255,255,0.3);
        }
        
        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .profile-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .profile-card-title {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
        }
        
        .profile-card-body {
            padding: 30px;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .info-row:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
            font-size: 1.1rem;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 2px;
            font-weight: 500;
        }
        
        .info-value {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }
        
        .btn-edit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }
        
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
            color: white;
        }
        
        .role-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
        }
        
        @media (max-width: 768px) {
            .profile-container {
                padding: 10px;
            }
            
            .profile-header {
                padding: 20px;
            }
            
            .profile-card-body {
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
                <a href="{{ 
                    auth()->user()->role === 'donor' ? route('donor.dashboard') : route('recipient.dashboard') 
                }}" class="btn btn-outline-primary me-2">
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

    <div class="container profile-container">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Profile Header --}}
        <div class="profile-header">
            <div class="content d-flex align-items-center">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="ms-3 flex-grow-1">
                    <h1 class="mb-2">{{ $user->name }}</h1>
                    <p class="mb-2 fs-5">{{ $user->email }}</p>
                    <span class="role-badge">
                        <i class="fas {{ $user->role === 'donor' ? 'fa-hand-holding-heart' : 'fa-utensils' }} me-2"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-edit">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>

        {{-- Profile Information --}}
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">
                    <i class="fas fa-user me-2"></i>Profile Information
                </h3>
            </div>
            <div class="profile-card-body">
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-signature"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value">{{ $user->phone_number ?? 'Not provided' }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Account Type</div>
                        <div class="info-value">
                            <span class="role-badge">
                                <i class="fas {{ $user->role === 'donor' ? 'fa-hand-holding-heart' : 'fa-utensils' }} me-2"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">{{ $user->created_at->format('F d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Button - Only Back to Dashboard --}}
        <div class="text-center">
            <a href="{{ 
                auth()->user()->role === 'donor' ? route('donor.dashboard') : route('recipient.dashboard') 
            }}" class="btn btn-back">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Auto-dismiss alerts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('show')) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                });
            }, 5000);
        });
    </script>
</body>
</html>
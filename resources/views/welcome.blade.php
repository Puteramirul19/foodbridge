<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodBridge - Connecting Food Donors and Recipients</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    {{-- Custom Styles --}}
    <style>
        body {
            background-color: #f4f6f9;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-section {
            background-image: url('{{ asset('Prototype.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 150px 0;
            position: relative;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }
        .hero-section > .container {
            position: relative;
            z-index: 2;
        }
        .hero-section h1 {
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .hero-section .lead {
            font-size: 1.25rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        .how-it-works {
            padding: 80px 0;
            background-color: #FEFAE0;
        }
        .feature-box {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            border-top: 4px solid #2575fc;
        }
        .feature-box:hover {
            transform: translateY(-15px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 35px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-get-started {
            transition: all 0.3s ease;
            transform: translateY(0);
        }
        .btn-get-started:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .hero-section .container > * {
            animation: fadeInUp 1s ease forwards;
            opacity: 0;
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                {{-- Logo --}}
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
                
                {{-- FoodBridge Text --}}
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
            @else
                <a href="{{ 
                    auth()->user()->role === 'admin' ? route('admin.dashboard') : 
                    (auth()->user()->role === 'donor' ? route('donor.dashboard') : route('recipient.dashboard')) 
                }}" class="btn btn-primary">Dashboard</a>
            @endguest
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Connecting Food Donors with Those in Need</h1>
            <p class="lead mb-5">Transform surplus food into hope. Reduce waste, feed communities.</p>
            <div>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-get-started btn-light btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i>Get Started
                    </a>
                @endguest
                <a href="#how-it-works" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-info-circle me-2"></i>Learn More
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section id="how-it-works" class="how-it-works text-center">
        <div class="container">
            <h2 class="mb-5">How FoodBridge Works</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-circle">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3>1. Register</h3>
                        <p>Create an account as a Donor or Recipient. It's quick, easy, and free.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-circle">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>2. Donate/Request</h3>
                        <p>Donors list surplus food, Recipients browse available donations.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-circle">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>3. Connect</h3>
                        <p>Match and coordinate food pickup seamlessly through our platform.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Impact Section --}}
    <section class="bg-light py-5 text-center" style="background-color: #FEFAE0;">
        <div class="container">
            <h2 class="mb-4">Our Impact</h2>
            <div class="row">
                <div class="col-md-4">
                    <h3 class="display-4 text-primary">500+</h3>
                    <p>Meals Donated</p>
                </div>
                <div class="col-md-4">
                    <h3 class="display-4 text-primary">50+</h3>
                    <p>Active Donors</p>
                </div>
                <div class="col-md-4">
                    <h3 class="display-4 text-primary">25+</h3>
                    <p>Community Partners</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} FoodBridge. All rights reserved.</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
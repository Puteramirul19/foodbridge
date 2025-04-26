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
        }
        .hero-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 100px 0;
        }
        .how-it-works {
            padding: 80px 0;
            background-color: white;
        }
        .feature-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-10px);
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            background-color: #2575fc;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                {{-- Logo --}}
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
                
                {{-- FoodBridge Text --}}
                <span class="fw-bold" style="color: #2575fc; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
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
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">Get Started</a>
                @endguest
                <a href="#how-it-works" class="btn btn-outline-light btn-lg">Learn More</a>
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
    <section class="bg-light py-5 text-center">
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
            <div class="social-links mt-3">
                <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
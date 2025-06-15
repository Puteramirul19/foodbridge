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
            background: linear-gradient(135deg, #FAF0E6 0%, #F5E6D3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
        }
        
        /* Navigation Enhancements */
        .navbar {
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .navbar-brand {
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar .btn {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .navbar .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        /* Hero Section */
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
            background-color: rgba(0, 0, 0, 0.5);
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
        
        .btn-get-started {
            background: white;
            color: #2c3e50;
            border: none;
            border-radius: 50px;
            padding: 15px 35px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .btn-get-started:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            color: #2c3e50;
            background: #f8f9fa;
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.8);
            border-radius: 50px;
            padding: 15px 35px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.1);
            transition: all 0.4s ease;
        }
        
        .btn-outline-light:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,1);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        /* How It Works Section */
        .how-it-works {
            padding: 100px 0;
            background: linear-gradient(135deg, #FEFAE0 0%, #FDF4E3 100%);
            position: relative;
        }
        
        .how-it-works h2 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 4rem;
            position: relative;
        }
        
        .how-it-works h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        .feature-box {
            background: white;
            border-radius: 25px;
            padding: 40px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .feature-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .feature-box:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .icon-circle {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 36px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        
        .feature-box:hover .icon-circle {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }
        
        .feature-box h3 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 15px;
        }
        
        .feature-box p {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        /* Impact Section */
        .impact-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #E6DFD0 0%, #DDD4C7 100%);
            position: relative;
        }
        
        .impact-section h2 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 4rem;
            position: relative;
        }
        
        .impact-section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        .impact-stat {
            padding: 40px 25px;
            background: white;
            border-radius: 25px;
            margin: 15px 0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-center: center;
        }
        
        .impact-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .impact-stat:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .impact-stat h3 {
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .impact-stat .lead {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 8px;
        }
        
        .impact-stat small {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .growing-message {
            background: rgba(255,255,255,0.8);
            border-radius: 25px;
            padding: 25px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(102, 126, 234, 0.2);
            margin-top: 3rem;
        }
        
        .growing-message p {
            color: #667eea;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .growing-message i {
            color: #4facfe;
            margin-right: 10px;
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        
        footer p {
            margin: 0;
            font-size: 1rem;
            opacity: 0.9;
        }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: rotate(45deg) translateY(0px); }
            50% { transform: rotate(45deg) translateY(-20px); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-section .container > * {
            animation: fadeInUp 1s ease forwards;
            opacity: 0;
        }
        
        .hero-section .container > *:nth-child(1) { animation-delay: 0.2s; }
        .hero-section .container > *:nth-child(2) { animation-delay: 0.4s; }
        .hero-section .container > *:nth-child(3) { animation-delay: 0.6s; }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 100px 0;
            }
            
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section .lead {
                font-size: 1.1rem;
            }
            
            .how-it-works, .impact-section {
                padding: 60px 0;
            }
            
            .btn-get-started, .btn-outline-light {
                padding: 12px 25px;
                font-size: 1rem;
            }
            
            .feature-box {
                margin-bottom: 40px;
            }
        }
        
        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .animate-on-scroll.animate {
            opacity: 1;
            transform: translateY(0);
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
                <span class="fw-bold" style="color: #4A5568; font-size: 1.4rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            @else
                <a href="{{ 
                    auth()->user()->role === 'admin' ? route('admin.dashboard') : 
                    (auth()->user()->role === 'donor' ? route('donor.dashboard') : route('recipient.dashboard')) 
                }}" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
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
            <h2 class="animate-on-scroll">How FoodBridge Works</h2>
            <div class="row">
                <div class="col-lg-4 animate-on-scroll">
                    <div class="feature-box">
                        <div class="icon-circle">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3>1. Register</h3>
                        <p>Create your account as a Donor or Recipient in just a few clicks. Join our growing community of food heroes making a difference.</p>
                    </div>
                </div>
                <div class="col-lg-4 animate-on-scroll">
                    <div class="feature-box">
                        <div class="icon-circle">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>2. Share & Discover</h3>
                        <p>Donors list surplus food with details and pickup information. Recipients browse and find nutritious meals in their area.</p>
                    </div>
                </div>
                <div class="col-lg-4 animate-on-scroll">
                    <div class="feature-box">
                        <div class="icon-circle">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>3. Connect & Impact</h3>
                        <p>Coordinate seamless food pickup through our platform. Every connection creates positive impact for our community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Impact Section with Real Data --}}
    <section class="impact-section text-center">
        <div class="container">
            <h2 class="animate-on-scroll">Our Community Impact</h2>
            <div class="row">
                <div class="col-lg-4 animate-on-scroll">
                    <div class="impact-stat">
                        <h3>{{ number_format($totalServings) }}+</h3>
                        <p class="lead">Meals Served</p>
                        <small>Estimated food servings donated through our platform</small>
                    </div>
                </div>
                <div class="col-lg-4 animate-on-scroll">
                    <div class="impact-stat">
                        <h3>{{ $activeDonors }}</h3>
                        <p class="lead">Active Donors</p>
                        <small>Generous community members sharing surplus food</small>
                    </div>
                </div>
                <div class="col-lg-4 animate-on-scroll">
                    <div class="impact-stat">
                        <h3>{{ $activeRecipients }}</h3>
                        <p class="lead">Active Recipients</p>
                        <small>Families and individuals receiving nutritious meals</small>
                    </div>
                </div>
            </div>
            
            {{-- Growing Community Message --}}
            @if($totalServings < 50)
            <div class="growing-message animate-on-scroll">
                <p>
                    <i class="fas fa-seedling"></i>
                    We're just getting started! Join us in building a stronger, more sustainable community where no food goes to waste and no one goes hungry.
                </p>
            </div>
            @endif
        </div>
    </section>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} FoodBridge. All rights reserved. | Building bridges between surplus and need.</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Scroll Animation Script --}}
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);
        
        // Observe all elements with animate-on-scroll class
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
        
        // Add staggered animation delays for feature boxes and impact stats
        document.querySelectorAll('.feature-box').forEach((box, index) => {
            box.style.transitionDelay = `${index * 0.2}s`;
        });
        
        document.querySelectorAll('.impact-stat').forEach((stat, index) => {
            stat.style.transitionDelay = `${index * 0.2}s`;
        });
    </script>
</body>
</html>
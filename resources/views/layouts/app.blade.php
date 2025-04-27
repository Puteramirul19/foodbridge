<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>@yield('title', 'FoodBridge')</title>
{{-- Bootstrap CSS --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

{{-- Font Awesome --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

{{-- Custom Styles --}}
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Arial', sans-serif;
    }
    .main-container {
        padding-top: 20px;
    }
    .navbar-brand img {
        height: 40px;
        margin-right: 10px;
    }
    .sidebar {
        background-color: #ffffff;
        border-right: 1px solid #e0e0e0;
        min-height: 100vh;
        padding-top: 20px;
    }
    .sidebar .nav-link {
        color: #333;
        transition: all 0.3s ease;
    }
    .sidebar .nav-link:hover {
        background-color: #f8f9fa;
        color: #2575fc;
    }
    .sidebar .nav-link.active {
        background-color: #2575fc;
        color: white;
    }
    .flash-message {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
    }
    .content-wrapper {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 20px;
    }
</style>

@yield('styles')
</head> <body> {{-- Navbar --}} <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm"> <div class="container-fluid"> <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}"> <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo"> <span class="fw-bold" style="color: #2575fc;">FoodBridge</span> </a> <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"> <span class="navbar-toggler-icon"></span> </button> <div class="collapse navbar-collapse" id="navbarContent"> <ul class="navbar-nav ms-auto mb-2 mb-lg-0"> @guest <li class="nav-item"> <a class="nav-link" href="{{ route('login') }}">Login</a> </li> <li class="nav-item"> <a class="nav-link btn btn-primary text-white" href="{{ route('register') }}">Register</a> </li> @else @if(Auth::user()->role == 'donor') <li class="nav-item"> <a class="nav-link" href="{{ route('donor.dashboard') }}">Dashboard</a> </li> <li class="nav-item"> <a class="nav-link" href="{{ route('donor.donations.create') }}">New Donation</a> </li> @elseif(Auth::user()->role == 'recipient') <li class="nav-item"> <a class="nav-link" href="{{ route('recipient.dashboard') }}">Dashboard</a> </li> <li class="nav-item"> <a class="nav-link" href="{{ route('recipient.donations.browse') }}">Browse Donations</a> </li> @elseif(Auth::user()->role == 'admin') <li class="nav-item"> <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a> </li> <li class="nav-item"> <a class="nav-link" href="{{ route('admin.users.index') }}">Manage Users</a> </li> @endif <li class="nav-item"> <form method="POST" action="{{ route('logout') }}" class="d-inline"> @csrf <button type="submit" class="nav-link btn btn-link text-danger"> Logout </button> </form> </li> @endguest </ul> </div> </div> </nav>
{{-- Flash Messages --}}
<div class="container main-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

{{-- Bootstrap JS and Dependencies --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Custom Scripts --}}
<script>
    // Auto-dismiss flash messages
    document.addEventListener('DOMContentLoaded', function() {
        const flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(message => {
            setTimeout(() => {
                message.classList.add('fade');
                setTimeout(() => message.remove(), 1000);
            }, 5000);
        });
    });
</script>

@yield('scripts')
</body> </html>

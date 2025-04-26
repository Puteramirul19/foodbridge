<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodBridge')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .sidebar {
            width: 200px;
            background-color: #f1f1f1;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .flash-message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .flash-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .flash-error {
            background-color: #f2dede;
            color: #a94442;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-danger {
            background-color: #dc3545;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        @include('partials.header')
        
        @if(session('success'))
            <div class="flash-message flash-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="flash-message flash-error">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .reports-container {
            max-width: 600px;
            margin: 50px auto;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .card-header h2 {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-header i {
            margin-right: 10px;
        }
        .form-select, .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .btn-primary {
            background-color: #2575fc;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1a5adf;
            transform: translateY(-2px);
        }
        .form-label {
            font-weight: 600;
            color: #4a5568;
        }
        .info-text {
            color: #6c757d;
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('foodbridge-icon.svg') }}" alt="FoodBridge Logo" height="40" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary me-2">
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

    <div class="container reports-container">
        <div class="card">
            <div class="card-header">
                <h2>
                    <i class="fas fa-file-alt"></i>
                    Generate Reports
                </h2>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.generate-reports') }}" method="POST">
                    @csrf
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="report_type" class="form-label">
                            <i class="fas fa-chart-pie me-2"></i>Report Type
                        </label>
                        <select name="report_type" id="report_type" class="form-select" required>
                            <option value="">Select Report Type</option>
                            <option value="users">All Users</option>
                            <option value="donations">Donations</option>
                            <option value="donors">Donors</option>
                            <option value="recipients">Recipients</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="format" class="form-label">
                            <i class="fas fa-file-export me-2"></i>Export Format
                        </label>
                        <select name="format" id="format" class="form-select" required>
                            <option value="">Select Format</option>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Start Date
                            </label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>End Date
                            </label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-download me-2"></i>Generate Report
                        </button>
                    </div>
                </form>
                
                <p class="info-text">
                    <i class="fas fa-info-circle me-2"></i>
                    Optional date range helps you filter specific time periods
                </p>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom JavaScript for Date Validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Prevent selecting end date before start date
            endDateInput.addEventListener('change', function() {
                if (startDateInput.value && this.value < startDateInput.value) {
                    alert('End date cannot be before start date');
                    this.value = '';
                }
            });

            startDateInput.addEventListener('change', function() {
                if (endDateInput.value && this.value > endDateInput.value) {
                    alert('Start date cannot be after end date');
                    this.value = '';
                }
            });
        });
    </script>
</body>
</html>
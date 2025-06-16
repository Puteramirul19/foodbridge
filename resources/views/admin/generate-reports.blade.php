<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports - FoodBridge Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reports-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border: none;
        }
        .card-header h2 {
            margin: 0;
            font-weight: 600;
        }
        .card-body {
            padding: 0;
        }
        .form-section {
            padding: 40px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .form-select, .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-select:focus, .form-control:focus {
            border-color: #2575fc;
            box-shadow: 0 0 0 0.2rem rgba(37, 117, 252, 0.25);
        }
        .report-option {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .report-option:hover {
            border-color: #2575fc;
            background-color: #f8f9ff;
        }
        .report-option.selected {
            border-color: #2575fc;
            background-color: #f0f4ff;
        }
        .report-option input[type="radio"] {
            margin-right: 15px;
            transform: scale(1.2);
        }
        .report-info {
            flex: 1;
        }
        .report-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .report-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
        }
        .report-icon {
            font-size: 2rem;
            color: #2575fc;
            margin-right: 20px;
        }
        .btn-generate {
            background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 117, 252, 0.3);
        }
        .date-inputs {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .format-badge {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="navbar-nav ms-auto">
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
                <h2 class="mb-2">
                    <i class="fas fa-file-alt me-3"></i>Generate Reports
                </h2>
                <p class="mt-3 mb-0 opacity-75">
                    Generate comprehensive reports for platform analysis
                </p>
            </div>
            
            <div class="card-body">
                <div class="form-section">
                    {{-- Error Handling --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.generate-reports') }}" method="POST">
                        @csrf
                        
                        {{-- Report Type Selection --}}
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-chart-pie me-2"></i>Select Report Type
                            </label>
                            
                            <div class="report-option" onclick="selectReport('users')">
                                <input type="radio" name="report_type" value="users" id="users" required>
                                <div class="report-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="report-info">
                                    <div class="report-title">Platform Users Overview</div>
                                    <div class="report-description">
                                        Complete list of all registered users including donors, recipients, and their registration details
                                    </div>
                                </div>
                            </div>



                            <div class="report-option" onclick="selectReport('donors')">
                                <input type="radio" name="report_type" value="donors" id="donors" required>
                                <div class="report-icon">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </div>
                                <div class="report-info">
                                    <div class="report-title">Donor Contribution Analysis</div>
                                    <div class="report-description">
                                        Comprehensive analysis of donor activities, total contributions, and impact metrics per donor
                                    </div>
                                </div>
                            </div>

                            <div class="report-option" onclick="selectReport('recipients')">
                                <input type="radio" name="report_type" value="recipients" id="recipients" required>
                                <div class="report-icon">
                                    <i class="fas fa-people-carry"></i>
                                </div>
                                <div class="report-info">
                                    <div class="report-title">Food Recipients Activity Report</div>
                                    <div class="report-description">
                                        Overview of recipient engagement, reservation patterns, and food assistance received
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Format Field (PDF Only) --}}
                        <input type="hidden" name="format" value="pdf">
                        
                        {{-- Date Range Selection --}}
                        <div class="date-inputs">
                            <h5 class="mb-3">
                                <i class="fas fa-calendar-range me-2"></i>Date Range (Optional)
                            </h5>
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
                        </div>
                        
                        {{-- Generate Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-generate">
                                <i class="fas fa-file-pdf me-2"></i>Generate PDF Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Additional Information --}}
        <div class="text-center mt-4">
            <div class="alert alert-info" style="background: rgba(255, 255, 255, 0.9);">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Report Information:</strong> All reports are generated in PDF format for easy sharing and printing. 
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            
            // Set max date to today
            const today = new Date().toISOString().split('T')[0];
            startDateInput.max = today;
            endDateInput.max = today;

            // Date range validation
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

        function selectReport(reportType) {
            // Remove selected class from all options
            document.querySelectorAll('.report-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.getElementById(reportType).checked = true;
        }

        // Add click event listeners to radio buttons to update visual selection
        document.querySelectorAll('input[name="report_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.report-option').forEach(option => {
                    option.classList.remove('selected');
                });
                this.closest('.report-option').classList.add('selected');
            });
        });
    </script>
</body>
</html>
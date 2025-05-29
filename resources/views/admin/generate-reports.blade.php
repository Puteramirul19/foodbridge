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
            max-width: 700px;
            margin: 50px auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-select, .form-control {
            border-radius: 10px;
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
        .date-info-btn {
            margin-left: 10px;
        }
        .report-type-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="40" class="me-2">
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
                <h2 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Generate Reports
                </h2>
                <span class="badge bg-light text-dark">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ now()->format('d M Y') }}
                </span>
            </div>
            
            <div class="card-body p-4">
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
                    
                    <div class="mb-3">
                        <label for="report_type" class="form-label">
                            <i class="fas fa-chart-pie me-2"></i>Report Type
                        </label>
                        <select name="report_type" id="report_type" class="form-select" required>
                            <option value="">Select Report Type</option>
                            <option value="users">
                                Users Overview
                                <small class="report-type-description">
                                    All registered platform users by role
                                </small>
                            </option>
                            <option value="donations">
                                Food Donations Summary
                                <small class="report-type-description">
                                    Details of all food donations made
                                </small>
                            </option>
                            <option value="donors">
                                Donor Contribution Report
                                <small class="report-type-description">
                                    Breakdown of donor activities and donations
                                </small>
                            </option>
                            <option value="recipients">
                                Food Recipients Report
                                <small class="report-type-description">
                                    Overview of food reservation activities
                                </small>
                            </option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="format" class="form-label">
                            <i class="fas fa-file-export me-2"></i>Export Format
                        </label>
                        <select name="format" id="format" class="form-select" required>
                            <option value="">Select Format</option>
                            <option value="csv">CSV (Spreadsheet)</option>
                            <option value="pdf">PDF (Printable Document)</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Start Date
                            </label>
                            <div class="input-group">
                                <input type="date" name="start_date" id="start_date" class="form-control">
                                <button type="button" class="btn btn-outline-secondary date-info-btn" 
                                        data-bs-toggle="modal" data-bs-target="#dateRangeModal">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>End Date
                            </label>
                            <div class="input-group">
                                <input type="date" name="end_date" id="end_date" class="form-control">
                                <button type="button" class="btn btn-outline-secondary date-info-btn" 
                                        data-bs-toggle="modal" data-bs-target="#dateRangeModal">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-download me-2"></i>Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Date Range Info Modal --}}
    <div class="modal fade" id="dateRangeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle text-primary me-2"></i>Date Range Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Date Range Guidance</h6>
                    <ul>
                        <li>Optional fields for filtering reports</li>
                        <li>Start date must be before or equal to end date</li>
                        <li>Dates cannot be in the future</li>
                        <li>Leave blank to generate a full historical report</li>
                    </ul>
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        Pro Tip: Use date ranges to analyze specific time periods or track donation trends
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Date Validation Script --}}
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
    </script>
</body>
</html>
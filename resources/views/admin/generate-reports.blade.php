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
            background: linear-gradient(135deg, #FAF0E6 0%, #F5E6D3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .reports-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .page-header .content {
            position: relative;
            z-index: 2;
        }
        
        .stats-badge {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        .reports-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .reports-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px 30px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .reports-card-title {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            font-size: 1.4rem;
        }
        
        .reports-card-body {
            padding: 30px;
        }
        
        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            color: #667eea;
        }
        
        .btn-generate {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
            width: 100%;
            font-size: 1.1rem;
        }
        
        .btn-generate:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .report-option {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 2px solid #e9ecef;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .report-option:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }
        
        .report-option.selected {
            border-color: #667eea;
            background: #f8f9ff;
            transform: translateY(-5px);
        }
        
        .report-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .report-option.selected::before,
        .report-option:hover::before {
            opacity: 1;
        }
        
        .report-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        
        .report-info {
            display: flex;
            align-items: center;
        }
        
        .report-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 20px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        
        .report-option:hover .report-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .report-details {
            flex: 1;
        }
        
        .report-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .report-description {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
            margin: 0;
        }
        
        .date-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border: 2px dashed #dee2e6;
        }
        
        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            color: #d63031;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
        }
        
        .btn-close-white {
            filter: brightness(0) invert(1);
        }
        
        @media (max-width: 768px) {
            .reports-container {
                padding: 10px;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .reports-card-body {
                padding: 20px;
            }
            
            .report-info {
                flex-direction: column;
                text-align: center;
            }
            
            .report-icon {
                margin-right: 0;
                margin-bottom: 15px;
            }
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

    <div class="container-fluid reports-container">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-3">
                        <i class="fas fa-file-alt me-3"></i>Generate Reports
                    </h1>
                    <p class="mb-0 fs-5">Generate comprehensive reports for platform analysis and insights</p>
                </div>
                <div class="stats-badge">
                    <i class="fas fa-chart-bar me-2"></i>
                    Admin Reports
                </div>
            </div>
        </div>

        {{-- Error Handling --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Reports Form --}}
        <div class="reports-card">
            <div class="reports-card-header">
                <h3 class="reports-card-title">
                    <i class="fas fa-chart-pie me-2"></i>Report Configuration
                </h3>
            </div>
            <div class="reports-card-body">
                <form action="{{ route('admin.generate-reports') }}" method="POST">
                    @csrf
                    
                    {{-- Report Type Selection - REMOVED DONATIONS OPTION --}}
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-list-alt"></i>Select Report Type
                        </label>
                        
                        <div class="report-option" onclick="selectReport('users')">
                            <input type="radio" name="report_type" value="users" id="users" required>
                            <div class="report-info">
                                <div class="report-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="report-details">
                                    <div class="report-title">Platform Users Overview</div>
                                    <div class="report-description">
                                        Complete list of all registered users
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="report-option" onclick="selectReport('donors')">
                            <input type="radio" name="report_type" value="donors" id="donors" required>
                            <div class="report-info">
                                <div class="report-icon">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </div>
                                <div class="report-details">
                                    <div class="report-title">Donor Contribution Analysis</div>
                                    <div class="report-description">
                                        Analysis of donor activities and contributions
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="report-option" onclick="selectReport('recipients')">
                            <input type="radio" name="report_type" value="recipients" id="recipients" required>
                            <div class="report-info">
                                <div class="report-icon">
                                    <i class="fas fa-people-carry"></i>
                                </div>
                                <div class="report-details">
                                    <div class="report-title">Food Recipients Activity Report</div>
                                    <div class="report-description">
                                        Overview of recipient engagement and food assistance
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden Format Field (PDF Only) --}}
                    <input type="hidden" name="format" value="pdf">
                    
                    {{-- Date Range Selection --}}
                    <div class="date-section">
                        <div class="section-title">
                            <i class="fas fa-calendar-range"></i>Date Range (Optional)
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>Start Date
                                </label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>End Date
                                </label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Generate Button --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-generate">
                            <i class="fas fa-file-pdf me-2"></i>Generate PDF Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Additional Information --}}
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Report Information:</strong> All reports are generated in PDF format for easy sharing and printing. Date filters are optional and will include all records if not specified.
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

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('show')) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                });
            }, 8000);
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
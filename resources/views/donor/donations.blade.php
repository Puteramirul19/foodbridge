<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Donations - FoodBridge</title>
    
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
        
        .donations-container {
            max-width: 1400px;
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
        
        .donations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .donation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.4s ease;
            overflow: hidden;
            position: relative;
        }
        
        .donation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .donation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .category-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .status-available {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
        }
        
        .status-reserved {
            background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
            color: white;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
        }
        
        .status-expired {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .urgency-badge {
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .card-body-custom {
            padding: 25px;
        }
        
        .card-title-custom {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .donation-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
        }
        
        .detail-icon {
            width: 20px;
            color: #667eea;
            margin-right: 10px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-action {
            flex: 1;
            border-radius: 12px;
            padding: 10px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.9rem;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        
        .btn-disabled {
            background: #6c757d;
            color: #adb5bd;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .btn-action:hover:not(.btn-disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .empty-state {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 60px 40px;
            text-align: center;
            margin-top: 30px;
        }
        
        .empty-icon {
            color: #00b894;
            margin-bottom: 20px;
        }
        
        .btn-create-first {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-create-first:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        @media (max-width: 768px) {
            .donations-grid {
                grid-template-columns: 1fr;
            }
            
            .donations-container {
                padding: 10px;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('donor.dashboard') }}">
                <img src="{{ asset('icon.png') }}" alt="FoodBridge Logo" height="30" class="me-2">
                <span class="fw-bold" style="color: #4A5568; font-size: 1.25rem;">FoodBridge</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('donor.dashboard') }}" class="btn btn-outline-primary me-2">
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

    <div class="container-fluid donations-container">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-3">
                        <i class="fas fa-heart me-3"></i>My Donations
                    </h1>
                    <p class="mb-0 fs-5">Track and manage your food donations to the community</p>
                </div>
                <div class="stats-badge">
                    <i class="fas fa-donate me-2"></i>
                    Total Donations: {{ $donations->count() }}
                </div>
            </div>
        </div>
        
        {{-- Donations Grid --}}
        @if($donations->isEmpty())
            <div class="empty-state">
                <i class="fas fa-heart fa-5x empty-icon"></i>
                <h3 class="mt-3 mb-2">No Donations Yet</h3>
                <p class="text-muted fs-5 mb-4">Start making a difference by creating your first food donation.</p>
                <p class="text-muted">Help reduce food waste and support your community today!</p>
                <a href="{{ route('donor.donations.create') }}" class="btn btn-create-first">
                    <i class="fas fa-plus me-2"></i>Create Your First Donation
                </a>
            </div>
        @else
            <div class="donations-grid">
                @foreach($donations as $donation)
                    @php
                        $isExpired = $donation->isExpired();
                        $isExpiringSoon = $donation->isExpiringSoon();
                        $showExpiryWarnings = in_array($donation->status, ['available', 'reserved']); // Only show for active donations
                    @endphp
                    
                    <div class="donation-card">
                        <div class="card-header-custom">
                            <span class="category-badge">
                                {{ ucfirst(str_replace('_', ' ', $donation->food_category)) }}
                            </span>
                            @if($showExpiryWarnings && $isExpiringSoon && !$isExpired)
                                <span class="urgency-badge">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Expiring Soon
                                </span>
                            @elseif($showExpiryWarnings && $isExpired)
                                <span class="urgency-badge" style="background: #6c757d; animation: none;">
                                    <i class="fas fa-times-circle me-1"></i>Expired
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body-custom">
                            <h5 class="card-title-custom">
                                {{ Str::limit($donation->food_description, 60) }}
                            </h5>
                            
                            <div class="donation-details">
                                <div class="detail-item">
                                    <i class="fas fa-users detail-icon"></i>
                                    <span><strong>Servings:</strong> {{ $donation->estimated_servings }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt detail-icon"></i>
                                    <span><strong>Best Before:</strong> 
                                        {{ \Carbon\Carbon::parse($donation->best_before)->format('d M Y') }}
                                        @if($showExpiryWarnings && $isExpired)
                                            <span class="text-danger fw-bold ms-2">(Expired)</span>
                                        @elseif($showExpiryWarnings && $isExpiringSoon)
                                            <span class="text-warning fw-bold ms-2">(Expires Soon)</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt detail-icon"></i>
                                    <span><strong>Location:</strong> {{ Str::limit($donation->pickup_location, 30) }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-truck detail-icon"></i>
                                    <span><strong>Type:</strong> 
                                        <span class="badge bg-info">{{ ucfirst($donation->donation_type) }}</span>
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Status Badge --}}
                            <div class="text-center mb-3">
                                <span class="status-badge status-{{ $donation->status }}">
                                    <i class="fas {{ 
                                        $donation->status == 'available' ? 'fa-check-circle' : 
                                        ($donation->status == 'reserved' ? 'fa-clock' : 
                                        ($donation->status == 'completed' ? 'fa-thumbs-up' : 'fa-times-circle'))
                                    }} me-1"></i>
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="action-buttons">
                                {{-- View Button --}}
                                <a href="{{ route('donor.donations.show', $donation) }}" 
                                   class="btn btn-action btn-view">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>

                                {{-- Edit Button --}}
                                @if($donation->canBeEdited())
                                    <a href="{{ route('donor.donations.edit', $donation) }}" 
                                       class="btn btn-action btn-edit">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                @else
                                    <button class="btn btn-action btn-disabled" 
                                            disabled 
                                            title="{{ $donation->isExpired() ? 'Cannot edit expired donation' : 'Cannot edit reserved/completed donation' }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                @endif

                                {{-- Delete Button --}}
                                @if($donation->canBeDeleted())
                                    <form action="{{ route('donor.donations.destroy', $donation) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this donation? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-action btn-disabled" 
                                            disabled 
                                            title="{{ $donation->isExpired() ? 'Cannot delete expired donation' : 'Cannot delete reserved/completed donation' }}">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Enhanced interactions --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to delete buttons
            const deleteForms = document.querySelectorAll('form[method="POST"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function() {
                    const button = this.querySelector('button[type="submit"]');
                    if (button && !button.disabled) {
                        const originalText = button.innerHTML;
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';
                        
                        // Re-enable after 3 seconds as fallback
                        setTimeout(() => {
                            button.disabled = false;
                            button.innerHTML = originalText;
                        }, 3000);
                    }
                });
            });

            // Enhance disabled button tooltips
            const disabledButtons = document.querySelectorAll('.btn-disabled');
            disabledButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const title = this.getAttribute('title');
                    if (title) {
                        alert(title);
                    }
                });
            });
        });
    </script>
</body>
</html>
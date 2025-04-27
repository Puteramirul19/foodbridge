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
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .donations-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .donations-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .donations-table {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .actions-column {
            white-space: nowrap;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
                <a href="{{ route('donor.donations.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>New Donation
                </a>
                <a href="{{ route('donor.dashboard') }}" class="btn btn-outline-primary me-2">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container donations-container">
        {{-- Donations Header --}}
        <div class="donations-header">
            <div>
                <h1 class="mb-0">My Donations</h1>
                <p class="mb-0">Track and manage your food donations</p>
            </div>
            <div>
                <span class="badge bg-light text-dark">Total Donations: {{ $donations->count() }}</span>
            </div>
        </div>

        {{-- Donations Table --}}
        @if($donations->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                <h3>No Donations Yet</h3>
                <p>You haven't created any food donations. Start helping your community!</p>
                <a href="{{ route('donor.donations.create') }}" class="btn btn-primary">
                    Create Your First Donation
                </a>
            </div>
        @else
            <div class="donations-table">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Food Category</th>
                            <th>Description</th>
                            <th>Servings</th>
                            <th>Best Before</th>
                            <th>Status</th>
                            <th class="actions-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $donation->food_category)) }}</td>
                                <td>{{ Str::limit($donation->food_description, 30) }}</td>
                                <td>{{ $donation->estimated_servings }}</td>
                                <td>{{ \Carbon\Carbon::parse($donation->best_before)->format('d M Y') }}</td>
                                <td>
                                    <span class="status-badge bg-{{ 
                                        $donation->status == 'available' ? 'success' : 
                                        ($donation->status == 'reserved' ? 'warning' : 'secondary')
                                    }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                                <td class="actions-column">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('donor.donations.edit', $donation) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('donor.donations.destroy', $donation) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this donation?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
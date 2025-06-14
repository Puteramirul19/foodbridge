<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - FoodBridge</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #F2EDE4;
            font-family: 'Arial', sans-serif;
        }
        .users-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 20px;
        }
        .page-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .table {
            margin-bottom: 0;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        .status-inactive {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.05);
            transition: background-color 0.3s ease;
        }
        .btn-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .btn-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .search-container {
            margin-bottom: 20px;
        }
        .btn-action {
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 6px;
            font-size: 0.85rem;
        }
        .modal-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border: none;
        }
        .modal-title {
            color: white;
        }
        .btn-close-white {
            filter: brightness(0) invert(1);
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

    <div class="container-fluid users-container">
        <div class="page-header">
            <div>
                <h1 class="mb-2">User Management</h1>
                <p class="mb-0">Control and Monitor Platform Users</p>
            </div>
            <div>
                <span class="badge bg-light text-dark p-2">
                    <i class="fas fa-users me-2"></i>
                    Total Users: {{ $users->where('role', '!=', 'admin')->count() }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="search-container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" 
                                   placeholder="Search users by name, email, or role">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button id="filterAll" class="btn btn-outline-secondary active">
                                    <i class="fas fa-users me-2"></i>All
                                </button>
                                <button id="filterDonors" class="btn btn-outline-secondary">
                                    <i class="fas fa-hand-holding-heart me-2"></i>Donors
                                </button>
                                <button id="filterRecipients" class="btn btn-outline-secondary">
                                    <i class="fas fa-utensils me-2"></i>Recipients
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                {{-- Skip admin users in the display --}}
                                @if($user->role !== 'admin')
                                <tr data-role="{{ $user->role }}">
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        {{ $user->phone_number ?? 'Not provided' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'donor' ? 'success' : 'primary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            {{-- View Details Button --}}
                                            <button type="button" class="btn btn-outline-info btn-action" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewUserModal"
                                                    onclick="viewUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone_number }}', '{{ $user->role }}', {{ $user->is_active ? 'true' : 'false' }}, '{{ $user->created_at->format('d M Y H:i:s') }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            {{-- Toggle Status Button --}}
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-action {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- View User Modal --}}
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel">
                        <i class="fas fa-user me-2"></i>User Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">User ID</label>
                                <p id="viewUserId" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <p id="viewUserName" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <p id="viewUserEmail" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <p id="viewUserPhone" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <p id="viewUserRole" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Account Status</label>
                                <p id="viewUserStatus" class="form-control-plaintext"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Registration Date</label>
                                <p id="viewUserRegistered" class="form-control-plaintext"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterButtons = document.querySelectorAll('#filterAll, #filterDonors, #filterRecipients');
            const rows = document.querySelectorAll('#usersTable tbody tr');

            // Search functionality
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const role = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                    
                    const isVisible = name.includes(searchTerm) || 
                                      email.includes(searchTerm) || 
                                      role.includes(searchTerm);
                    
                    row.style.display = isVisible ? '' : 'none';
                });
            });

            // Role Filtering
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const filterRole = this.id === 'filterAll' ? '' : 
                                       (this.id === 'filterDonors' ? 'donor' : 'recipient');
                    
                    rows.forEach(row => {
                        if (filterRole === '' || row.dataset.role === filterRole) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });

        // View User Modal
        function viewUser(id, name, email, phone, role, isActive, registered) {
            document.getElementById('viewUserId').textContent = id;
            document.getElementById('viewUserName').textContent = name;
            document.getElementById('viewUserEmail').textContent = email;
            document.getElementById('viewUserPhone').textContent = phone || 'Not provided';
            document.getElementById('viewUserRole').innerHTML = `<span class="badge bg-${role === 'donor' ? 'success' : 'primary'}">${role.charAt(0).toUpperCase() + role.slice(1)}</span>`;
            document.getElementById('viewUserStatus').innerHTML = `<span class="badge bg-${isActive ? 'success' : 'danger'}">${isActive ? 'Active' : 'Inactive'}</span>`;
            document.getElementById('viewUserRegistered').textContent = registered;
        }
    </script>
</body>
</html>
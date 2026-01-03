
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Staff Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        
        .main-content { margin-left: 250px; padding: 40px; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .staff-card { 
            background: #fff; 
            border-radius: 15px; 
            padding: 20px; 
            margin-bottom: 20px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); 
        }
        
        .staff-info { flex-grow: 1; }
        
        .action-btn { 
            background: #eee; 
            border: none; 
            padding: 8px 12px; 
            border-radius: 8px; 
            margin-left: 10px; 
            transition: 0.3s; 
            cursor: pointer; 
        }
        .action-btn:hover { background: #e0e0e0; }
        .action-btn.delete:hover { background: #ffebee; color: #d32f2f; }
        
        .tab-menu { border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .tab-link { padding: 10px 20px; text-decoration: none; color: #666; display: inline-block; }
        .tab-link.active { color: #000; border-bottom: 2px solid #000; font-weight: 600; }
        
        .alert-floating { 
            margin-bottom: 25px; 
            border-radius: 10px; 
            border: none; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        }
        
        .activity-log { 
            max-height: 300px; 
            overflow-y: auto; 
            border: 1px solid #eee; 
            border-radius: 10px; 
            padding: 15px;
        }
        .activity-item { 
            padding: 10px 0; 
            border-bottom: 1px solid #f0f0f0; 
        }
        .activity-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
        <div id="profile">
            <div id="profile-container">
                <img id="pfp" src="{{ asset('img/racc_icon.png') }}">
                <div id="profile-dropdown">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
            @auth
                <span id="username">{{ Auth::user()->name }}</span>
            @endauth
        </div>
    </div>

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item active">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
        <a href="#" class="nav-item">Settings</a>
    </div>

    <div class="main-content">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-floating" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="header-flex">
            <h2>Staff Management</h2>
            <a href="{{ route('admin.staff.create')}}" class="btn btn-success" style="border-radius: 20px; background-color: #1a8f36; text-decoration: none;">
                + Add New Staff
            </a>
        </div>

        <div class="tab-menu">
            <span class="float-end text-muted">Total <b>{{ $staffs->count() }}</b></span>
        </div>

        @foreach($staffs as $staff)
            <div class="staff-card">
                <div class="staff-info">
                    <h4 class="mb-0">{{ $staff->name }}</h4>
                    <p class="text-muted mb-0">{{ $staff->email }}</p>
                    <p class="mb-0">
                        <span class="badge bg-secondary">{{ $staff->position }}</span>
                        <small class="text-muted ms-2">ID: {{ $staff->userID }}</small>
                    </p>
                </div>

                <div class="d-flex">
                    {{-- 👁️ VIEW PROFILE BUTTON --}}
                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#profileModal{{ $staff->userID }}">👁️</button>
                    
                    {{-- 📝 EDIT BUTTON --}}
                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#editModal{{ $staff->userID }}">📝</button>

                    {{-- 🗑️ DELETE FORM --}}
                    <form action="{{ route('admin.staff.destroy', $staff->userID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete">🗑️</button>
                    </form>
                </div>
            </div>

            <!-- Profile Modal -->
            <div class="modal fade" id="profileModal{{ $staff->userID }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Staff Profile: {{ $staff->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Personal Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Name:</th>
                                    <td>{{ $staff->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $staff->email }}</td>
                                </tr>
                                <tr>
                                    <th>IC Number:</th>
                                    <td>{{ $staff->icNumber }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $staff->phoneNumber }}</td>
                                </tr>
                                <tr>
                                    <th>Position:</th>
                                    <td>{{ $staff->position }}</td>
                                </tr>
                                <tr>
                                    <th>Commission Count:</th>
                                    <td>{{ $staff->commissionCount }}</td>
                                </tr>
                                <tr>
                                    <th>Member Since:</th>
                                    <td>{{ \Carbon\Carbon::parse($staff->created_at)->format('d M Y') }}</td>
                                </tr>
                            </table>
                            
                            <h6 class="mt-4">Recent Activity</h6>
                            <div class="activity-log">
                                <p class="text-muted text-center">Activity logs will appear here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $staff->userID }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.staff.update', $staff->userID) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Staff: {{ $staff->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <input type="text" name="position" class="form-control" value="{{ $staff->position }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phoneNumber" class="form-control" value="{{ $staff->phoneNumber }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Commission Count</label>
                                <input type="number" name="commissionCount" class="form-control" value="{{ $staff->commissionCount }}" min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="background-color: #bc3737; border:none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
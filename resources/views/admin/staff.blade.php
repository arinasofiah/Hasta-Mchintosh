<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Staff Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
        
        .commission-badge {
            background: #e7f4e4;
            color: #2e7d32;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .commission-amount {
            color: #1a8f36;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .commission-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .commission-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .commission-list li:last-child {
            border-bottom: none;
        }
        
        .commission-type {
            font-weight: 500;
            color: #333;
        }
        
        .commission-status {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .commission-date {
            font-size: 12px;
            color: #666;
        }
        
        .no-commissions {
            text-align: center;
            padding: 30px;
            color: #999;
        }
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
                    <h4 class="mb-2">{{ $staff->name }}</h4>
                    <div class="text-muted small mt-3">
                        <p class="mb-1">Email: {{ $staff->email }}</p>
                        <p class="mb-0">Phone: {{ $staff->phoneNumber ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                        {{-- 👤 VIEW PROFILE BUTTON --}}
                        <button type="button" class="action-btn view" data-bs-toggle="modal" data-bs-target="#profileModal{{ $staff->userID }}" title="View Profile">
                            <i class="fas fa-user"></i>
                        </button>
                        
                        {{-- 📝 EDIT BUTTON --}}
                        <button type="button" class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $staff->userID }}" title="Edit Staff">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- 🗑️ DELETE FORM --}}
                        <form action="{{ route('admin.staff.destroy', $staff->userID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Delete Staff">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Modal -->
            <div class="modal fade" id="profileModal{{ $staff->userID }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Staff Profile: {{ $staff->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
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
                                            <th>Member Since:</th>
                                            <td>{{ \Carbon\Carbon::parse($staff->created_at)->format('d M Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div style="background: #f0f9ff; border: 2px solid #1a8f36; border-radius: 10px; padding: 20px;">
                                        <h6 class="mb-3">Commission Summary</h6>
                                        <h3 class="commission-amount mb-3">RM {{ number_format($staff->commissionCount ?? 0, 2) }}</h3>
                                        <p class="text-muted small mb-0">Total Commission Approved</p>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="mt-4">Commission Activities</h6>
                            <div class="activity-log">
                                @if($staff->commissions && $staff->commissions->count() > 0)
                                    <ul class="commission-list">
                                        @foreach($staff->commissions as $commission)
                                            <li>
                                                <div>
                                                    <div class="commission-type">
                                                        {{ $commission->commissionType }}
                                                        @if($commission->description)
                                                            <br><small class="text-muted">{{ $commission->description }}</small>
                                                        @endif
                                                    </div>
                                                    <div class="commission-date">
                                                        Submitted: {{ \Carbon\Carbon::parse($commission->created_at)->format('d M Y') }}
                                                        @if($commission->amount)
                                                            <br>Amount: RM {{ number_format($commission->amount, 2) }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="commission-status status-{{ strtolower($commission->status) }}">
                                                        {{ ucfirst($commission->status) }}
                                                    </span>
                                                    @if($commission->status == 'approved' && $commission->approvedAmount)
                                                        <div class="commission-amount small mt-1">
                                                            RM {{ number_format($commission->approvedAmount, 2) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="no-commissions">
                                        <p>No commission submissions yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal - Updated to include CommissionCount -->
            <div class="modal fade" id="editModal{{ $staff->userID }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.staff.update', $staff->userID) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Staff: {{ $staff->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phoneNumber" class="form-control" value="{{ $staff->phoneNumber }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Commission Count (RM)</label>
                                        <input type="number" name="commissionCount" class="form-control" value="{{ $staff->commissionCount ?? 0 }}" step="0.01" min="0" required>
                                        <small class="text-muted">Total approved commission amount</small>
                                    </div>
                                </div>
                            </div>
                            
                            @if($staff->commissions && $staff->commissions->where('status', 'pending')->count() > 0)
                                <hr>
                                <h6>Pending Commission Approvals</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount Claimed</th>
                                                <th>Submitted</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($staff->commissions->where('status', 'pending') as $commission)
                                                <tr>
                                                    <td>{{ $commission->commissionType }}</td>
                                                    <td>{{ $commission->description }}</td>
                                                    <td>RM {{ number_format($commission->amount, 2) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($commission->created_at)->format('d M Y') }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-success btn-sm" onclick="approveCommission('{{ $commission->id }}', {{ $commission->amount }})">Approve</button>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="rejectCommission('{{ $commission->id }}')">Reject</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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
    <script>
        function approveCommission(commissionId, amount) {
            if (confirm('Approve this commission claim of RM ' + amount.toFixed(2) + '?')) {
                // AJAX call to approve commission
                fetch('/admin/commission/' + commissionId + '/approve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ amount: amount })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Commission approved successfully!');
                        location.reload();
                    }
                });
            }
        }
        
        function rejectCommission(commissionId) {
            if (confirm('Reject this commission claim?')) {
                // AJAX call to reject commission
                fetch('/admin/commission/' + commissionId + '/reject', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Commission rejected!');
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Booking Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/admin-header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .main-content { margin-left: 250px; padding: 40px; }
        
        .nav-tabs { border-bottom: 2px solid #eee; margin-bottom: 30px; }
        .nav-tabs .nav-link { border: none; color: #999; font-weight: 500; padding: 15px 25px; font-size: 1.1rem; }
        .nav-tabs .nav-link.active { color: #bc3737; border-bottom: 3px solid #bc3737; background: transparent; }

        .table-card { background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .status-badge { padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
        .bg-green { background-color: #dcfce7; color: #1a8f36; }
        .bg-yellow { background-color: #fff3cd; color: #856404; }
        .bg-red { background-color: #ffebee; color: #c62828; }
        
        .action-btn { text-decoration: none; font-weight: 600; color: #bc3737; }
        .action-btn:hover { text-decoration: underline; color: #8b2525; }
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
            @auth <span id="username">{{ Auth::user()->name }}</span> @endauth
        </div>
    </div>

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('admin.bookings') }}" class="nav-item active">Bookings</a> 
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
    </div>

    <div class="main-content">
        <h2 class="fw-bold mb-4">Booking Management</h2>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <ul class="nav nav-tabs" id="bookingTabs" role="tablist">
            <li class="nav-item"><button class="nav-link" id="approval-tab" data-bs-toggle="tab" data-bs-target="#approval-pane">Pending Approval</button></li>
            <li class="nav-item"><button class="nav-link" id="pickup-tab" data-bs-toggle="tab" data-bs-target="#pickup-pane">Pickups</button></li>
            <li class="nav-item"><button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return-pane">Returns</button></li>
            <li class="nav-item"><button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-pane">History</button></li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane fade" id="approval-pane">
                <div class="table-card">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>ID</th><th>Customer</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($pendingApprovals as $booking)
                            <tr>
                                <td>#{{ $booking->bookingID }}</td>
                                <td>{{ $booking->customer->name ?? 'Unknown' }}</td>
                                <td>
                                    @if($booking->bookingStatus == 'paid')
                                        <span class="status-badge bg-green">Paid</span>
                                    @else
                                        <span class="status-badge bg-yellow">{{ ucfirst($booking->bookingStatus) }}</span>
                                    @endif
                                </td>
                                <td><a href="{{ route('admin.bookings.show', $booking->bookingID) }}" class="action-btn">Details</a></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.bookings.approve', $booking->bookingID) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm rounded-pill px-3" onclick="return confirm('Approve this booking?')">
                                                Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.bookings.reject', $booking->bookingID) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Reject this booking?')">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                </tr>
                            @empty <tr><td colspan="4" class="text-center p-4 text-muted">No pending approvals.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pickup-pane">
                <div class="table-card">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>ID</th><th>Pickup Date</th><th>Customer</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($upcomingPickups as $booking)
                            <tr>
                                <td>#{{ $booking->bookingID }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M Y, h:i A') }}</td>
                                <td>{{ $booking->customer->name ?? 'Unknown' }}</td>
                                <td><a href="{{ route('admin.bookings.show', $booking->bookingID) }}" class="action-btn">Details</a></td>
                            </tr>
                            @empty <tr><td colspan="4" class="text-center p-4 text-muted">No upcoming pickups.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="return-pane">
                <div class="table-card">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>ID</th><th>Return Date</th><th>Customer</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($pendingReturns as $booking)
                            <tr>
                                <td>#{{ $booking->bookingID }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M Y') }}</td>
                                <td>{{ $booking->customer->name ?? 'Unknown' }}</td>
                                <td>
                                    <form action="{{ route('admin.bookings.complete', $booking->bookingID) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-warning btn-sm rounded-pill px-3" onclick="return confirm('Confirm vehicle returned?')">Confirm Return</button>
                                    </form>
                                </td>
                            </tr>
                            @empty <tr><td colspan="4" class="text-center p-4 text-muted">No pending returns.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="history-pane">
                 <div class="table-card">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>ID</th><th>Date</th><th>Customer</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($bookingHistory as $booking)
                            <tr>
                                <td>#{{ $booking->bookingID }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $booking->customer->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="status-badge {{ $booking->bookingStatus == 'completed' ? 'bg-green' : 'bg-red' }}">
                                        {{ ucfirst($booking->bookingStatus) }}
                                    </span>
                                </td>
                                <td><a href="{{ route('admin.bookings.show', $booking->bookingID) }}" class="action-btn">Details</a></td>
                            </tr>
                            @empty <tr><td colspan="5" class="text-center p-4 text-muted">No history found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $bookingHistory->links() }}</div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
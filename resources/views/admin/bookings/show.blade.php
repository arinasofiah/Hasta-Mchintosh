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

<div class="container mt-4">
    <a href="{{ route('admin.bookings') }}" class="back-link">
        <span style="font-size: 20px; margin-right: 8px;">&larr;</span> Back to List
    </a>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Booking Details #{{ $booking->bookingID }}</span>
                    <span class="badge bg-light text-dark">{{ $bookings->bookingStatus ?? 'Pending' }}</span>
                </div>
                <div class="card-body p-4">
                    
                    <h5 class="text-muted mb-3">Customer Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="label">Name</div>
                            <div class="value">{{ $booking->customer->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="label">Email</div>
                            <div class="value">{{ $booking->customer->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <hr>

                    <h5 class="text-muted mb-3 mt-3">Rental Details</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="label">Pick-up Date</div>
                            <div class="value">{{ $booking->pickupDate }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="label">Return Date</div>
                            <div class="value">{{ $booking->returnDate }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="label">Total Price</div>
                            <div class="value" style="font-size: 18px; color: #E85D04; font-weight: 700;">
                                RM {{ number_format($booking->totalPrice, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">

                            <form action="{{ route('admin.bookings.reject', $booking->bookingID) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to REJECT this booking?')">
                                    Reject
                                </button>
                            </form>

                            <form action="{{ route('admin.bookings.approve', $booking->bookingID) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-approve" onclick="return confirm('Are you sure you want to approve this booking?')">
                                    Approve Booking
                                </button>
                            </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
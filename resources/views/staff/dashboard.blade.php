<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Staff - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<style>
    .big-action-card {
        background: white;
        border-radius: 12px;
        padding: 25px 15px; 
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center; 
        position: relative;
        text-decoration: none;
        color: #333; 
        border: 1px solid #eee;
    }

    .big-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        color: #bc3737; 
        border-color: #bc3737;
    }

    .card-text {
        font-size: 1.25rem;   
        font-weight: 600;  
        line-height: 1.3;
        margin: 0;
    }

    .notification-badge {
        position: absolute; 
        top: -8px;
        right: -8px;
        background-color: #bc3737;
        color: white;
        width: 35px;  
        height: 35px;
        border-radius: 50%; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 13px;
        box-shadow: 0 4px 8px rgba(188, 55, 55, 0.3);
        border: 3px solid #f4f7f6; 
    }
</style>

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
    
    <nav class="d-flex flex-column">
        <a href="{{ route('staff.dashboard') }}" class="nav-item active">Handle Booking</a>
        <a href="{{ route('staff.commission') }}" class="nav-item">Commissions</a>
    </nav>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-5" style="color: #333;">Handle Booking</h2>

    <div class="container-fluid p-0">
        <div class="row g-4"> <div class="col-md-6">
                <a href="{{ route('staff.booking.confirmation') }}" class="big-action-card">
                    <h3 class="card-text">Booking<br>Confirmation</h3>
                    @if(isset($pendingBookings) && $pendingBookings > 0)
                        <div class="notification-badge">{{ $pendingBookings }}</div>
                    @endif
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('staff.payment.verify') }}" class="big-action-card">
                    <h3 class="card-text">Verify<br>Payment</h3>
                    @if(isset($pendingPayments) && $pendingPayments > 0)
                        <div class="notification-badge">{{ $pendingPayments }}</div>
                    @endif
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('staff.vehicle.pickup') }}" class="big-action-card">
                    <h3 class="card-text">View Pick Up<br>Vehicle</h3>
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('staff.vehicle.return') }}" class="big-action-card">
                    <h3 class="card-text">Verify Return<br>Vehicle</h3>
                    @if(isset($pendingReturns) && $pendingReturns > 0)
                        <div class="notification-badge">{{ $pendingReturns }}</div>
                    @endif
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('staff.booking.history') }}" class="big-action-card">
                    <h3 class="card-text">Booking<br>History</h3>
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('staff.vehicle.status') }}" class="big-action-card">
                    <h3 class="card-text">Update Vehicle<br>Status</h3>
                </a>
            </div>

        </div>
    </div>
</div>


</body>
</html>
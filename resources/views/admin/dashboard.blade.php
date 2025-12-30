<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Admin - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
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
    
    <nav class="d-flex flex-column">
        <a href="{{ route('admin.dashboard') }}" class="nav-item active"> Dashboard</a>
        <a href="#" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item"> Fleet</a> 
        <a href="#" class="nav-item">Customer</a>
        <a href="#" class="nav-item">Staff</a>
        <a href="#" class="nav-item"> Promotions</a>
        <a href="#" class="nav-item"> Settings</a>
    </nav>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Admin Dashboard</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box bg-light text-dark">🚗</div>
                <small class="text-muted">Total Fleet</small>
                <h2 class="fw-bold mb-0">{{ $totalVehicles ?? 0 }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box" style="background: #e6fffa; color: #38b2ac;">✅</div>
                <small class="text-muted">Available</small>
                <h2 class="fw-bold mb-0">{{ $availableCount ?? 0}}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box" style="background: #ebf8ff; color: #4299e1;">🔑</div>
                <small class="text-muted">Active Rentals</small>
                <h2 class="fw-bold mb-0">{{ $onRentCount ?? 0 }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box" style="background: #fff5f5; color: #f56565;">🛠️</div>
                <small class="text-muted">In Maintenance</small>
                <h2 class="fw-bold mb-0">{{ $maintenanceCount ?? 0}}</h2>
            </div>
        </div>
    </div>


</body>
</html>
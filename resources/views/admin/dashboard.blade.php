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
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
        .sidebar { width: 260px; height: 100vh; background: #fff; position: fixed; border-right: 1px solid #e0e0e0; padding-top: 20px; }
        .nav-item { padding: 12px 25px; color: #666; text-decoration: none; display: flex; align-items: center; border-left: 4px solid transparent; }
        
        /* Highlighted Dashboard Item */
        .nav-item.active { background: #fff5f5; color: #bc3737; font-weight: 600; border-left: 4px solid #bc3737; }
        
        .main-content { margin-left: 260px; padding: 40px; }
        .stat-card { background: #fff; border-radius: 15px; padding: 25px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 15px; }
        
        .recent-table { background: #fff; border-radius: 15px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
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
    
    <nav class="d-flex flex-column">
        <a href="{{ route('admin.dashboard') }}" class="nav-item active">📊 Dashboard</a>
        <a href="#" class="nav-item">📈 Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">🚗 Vehicles</a>
        <a href="#" class="nav-item">👥 Customer</a>
        <a href="#" class="nav-item">👔 Staff</a>
        <a href="#" class="nav-item">⚙️ Settings</a>
    </nav>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Admin Dashboard</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box bg-light text-dark">🚗</div>
                <small class="text-muted">Total Fleet</small>
                <h2 class="fw-bold mb-0">{{ $totalVehicles ?? 0}}</h2>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Admin - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    
    <style>
        .utilization-card {
            background: linear-gradient(135deg, #bc3737 0%, #8b2525 100%);
            color: white !important;
            height: 100%;
        }
        .utilization-card .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        .progress {
            height: 12px; 
            background: rgba(255,255,255,0.2); 
            border-radius: 10px;
        }
        .progress-bar {
            background: white;
            border-radius: 10px;
        }
        .chart-container {
            height: 220px;
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
    <a href="{{ route('admin.dashboard') }}" class="nav-item active">Dashboard</a>
    <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
    <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
    <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
    <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
    <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
    <a href="#" class="nav-item">Settings</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Admin Dashboard</h2>
        <span class="text-muted">{{ now()->format('D, d M Y') }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-light text-dark">
                        <i class="fas fa-car-side"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success">Total</span>
                </div>
                <small class="text-muted d-block">Total Fleet</small>
                <h2 class="fw-bold mb-0">{{ $totalVehicles ?? 0 }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box" style="background: #e6fffa; color: #38b2ac;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <small class="text-muted d-block">Available Now</small>
                <h2 class="fw-bold mb-0">{{ $availableCount ?? 0 }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box" style="background: #ebf8ff; color: #4299e1;">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                <small class="text-muted d-block">Active Rentals</small>
                <h2 class="fw-bold mb-0">{{ $onRentCount ?? 0 }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box" style="background: #fff5f5; color: #f56565;">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <small class="text-muted d-block">In Maintenance</small>
                <h2 class="fw-bold mb-0">{{ $maintenanceCount ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-7">
            <div class="stat-card utilization-card h-100">
                <h5 class="fw-bold mb-1">Fleet Utilization Efficiency</h5>
                <p class="text-white-50 mb-4">Current revenue-generating capacity.</p>
                
                @php $rate = ($totalVehicles > 0) ? ($onRentCount / $totalVehicles) * 100 : 0; @endphp
                <h1 class="fw-bold mb-2">{{ number_format($rate, 1) }}%</h1>
                
                <div class="progress mb-3">
                    <div class="progress-bar shadow-sm" role="progressbar" style="width: {{ $rate }}%;"></div>
                </div>
                <small class="text-white-50">{{ $onRentCount }} out of {{ $totalVehicles }} cars are currently on the road.</small>
            </div>
        </div>

        <div class="col-md-5">
            <div class="stat-card h-100">
                <h5 class="fw-bold mb-3">Top Models in Use</h5>
                <div class="chart-container">
                    <canvas id="fleetUsageChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('fleetUsageChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($usageData->pluck('model')),
                datasets: [{
                    label: 'Active Rentals',
                    data: @json($usageData->pluck('count')),
                    backgroundColor: [
                        '#bc3737', // Brand Red
                        '#38b2ac', // Teal
                        '#4299e1', // Blue
                        '#ecc94b', // Yellow
                        '#9f7aea'  // Purple
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            font: { 
                                family: 'Poppins', 
                                size: 11 
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta User - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/admin-header.css') }}" rel="stylesheet">
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

        .feedback-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .feedback-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .feedback-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        .feedback-scroll::-webkit-scrollbar-thumb:hover {
            background: #bc3737;
        }
        .feedback-card {
            transition: background 0.3s ease;
        }
        .feedback-card:hover {
            background: #fff !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .italic {
            font-style: italic;
        }
        
        /* New styles for available vehicles */
        .available-vehicles-card {
            height: 100%;
        }
        .vehicle-list-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            border: 1px solid #e9ecef;
        }
        .vehicle-list-item:hover {
            background: white;
            border-color: #bc3737;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(188, 55, 55, 0.1);
            text-decoration: none;
            color: inherit;
        }
        .vehicle-image {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
            background: #e9ecef;
        }
        .vehicle-info h6 {
            margin-bottom: 2px;
            font-weight: 600;
        }
        .vehicle-info small {
            color: #6c757d;
        }
        .view-all-btn {
            text-decoration: none;
            color: #bc3737;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        .view-all-btn:hover {
            color: #8b2525;
            text-decoration: underline;
        }
        .no-vehicles {
            padding: 40px 20px;
            text-align: center;
            color: #6c757d;
        }
        .vehicle-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: auto;
        }
        .available-badge {
            background: #c1f2c7;
            color: #2e7d32;
        }
        .reserved-badge {
            background: #fff3cd;
            color: #856404;
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
                 @if(Auth::user()->userType === 'staff')
                          <a href="{{ route('admin.profile') }}" class="dropdown-item">My Profile</a>
                        @endif
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
    <a href="{{ route('admin.bookings') }}" class="nav-item ">Bookings</a>
    <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
    <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
    <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
    @if(Auth::user()->userType === 'admin')
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
    @endif
    <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Dashboard</h2>
        <span class="text-muted">{{ now()->format('D, d M Y') }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">



    <div class="row g-4 mb-5">
        <!-- Available Vehicles Column (NEW) -->
        <div class="col-md-5">
            <div class="stat-card available-vehicles-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-car text-success me-2"></i> Available Vehicles
                    </h5>
                    <span class="badge bg-light text-dark">{{ $availableVehicles->count() }} Ready</span>
                </div>
                
                @if($availableVehicles->count() > 0)
                    <div class="vehicle-list-scroll" style="max-height: 250px; overflow-y: auto; padding-right: 10px;">
                        @foreach($availableVehicles->take(8) as $vehicle)
                            <a href="{{ route('admin.fleet') }}?status=available" class="vehicle-list-item">
                                @if($vehicle->vehiclePhoto)
                                    <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" class="vehicle-image" alt="{{ $vehicle->model }}">
                                @else
                                    <div class="vehicle-image d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-car text-muted"></i>
                                    </div>
                                @endif
                                <div class="vehicle-info">
                                    <h6 class="mb-0">{{ $vehicle->model }}</h6>
                                    <small>{{ $vehicle->plateNumber }} • {{ $vehicle->vehicleType }}</small>
                                </div>
                                <span class="vehicle-badge {{ $vehicle->status == 'reserved' ? 'reserved-badge' : 'available-badge' }}">
                                    {{ $vehicle->status == 'reserved' ? 'Reserved' : 'Available' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                    
                    @if($availableVehicles->count() > 8)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.fleet') }}?status=available" class="view-all-btn">
                                View all {{ $availableVehicles->count() }} vehicles
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="no-vehicles">
                        <i class="fas fa-car fa-3x text-light mb-3"></i>
                        <p class="mb-2">No vehicles available at the moment</p>
                        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Add Vehicle
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Utilization Efficiency Column -->
        <div class="col-md-7">
            <div class="stat-card utilization-card h-100">
                <h5 class="fw-bold mb-1">Fleet Utilization Efficiency</h5>
                <p class="text-white-50 mb-4">Current revenue-generating capacity.</p>
                
                @php 
                    $availableForRent = $availableCount + ($reservedCount ?? 0);
                    $totalRentable = $totalVehicles - $maintenanceCount;
                    $rate = ($totalRentable > 0) ? ($onRentCount / $totalRentable) * 100 : 0;
                @endphp
                <h1 class="fw-bold mb-2">{{ number_format($rate, 1) }}%</h1>
                
                <div class="progress mb-3">
                    <div class="progress-bar shadow-sm" role="progressbar" style="width: {{ $rate }}%;"></div>
                </div>
                <small class="text-white-50">
                    {{ $onRentCount }} out of {{ $totalRentable }} rentable cars are generating revenue.
                    @if($availableForRent > 0)
                        <br>{{ $availableForRent }} vehicles ready for booking.
                    @endif
                </small>
            </div>
        </div>
    </div>
    
    
    <!-- Customer Feedback -->
    <div class="row mt-4 mb-5">
        <div class="col-12">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-comments text-danger me-2"></i> Recent Customer Feedback
                    </h5>
                    <span class="badge bg-light text-dark">{{ $feedback->count() }} New Reviews</span>
                </div>

                <div class="feedback-scroll" style="max-height: 350px; overflow-y: auto; padding-right: 10px;">
                    @forelse($feedback as $feedbackItem)
                        <div class="feedback-card p-3 mb-3 border-start border-4 border-danger bg-light rounded-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0 text-dark">{{ $feedbackItem->name }}</h6>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> 
                                    {{ date('d M Y', strtotime($feedbackItem->returnDate)) }}
                                </small>
                            </div>
                            <p class="mb-0 text-secondary italic">
                                "{{ $feedbackItem->feedback }}"
                            </p>
                            <div class="mt-2">
                                <small class="text-uppercase fw-bold" style="font-size: 10px; color: #bc3737;">
                                    Return ID: #{{ $feedbackItem->returnID }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-comment-slash fa-3x text-light mb-3"></i>
                            <p class="text-muted">No feedback found in the database.</p>
                        </div>
                    @endforelse
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
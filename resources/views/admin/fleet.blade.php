<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Fleet Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        
        .main-content { margin-left: 250px; padding: 40px; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .vehicle-card { background: #fff; border-radius: 15px; padding: 20px; margin-bottom: 20px; display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); position: relative; }
        .vehicle-img { width: 150px; height: 100px; object-fit: contain; margin-right: 30px; }
        .vehicle-info { flex-grow: 1; }
        .status-badge { padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
        .status-available { background: #c1f2c7; color: #2e7d32; }
        .status-rented { background: #e3f2fd; color: #1565c0; }
        .status-maintenance { background: #ffebee; color: #c62828; }
        .status-reserved { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        
        .action-btn { background: #eee; border: none; padding: 8px 12px; border-radius: 8px; margin-left: 10px; transition: 0.3s; cursor: pointer; }
        .action-btn:hover { background: #e0e0e0; }
        .action-btn.delete:hover { background: #ffebee; color: #d32f2f; }
        
        .tab-menu { border-bottom: 1px solid #eee; margin-bottom: 20px; position: relative; }
        .tab-link { padding: 10px 20px; text-decoration: none; color: #666; display: inline-block; }
        .tab-link.active { color: #000; border-bottom: 2px solid #000; font-weight: 600; }
        
        .stats-counter { position: absolute; right: 0; top: 10px; }
        
        .alert-floating { margin-bottom: 25px; border-radius: 10px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        
        /* Status count badges */
        .count-badge { 
            font-size: 0.75rem; 
            padding: 3px 8px; 
            border-radius: 10px; 
            margin-left: 5px; 
            background: #f0f0f0; 
            color: #666; 
        }
        .available-count { background: #c1f2c7; color: #2e7d32; }
        .rented-count { background: #e3f2fd; color: #1565c0; }
        .maintenance-count { background: #ffebee; color: #c62828; }
        .reserved-count { background: #fff3cd; color: #856404; }
        
        /* Booking info */
        .booking-info { font-size: 0.85rem; margin-top: 5px; }
        .booking-info .text-warning { color: #d39e00 !important; }
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
        <a href="{{ route('admin.fleet') }}" class="nav-item active">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
    </div>

    <div class="main-content">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-floating" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="header-flex">
            <h2>Vehicles Management</h2>
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-success" style="border-radius: 20px; background-color: #1a8f36;">
                + Add New 
            </a>
        </div>

        <div class="tab-menu">
            <a href="?status=available" class="tab-link {{ $status == 'available' ? 'active' : '' }}">
                Available 
                <span class="count-badge available-count">{{ $availableCount }}</span>
              
            </a>
            <a href="?status=rented" class="tab-link {{ $status == 'rented' ? 'active' : '' }}">
                On Rent 
                <span class="count-badge rented-count">{{ $onRentCount }}</span>
            </a>
            <a href="?status=maintenance" class="tab-link {{ $status == 'maintenance' ? 'active' : '' }}">
                Inactive 
                <span class="count-badge maintenance-count">{{ $maintenanceCount }}</span>
            </a>
            
            <div class="stats-counter">
                Total <b>{{ $totalCount }}</b>
            </div>
        </div>

        @if($vehicles->count() > 0)
            @foreach($vehicles as $vehicle)
                <div class="vehicle-card">
                    @if($vehicle->vehiclePhoto)
                        <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" class="vehicle-img" alt="Car">
                    @else
                        <img src="{{ asset('img/vehicles/' . $vehicle->vehicleID . '.png') }}" class="vehicle-img" alt="Car" 
                            onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.png') }}'">
                    @endif
                    
                    <div class="vehicle-info">
                        <h4 class="mb-1">{{ $vehicle->model }}</h4>
                        <p class="text-muted mb-1">
                            {{ $vehicle->vehicleType }} • 
                            {{ $vehicle->plateNumber }} 
                        </p>
                        
                        {{-- Show booking info if reserved --}}
                        @if($vehicle->status == 'reserved')
                            @php
                                // Get the upcoming booking for this vehicle
                                $upcomingBooking = DB::table('booking')
                                    ->where('vehicleID', $vehicle->vehicleID)
                                    ->whereIn('bookingStatus', ['confirmed', 'approved'])
                                    ->where('startDate', '>', now())
                                    ->orderBy('startDate', 'asc')
                                    ->first();
                            @endphp
                            @if($upcomingBooking)
                                <div class="booking-info">
                                    <small class="text-warning">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Reserved from {{ \Carbon\Carbon::parse($upcomingBooking->startDate)->format('M d, Y') }}
                                        @if($upcomingBooking->customerID)
                                            @php
                                                $customer = DB::table('users')->where('userID', $upcomingBooking->customerID)->first();
                                            @endphp
                                            @if($customer)
                                                for {{ $customer->name }}
                                            @endif
                                        @endif
                                    </small>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div>
                        @if($vehicle->status == 'reserved')
                            <span class="status-badge status-reserved">
                                <i class="fas fa-clock me-1"></i> Reserved
                            </span>
                        @else
                            <span class="status-badge status-{{ $vehicle->status }}">
                                @if($vehicle->status == 'available')
                                    Available
                                @elseif($vehicle->status == 'rented')
                                    On Rent
                                @else
                                    {{ ucfirst($vehicle->status) }}
                                @endif
                            </span>
                        @endif
                    </div>

                    <div class="ms-5 d-flex">
                        {{-- 📝 EDIT BUTTON --}}
                        <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#editModal{{ $vehicle->vehicleID }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- 🗑️ DELETE FORM --}}
                        <form action="{{ route('admin.vehicles.destroy', $vehicle->vehicleID) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete {{ $vehicle->model }} ({{ $vehicle->plateNumber }})?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>

                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editModal{{ $vehicle->vehicleID }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.vehicles.update', $vehicle->vehicleID) }}" method="POST" class="modal-content" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Vehicle: {{ $vehicle->model }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Vehicle Photo</label>
                                        <input type="file" name="vehiclePhoto" class="form-control" accept="image/*">
                                        @if($vehicle->vehiclePhoto)
                                            <div class="mt-2">
                                                <small>Current photo:</small><br>
                                                <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" alt="Current photo" style="max-width: 200px; max-height: 150px; border-radius: 5px;">
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <small>No photo uploaded</small>
                                            </div>
                                        @endif
                                        <small class="text-muted">Leave empty to keep current photo</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Model Name</label>
                                        <input type="text" name="model" class="form-control" value="{{ $vehicle->model }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Plate Number</label>
                                        <input type="text" name="plateNumber" class="form-control" value="{{ $vehicle->plateNumber }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vehicle Type</label>
                                        <input type="text" name="vehicleType" class="form-control" value="{{ $vehicle->vehicleType }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Daily Price (RM)</label>
                                        <input type="number" step="0.01" name="pricePerDay" class="form-control" value="{{ $vehicle->pricePerDay }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Seats</label>
                                        <input type="number" name="seat" class="form-control" value="{{ $vehicle->seat }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transmission</label>
                                        <select name="transmission" class="form-select">
                                            <option value="Manual" {{ $vehicle->transmission == 'Manual' ? 'selected' : '' }}>Manual</option>
                                            <option value="Automatic" {{ $vehicle->transmission == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">AC</label>
                                        <select name="ac" class="form-select">
                                            <option value="1" {{ $vehicle->ac == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $vehicle->ac == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fuel Type</label>
                                        <input type="text" name="fuelType" class="form-control" value="{{ $vehicle->fuelType }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fuel Level (%)</label>
                                        <input type="number" min="0" max="100" name="fuelLevel" class="form-control" value="{{ $vehicle->fuelLevel }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="available" {{ $vehicle->status == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="reserved" {{ $vehicle->status == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                            <option value="rented" {{ $vehicle->status == 'rented' ? 'selected' : '' }}>Rented</option>
                                            <option value="maintenance" {{ $vehicle->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                    </div>
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
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-car" style="font-size: 4rem; color: #ddd;"></i>
                </div>
                <h4 class="text-muted">No vehicles found</h4>
                <p class="text-muted">
                    @if($status == 'available')
                        No active vehicles (available or reserved) at the moment.
                    @elseif($status == 'rented')
                        No vehicles currently on rent.
                    @else
                        No vehicles in maintenance.
                    @endif
                </p>
                <a href="{{ route('admin.vehicles.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Vehicle
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Auto-refresh page after modal close if status was changed
        document.addEventListener('DOMContentLoaded', function() {
            const editModals = document.querySelectorAll('.modal');
            editModals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    setTimeout(() => {
                        if (window.vehicleUpdated) {
                            location.reload();
                        }
                    }, 500);
                });
            });
            
            // Track form submissions
            document.querySelectorAll('form[action*="update"]').forEach(form => {
                form.addEventListener('submit', function() {
                    window.vehicleUpdated = true;
                });
            });
        });
    </script>
</body>
</html>
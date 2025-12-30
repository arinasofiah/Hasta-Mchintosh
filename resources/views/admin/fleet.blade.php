<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Fleet Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .status-badge { background: #c1f2c7; color: #2e7d32; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
        
      
        .action-btn { background: #eee; border: none; padding: 8px 12px; border-radius: 8px; margin-left: 10px; transition: 0.3s; cursor: pointer; }
        .action-btn:hover { background: #e0e0e0; }
        .action-btn.delete:hover { background: #ffebee; color: #d32f2f; }
        
        .tab-menu { border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .tab-link { padding: 10px 20px; text-decoration: none; color: #666; display: inline-block; }
        .tab-link.active { color: #000; border-bottom: 2px solid #000; font-weight: 600; }

        
        .alert-floating { margin-bottom: 25px; border-radius: 10px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item">📊 Dashboard</a>
        <a href="#" class="nav-item">📈 Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item active">🚗 Fleet</a> 
        <a href="#" class="nav-item">👥 Customer</a>
        <a href="#" class="nav-item">👔 Staff</a>
        <a href="#" class="nav-item">🎁 Promotions</a>
        <a href="#" class="nav-item">⚙️ Settings</a>
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
        </div>

        <div class="tab-menu">
            <a href="?status=available" class="tab-link {{ $status == 'available' ? 'active' : '' }}">Active</a>
            <a href="?status=maintenance" class="tab-link {{ $status == 'maintenance' ? 'active' : '' }}">Inactive</a>
            <span class="float-end text-muted">Total <b>{{ $totalCount }}</b></span>
        </div>

        @foreach($vehicles as $vehicle)
            <div class="vehicle-card">
                <img src="{{ asset('img/vehicles/'.$vehicle->vehicleID.'.png') }}" class="vehicle-img" alt="Car">
                
                <div class="vehicle-info">
                    <h4 class="mb-0">{{ $vehicle->model }}</h4>
                    <p class="text-muted mb-0">{{ $vehicle->vehicleType }} • {{ $vehicle->plateNumber }}</p>
                </div>

                <div>
                    <span class="status-badge" style="{{ $vehicle->status == 'maintenance' ? 'background:#ffebee; color:#c62828;' : '' }}">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                </div>

                <div class="ms-5 d-flex">
                    {{-- 📝 EDIT BUTTON --}}
                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#editModal{{ $vehicle->vehicleID }}">📝</button>

</a>
                    {{-- 🗑️ DELETE FORM --}}
                    <form action="{{ route('admin.vehicles.destroy', $vehicle->vehicleID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete">🗑️</button>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="editModal{{ $vehicle->vehicleID }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.vehicles.update', $vehicle->vehicleID) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Vehicle: {{ $vehicle->model }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Model Name</label>
                                <input type="text" name="model" class="form-control" value="{{ $vehicle->model }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Plate Number</label>
                                <input type="text" name="plateNumber" class="form-control" value="{{ $vehicle->plateNumber }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="available" {{ $vehicle->status == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="rented" {{ $vehicle->status == 'rented' ? 'selected' : '' }}>Rented</option>
                                    <option value="maintenance" {{ $vehicle->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
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
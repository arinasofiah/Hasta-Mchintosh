<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta - Fleet Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        
        /* Sidebar Styling */
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        
        /* Highlighted Sidebar Menu */
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .nav-item i { margin-right: 10px; }

        /* Content Area */
        .main-content { margin-left: 250px; padding: 40px; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Vehicle Card */
        .vehicle-card { background: #fff; border-radius: 15px; padding: 20px; margin-bottom: 20px; display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .vehicle-img { width: 150px; height: 100px; object-fit: contain; margin-right: 30px; }
        .vehicle-info { flex-grow: 1; }
        .status-badge { background: #c1f2c7; color: #2e7d32; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
        
        /* Action Buttons */
        .action-btn { background: #eee; border: none; padding: 8px; border-radius: 8px; margin-left: 10px; transition: 0.3s; }
        .action-btn.delete:hover { background: #ffebee; color: #d32f2f; }
        
        .tab-menu { border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .tab-link { padding: 10px 20px; text-decoration: none; color: #666; display: inline-block; }
        .tab-link.active { color: #000; border-bottom: 2px solid #000; font-weight: 600; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">📊 Dashboard</a>
        <a href="#" class="nav-item">📈 Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item active">🚗 Vehicles</a> <a href="#" class="nav-item">👥 Customer</a>
        <a href="#" class="nav-item">👔 Staff</a>
        <a href="#" class="nav-item">🎁 Promotions</a>
        <a href="#" class="nav-item">⚙️ Settings</a>
    </div>

    <div class="main-content">
        <div class="header-flex">
            <h2>Vehicles Management</h2>
            <button class="btn btn-success" style="border-radius: 20px; background-color: #1a8f36;">+ Add New</button>
        </div>

        <div class="tab-menu">
            <a href="?status=available" class="tab-link {{ $status == 'available' ? 'active' : '' }}">Active</a>
            <a href="?status=maintenance" class="tab-link {{ $status == 'maintenance' ? 'active' : '' }}">Inactive</a>
           <span class="float-end text-muted">Total <b>{{ $totalCount }}</b></span>
        </div>

        @foreach($vehicles as $vehicle)
        <div class="vehicle-card">
            <img src="{{ asset('img/vehicles/'.$vehicle->vehicleID.'.jpg') }}" class="vehicle-img" alt="Car">
            
            <div class="vehicle-info">
                <h4 class="mb-0">{{ $vehicle->model }} {{ $vehicle->year }}</h4>
                <p class="text-muted mb-0">{{ $vehicle->vehicleType }}</p>
            </div>

            <div>
                <span class="status-badge">{{ ucfirst($vehicle->status) }}</span>
            </div>

            <div class="ms-5">
                <button class="action-btn">📝</button>
                <form action="#" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete">🗑️</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

</body>
</html>
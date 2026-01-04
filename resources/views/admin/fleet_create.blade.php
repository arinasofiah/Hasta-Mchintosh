<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Vehicle - Hasta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .main-content { margin-left: 250px; padding: 40px; }
        .form-container { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 800px; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item"> Dashboard</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item active"> Fleet</a> 
        <a href= "{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
    
    </div>

    <div class="main-content">
        <div class="mb-4">
            <a href="{{ route('admin.fleet') }}" class="text-decoration-none text-muted">← Back to Fleet</a>
            <h2 class="mt-2">Register New Fleet</h2>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <!-- FIX: Add enctype="multipart/form-data" -->
            <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Vehicle Photo Field -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Vehicle Photo</label>
                        <input type="file" name="vehiclePhoto" class="form-control" accept="image/*">
                        <small class="text-muted">Upload a photo of the vehicle (JPEG, PNG, JPG, GIF - max 2MB)</small>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Model Name</label>
                        <input type="text" name="model" class="form-control" placeholder="e.g. Honda Civic 2020" 
                               value="{{ old('model') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Vehicle Type</label>
                        <select name="vehicleType" class="form-select">
                            <option value="Sedan" {{ old('vehicleType') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="Hatchback" {{ old('vehicleType') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                            <option value="SUV" {{ old('vehicleType') == 'SUV' ? 'selected' : '' }}>SUV</option>
                            <option value="MPV" {{ old('vehicleType') == 'MPV' ? 'selected' : '' }}>MPV</option>
                            <option value="Luxury" {{ old('vehicleType') == 'Luxury' ? 'selected' : '' }}>Luxury</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Plate Number</label>
                        <input type="text" name="plateNumber" class="form-control" placeholder="VAB 1234" 
                               value="{{ old('plateNumber') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Transmission Type</label>
                        <select name="transmission" class="form-select" required>
                            <option value="">Select Transmission</option>
                            <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                            <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Air Conditioning</label>
                        <select name="ac" class="form-select" required>
                            <option value="">Select AC Status</option>
                            <option value="1" {{ old('ac') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('ac') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price Per Day (RM)</label>
                        <input type="number" name="pricePerDay" class="form-control" 
                               value="{{ old('pricePerDay') }}" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Number of Seats</label>
                        <input type="number" name="seat" class="form-control" value="{{ old('seat', 5) }}" min="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fuel Type</label>
                        <select name="fuelType" class="form-select">
                            <option value="Petrol" {{ old('fuelType') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="Diesel" {{ old('fuelType') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="Electric" {{ old('fuelType') == 'Electric' ? 'selected' : '' }}>Electric</option>
                            <option value="Hybrid" {{ old('fuelType') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fuel Level (%)</label>
                        <input type="number" name="fuelLevel" class="form-control" 
                               value="{{ old('fuelLevel', 100) }}" min="0" max="100" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-5" style="background-color: #bc3737; border: none; border-radius: 10px;">Save Vehicle</button>
                    <a href="{{ route('admin.fleet') }}" class="btn btn-light px-4 ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
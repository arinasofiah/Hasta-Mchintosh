<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour - Customer Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vehicles.css') }}" rel="stylesheet">

    <style>
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #bc3737;
            color: white;
            text-align: center;
        }
        
    </style>
</head>

<body>

<div id="header">
    <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">

    <div id="menu">
        <button class="head_button">Home</button>
        <button class="head_button">Vehicles</button>
        <button class="head_button">Details</button>
        <button class="head_button">About Us</button>
        <button class="head_button">Contact Us</button>
    </div>

    <div id="profile">
        <div id="profile-container">
            <img id="pfp" src="{{ asset('img/racc_icon.png') }}">
            <div id="profile-dropdown">
                <a href="{{ route('customer.profile') }}" class="dropdown-item">👤 My Profile</a>
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

<div id="body">
    <h2 style="text-align: center; margin: 20px 0;">Available Vehicles</h2>
    
    <div class="car-grid">
        
        @if(count($vehicles) > 0)
            @foreach($vehicles as $vehicle)
            <div class="car-card">
                {{-- Vehicle Image --}}
                <img src="{{ asset('img/vehicles/'.$vehicle->vehicleID.'.jpg') }}"
                     onerror="this.src='{{ asset('img/vehicles/default.jpg') }}'"
                     alt="{{ $vehicle->model }}">
                
                {{-- Vehicle Model and Type --}}
                <h4>{{ $vehicle->model }} ({{ $vehicle->vehicleType }})</h4>
                
                {{-- Pricing --}}
                <div style="margin: 10px 0;">
                    <h3 style="color: #bc3737; margin: 5px 0;">RM{{ number_format($vehicle->pricePerDay, 2) }}/day</h3>
                    <small>or RM{{ number_format($vehicle->pricePerHour, 2) }}/hour</small>
                </div>
                
                {{-- Specifications --}}
                <div class="specs">
                    <span title="Plate Number">📌 {{ $vehicle->plateNumber }}</span>
                    <span title="Fuel">⛽ {{ $vehicle->fuelLevel }}% ({{ $vehicle->fuelType }})</span>
                    <span title="Seats">💺 {{ $vehicle->seat }} seats</span>
                </div>
                
                <div class="specs">
                    <span title="AC">{{ $vehicle->ac ? '❄️ Has AC' : '🌡️ No AC' }}</span>
                    <span title="Status">📊 {{ ucfirst($vehicle->status) }}</span>
                </div>
                
                {{-- View Details Button --}}
                @auth
                    <a href="{{ route('booking.form', $vehicle->vehicleID) }}" class="btn">
                        View Details & Book
                    </a>
                @endauth
                
            </div>
            @endforeach
        @else
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <h3>No vehicles available at the moment</h3>
                <p>Please check back later or contact us for more information.</p>
            </div>
        @endif
        
    </div>
</div>


</body>
</html>
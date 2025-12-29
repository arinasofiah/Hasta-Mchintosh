<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vehicles.css') }}" rel="stylesheet">

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
                @guest
                    <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                    <a href="{{ route('register') }}" class="dropdown-item">Register</a>
                   
                @endguest

               
            </div>
        </div>

        @guest
            <a id="username" href="{{ route('login') }}">Log in</a>
        @endguest

    </div>
</div>

<div id="body">
    <div class="car-grid">

@foreach($vehicles as $vehicle)
    <div class="car-card">

        <img src="{{ asset('img/vehicles/'.$vehicle->vehicleID.'.jpg') }}">

        <h3>RM{{ $vehicle->pricePerDay }}</h3>

        <div class="specs">
            <span>🚗 {{ $vehicle->vehicleType }}</span>
            <span>⛽ {{ $vehicle->fuelLevel }}%</span>
            <span>📌 {{ $vehicle->plateNumber }}</span>
        </div>

        {{-- View Details Button --}}
        @auth
            <a href="{{ route('booking.form', $vehicle->vehicleID) }}" class="btn">
                View Details
            </a>
        @else
            <a href="{{ route('login') }}" class="btn">
                View Details
            </a>
        @endauth

    </div>
@endforeach

</div>

</div>

<div class="footer">
    <div class="footer-content">
        <div class="logo">
            <img src="{{ asset('img/hasta_logo.jpg') }}" alt="Hasta Travel Logo">
        </div>

        <div class="footer-item">
            <div class="footer-icon">📍</div>
            <div>
                <span class="title">Address</span><br>
                Student Mall UTM<br>
                Skudai, 81300, Johor Bahru
            </div>
        </div>

        <div class="footer-item">
            <div class="footer-icon">✉️</div>
            <div>
                <span class="title">Email</span><br>
                <a href="mailto:hastatravel@gmail.com">hastatravel@gmail.com</a>
            </div>
        </div>

        <div class="footer-item">
            <div class="footer-icon">📞</div>
            <div>
                <span class="title">Phone</span><br>
                <a href="tel:01110900700">011-1090 0700</a>
            </div>
        </div>
    </div>
    
    <div class="copyright">
        © {{ date('Y') }} Hasta Travel & Tour. All rights reserved.
    </div>
</div>

</body>
</html>

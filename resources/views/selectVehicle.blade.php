@php
$featuredVehicle = $featuredVehicle ?? [
    'id' => 1,
    'name' => 'Perodua Axia 2018',
    'type' => 'Hatchback',
    'price' => 120,
    'image' => 'assets/images/perodua-axia.png',
    'seats' => 5,
    'bags' => 1,
    'doors' => 4,
    'ac' => 'Yes',
    'transmission' => 'Auto',
    'fuel_policy' => 'Full',
    'fuel_type' => 'Petrol',
    'available' => true
];

$otherVehicles = $otherVehicles ?? [
    [
        'id' => 2,
        'name' => 'Proton Saga 2017',
        'type' => 'Sedan',
        'price' => 120,
        'image' => 'assets/images/proton-saga.png',
        'seats' => 5,
        'bags' => 1,
        'doors' => 4,
        'ac' => 'Yes',
        'transmission' => 'Auto',
        'fuel_policy' => 'Full',
        'fuel_type' => 'Petrol'
    ],
    [
        'id' => 3,
        'name' => 'Perodua Bezza 2018',
        'type' => 'Sedan',
        'price' => 140,
        'image' => 'assets/images/perodua-bezza.png',
        'seats' => 5,
        'bags' => 1,
        'doors' => 4,
        'ac' => 'Yes',
        'transmission' => 'Auto',
        'fuel_policy' => 'Full',
        'fuel_type' => 'Petrol'
    ],
    [
        'id' => 4,
        'name' => 'Perodua Myvi 2015',
        'type' => 'Hatchback',
        'price' => 120,
        'image' => 'assets/images/perodua-myvi.png',
        'seats' => 5,
        'bags' => 1,
        'doors' => 4,
        'ac' => 'Yes',
        'transmission' => 'Auto',
        'fuel_policy' => 'Full',
        'fuel_type' => 'Petrol'
    ]
];

$pickupDate = $pickupDate ?? date('Y-m-d');
$pickupTime = $pickupTime ?? '08:00';
$returnDate = $returnDate ?? date('Y-m-d', strtotime('+1 day'));
$returnTime = $returnTime ?? '08:00';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Vehicle Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/selectVehicle.css') }}">
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="logo">HASTA</div>
        <nav class="nav">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ route('selectVehicle', $vehicle->vehicleID) }}">Vehicles</a>
            <a href="#">Details</a>
            <a href="#">About Us</a>
            <a href="#">Contact Us</a>
        </nav>
        @guest
            <a href="{{ route('login') }}" class="login-btn">Log In</a>
        @endguest
        @auth
            <span class="login-btn">{{ Auth::user()->name }}</span>
        @endauth
    </header>

    <!-- Progress Steps -->
    <div class="progress-container">
        <div class="steps">
            <div class="step active"><span class="step-icon">✓</span><span>Vehicle</span></div>
            <div class="step-connector"></div>
            <div class="step"><span class="step-icon">✓</span><span>Register</span></div>
            <div class="step-connector"></div>
            <div class="step"><span class="step-icon">✓</span><span>Booking Details</span></div>
            <div class="step-connector"></div>
            <div class="step"><span class="step-icon">✓</span><span>Payment</span></div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="booking-form">
        <form action="{{ route('selectVehicle', $vehicle->vehicleID) }}" method="GET">
            <div class="form-group">
                <label>Pickup</label>
                <div class="date-time-group">
                    <div class="input-wrapper">
                        <div class="input-label">Date</div>
                        <input type="date" name="pickup_date" value="{{ $pickupDate }}">
                        <span class="input-icon">📅</span>
                    </div>
                    <div class="input-wrapper">
                        <div class="input-label">Time</div>
                        <input type="time" name="pickup_time" value="{{ $pickupTime }}">
                        <span class="input-icon">🕐</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Return</label>
                <div class="date-time-group">
                    <div class="input-wrapper">
                        <div class="input-label">Date</div>
                        <input type="date" name="return_date" value="{{ $returnDate }}">
                        <span class="input-icon">📅</span>
                    </div>
                    <div class="input-wrapper">
                        <div class="input-label">Time</div>
                        <input type="time" name="return_time" value="{{ $returnTime }}">
                        <span class="input-icon">🕐</span>
                    </div>
                </div>
            </div>
            <button type="submit" class="book-btn">Update Dates</button>
        </form>
    </div>

    <!-- Featured Vehicle -->
    <div class="featured-vehicle">
        <div class="vehicle-image">
            <img src="{{ asset($featuredVehicle['image']) }}" alt="{{ $featuredVehicle['name'] }}">
        </div>
        <div class="vehicle-details">
            <h2 class="vehicle-name">{{ $featuredVehicle['name'] }}</h2>
            <p class="vehicle-type">{{ $featuredVehicle['type'] }}</p>
            <div class="vehicle-price">
                <span class="price-amount">RM {{ $featuredVehicle['price'] }}</span> / day
            </div>
            <div class="vehicle-specs">
                <span>Seats: {{ $featuredVehicle['seats'] }}</span>
                <span>Bags: {{ $featuredVehicle['bags'] }}</span>
                <span>Doors: {{ $featuredVehicle['doors'] }}</span>
                <span>AC: {{ $featuredVehicle['ac'] }}</span>
                <span>Transmission: {{ $featuredVehicle['transmission'] }}</span>
                <span>Fuel: {{ $featuredVehicle['fuel_type'] }}</span>
            </div>
            @if($featuredVehicle['available'])
                <button class="book-btn">Book</button>
            @else
                <span class="availability-badge">Not Available</span>
            @endif
        </div>
    </div>

    <!-- Other Vehicles -->
    <div class="vehicle-grid">
        @foreach($otherVehicles as $vehicle)
        <div class="vehicle-card">
            <div class="card-image">
                <img src="{{ asset($vehicle['image']) }}" alt="{{ $vehicle['name'] }}">
            </div>
            <div class="card-header">
                <h3 class="card-name">{{ $vehicle['name'] }}</h3>
                <p class="card-type">{{ $vehicle['type'] }}</p>
                <div class="card-price">RM{{ $vehicle['price'] }}</div>
            </div>
            <div class="card-specs">
                <span>Seats: {{ $vehicle['seats'] }}</span>
                <span>Bags: {{ $vehicle['bags'] }}</span>
                <span>Doors: {{ $vehicle['doors'] }}</span>
                <span>AC: {{ $vehicle['ac'] }}</span>
                <span>Transmission: {{ $vehicle['transmission'] }}</span>
                <span>Fuel: {{ $vehicle['fuel_type'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

</body>
</html>

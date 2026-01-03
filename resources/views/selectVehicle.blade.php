<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Vehicle Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/selectVehicle.css') }}">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">

    <style>
                * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
        }

        /* Header 
        .header {
            background-color: #d94242;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            border: 3px solid white;
            padding: 8px 15px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }*/

        .nav {
            display: flex;
            gap: 40px;
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }

        .nav a:hover {
            text-decoration: underline;
        }

        .login-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
        }

        .login-icon svg {
            width: 24px;
            height: 24px;
            fill: #d94242;
        }

        /* Progress Steps */
        .progress-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 50px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 40px;
            border: 2px solid #ddd;
            border-radius: 50px;
            background-color: white;
            color: #999;
            font-weight: 600;
        }

        .step.active {
            border-color: #d94242;
            color: #d94242;
        }

        .step-icon {
            width: 24px;
            height: 24px;
        }

        .step-connector {
            width: 100px;
            height: 2px;
            background-color: #ddd;
        }

        /* Booking Form */
        .booking-form {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 20px;
            display: flex;
            justify-content: center;
        }

        .date-time-row {
            display: flex;
            gap: 15px;
            align-items: end;
        }

        .input-wrapper {
            position: relative;
            flex: 1;
            max-width: 200px;
        }

        .input-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 7px;
        }

        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .format-hint {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        /* Featured Vehicle */
        .featured-vehicle {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .vehicle-image {
            flex: 1;
        }

        .vehicle-image img {
            width: 100%;
            max-width: 500px;
        }

        .vehicle-details {
            flex: 1;
        }

        .vehicle-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .vehicle-name {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .vehicle-price {
            text-align: right;
        }

        .price-amount {
            font-size: 32px;
            font-weight: 700;
            color: #d94242;
        }

        .price-label {
            font-size: 14px;
            color: #666;
        }

        .vehicle-type {
            color: #666;
            margin-bottom: 30px;
        }

        .vehicle-specs {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }

        .spec-item {
            text-align: center;
        }

        .spec-icon {
            font-size: 24px;
            margin-bottom: 5px;
            color: #666;
        }

        .spec-value {
            font-size: 14px;
            color: #666;
        }

        .availability-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .availability-badge {
            padding: 10px 50px;
            border: 2px solid #4CAF50;
            border-radius: 30px;
            color: #4CAF50;
            font-weight: 600;
        }

        .book-btn {
            padding: 12px 40px;
            background-color: #d94242;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .book-btn:hover {
            background-color: #c23535;
        }

        /* Vehicle Grid */
        .vehicle-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .vehicle-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            border: 2px solid #eee;
        }

        .vehicle-card:hover {
            border-color: #d94242;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card-image {
            text-align: center;
            margin-bottom: 20px;
        }

        .card-image img {
            width: 100%;
            max-width: 250px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 5px;
        }

        .card-name {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .card-price {
            font-size: 22px;
            font-weight: 700;
            color: #d94242;
        }

        .card-type {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .card-specs {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }

        .card-spec {
            text-align: center;
        }

        .card-spec-icon {
            font-size: 18px;
            margin-bottom: 3px;
        }

        @media (max-width: 1024px) {
            .vehicle-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .nav {
                display: none;
            }

            .booking-form {
                flex-direction: column;
            }

            .featured-vehicle {
                flex-direction: column;
            }

            .vehicle-grid {
                grid-template-columns: 1fr;
            }

            .date-time-row {
                flex-wrap: wrap;
            }
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
                @guest
                    <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                    <a href="{{ route('register') }}" class="dropdown-item">Register</a>
                   
                @endguest
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
         @guest
            <a id="username" href="{{ route('login') }}">Log in</a>
        @endguest
    </div>
</div>

<!-- Progress Steps -->
<div class="progress-container">
    <div class="steps">
        <div class="step active"><span class="step-icon">✓</span><span>Vehicle</span></div>
        <div class="step-connector"></div>
        <div class="step"><span class="step-icon">✓</span><span>Booking Details</span></div>
        <div class="step-connector"></div>
        <div class="step"><span class="step-icon">✓</span><span>Payment</span></div>
    </div>
</div>

<!-- Booking Form -->
<div class="booking-form">
    <form action="{{ route('selectVehicle', $featuredVehicle->vehicleID) ?? 0 }}" method="GET" id="dateForm">
        <div class="date-time-row">
            <div class="input-wrapper">
                <div class="input-label">Pickup Date</div>
                <input type="date" id="pickup_date" name="pickup_date" value="{{ $pickupDate }}">
                <span class="input-icon"></span>
            </div>
            <div class="input-wrapper">
                <div class="input-label">Pickup Time</div>
                <input type="time" id="pickup_time" name="pickup_time" value="{{ $pickupTime }}">
                <span class="input-icon"></span>
            </div>
            <div class="input-wrapper">
                <div class="input-label">Return Date</div>
                <input type="date" id="return_date" name="return_date" value="{{ $returnDate }}">
                <span class="input-icon"></span>
            </div>
            <div class="input-wrapper">
                <div class="input-label">Return Time</div>
                <input type="time" id="return_time" name="return_time" value="{{ $returnTime }}">
                <span class="input-icon"></span>
            </div>
        </div>
    </form>
</div>

<!-- Featured Vehicle -->
<div class="featured-vehicle">

    <div class="vehicle-image">
        <img src="{{ asset($featuredVehicle->image ?? 'img/vehicles/default.jpg') }}" alt="{{ $featuredVehicle->model }}">
    </div>

    <div class="vehicle-details">
        <h2 class="vehicle-name">{{ $featuredVehicle->model }}</h2>
        <p class="vehicle-type">{{ $featuredVehicle->vehicleType }}</p>
        
        <div class="vehicle-price">
            <span class="price-amount">RM {{ $featuredVehicle->pricePerDay }}</span> / day
        </div>

        <div class="vehicle-specs">
            <span>Seats: {{ $featuredVehicle->seat }}</span>
            <span>AC: {{ $featuredVehicle->ac }}</span>
            <span>Transmission: {{ $featuredVehicle->transmission }}
            <span>Fuel: {{ $featuredVehicle->fuelType }}</span>
        </div>

        @if($featuredVehicle->status == 'available')
            <button class="availability-badge">Available!</button>
            <button class="book-btn" onclick="handleBooking()">Book</button>
            
            <script>
                function handleBooking() {
                    const pickupDate  = document.getElementById('pickup_date').value;
                    const pickupTime  = document.getElementById('pickup_time').value;
                    const returnDate  = document.getElementById('return_date').value;
                    const returnTime  = document.getElementById('return_time').value;

                   if (!pickupDate || !pickupTime || !returnDate || !returnTime) {
                alert('Please select pickup and return date/time first.');
                return;
            }

            @auth          
            const baseUrl = "{{ route('booking.form', ['vehicleID' => $featuredVehicle->vehicleID]) }}";
            const params = new URLSearchParams({
                pickup_date: pickupDate,
                pickup_time: pickupTime,
                return_date: returnDate,
                return_time: returnTime
            });
            
            window.location.href = `${baseUrl}?${params.toString()}`;
        @else
            window.location.href = "{{ route('login') }}";
        @endauth
                }
            </script>


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
            <h3 class="card-name">{{ $vehicle->model }}</h3>
            <p class="card-type">{{ $vehicle->vehicleType }}</p>
            <div class="card-price">RM{{ $vehicle->pricePerDay }}</div>
        </div>

        <div class="card-specs">
            <span>Seats: {{ $vehicle->seat }}</span>
            <span>AC: {{ $vehicle->ac }}</span>
            <span>Transmission: {{ $vehicle->transmission }}</span>
            <span>Fuel: {{ $vehicle->fuelType }}</span>
        </div>

    </div>
    @endforeach

</div>

<script>
const pickupInput = document.getElementById('pickup_date');
const pickupTimeInput = document.getElementById('pickup_time');
const returnInput = document.getElementById('return_date');
const returnTimeInput = document.getElementById('return_time');

// Defaults
if (!pickupInput.value) pickupInput.value = new Date().toISOString().split('T')[0];
if (!pickupTimeInput.value) pickupTimeInput.value = '08:00';

// Always sync return = pickup + 24 hours
function syncReturnDateTime() {
    const pickupDT = new Date(`${pickupInput.value}T${pickupTimeInput.value}`);
    if (isNaN(pickupDT)) return;

    pickupDT.setHours(pickupDT.getHours() + 24);

    returnInput.value = pickupDT.toISOString().split('T')[0];
    returnTimeInput.value = pickupDT.toTimeString().slice(0, 5);
}

// Initial sync
syncReturnDateTime();

// When pickup date OR time changes → auto update return
[pickupInput, pickupTimeInput].forEach(el => {
    el.addEventListener('change', () => {
        syncReturnDateTime();
        updateVehicles();
    });
});

// When return manually changed → validate
[returnInput, returnTimeInput].forEach(el => {
    el.addEventListener('change', updateVehicles);
});

function updateVehicles() {
    const pickupDT = new Date(`${pickupInput.value}T${pickupTimeInput.value}`);
    const returnDT = new Date(`${returnInput.value}T${returnTimeInput.value}`);

    if (returnDT <= pickupDT) {
        alert('Return date/time must be after pickup date/time!');
        return;
    }

    fetch(`/vehicles/available?pickup_date=${pickupInput.value}&pickup_time=${pickupTimeInput.value}&return_date=${returnInput.value}&return_time=${returnTimeInput.value}`)
        .then(res => res.json())
        .then(data => renderVehicles(data))
        .catch(err => console.error(err));
}

function renderVehicles(vehicles) {
    if(!vehicles || vehicles.length === 0) {
        vehicleGrid.innerHTML = '<p>No vehicles available for the selected date/time.</p>';
        featuredVehicleContainer.style.display = 'none';
        return;
    }

    // Featured vehicle
    const featured = vehicles[0];
    featuredVehicleContainer.style.display = 'flex';
    featuredVehicleContainer.querySelector('img').src = `/storage/${featured.image}`;
    featuredVehicleContainer.querySelector('.vehicle-name').textContent = featured.model;
    featuredVehicleContainer.querySelector('.vehicle-type').textContent = featured.vehicleType;
    featuredVehicleContainer.querySelector('.price-amount').textContent = 'RM ' + featured.pricePerDay;
    featuredVehicleContainer.querySelector('.vehicle-specs').innerHTML = `
        <span>Seats: ${featured.seat}</span>
        <span>AC: ${featured.ac}</span>
        <span>Transmission: ${featured.transmission}</span>
        <span>Fuel: ${featured.fuelType}</span>
    `;

    // Update Book button dynamically
    const bookBtn = featuredVehicleContainer.querySelector('.book-btn');
    if(bookBtn) {
        if(featured.status === 'available') {
            bookBtn.style.display = 'inline-block';
            const availabilityBadge = featuredVehicleContainer.querySelector('.availability-badge');
            if(availabilityBadge) availabilityBadge.textContent = 'Available!';

            bookBtn.onclick = function() {
                @auth
                    window.location.href = `/booking/form/${featured.vehicleID}?pickup_date=${pickupInput.value}&pickup_time=${pickupTimeInput.value}&return_date=${returnInput.value}&return_time=${returnTimeInput.value}`;
                @else
                    window.location.href = "{{ route('login') }}";
                @endauth
            };
        } else {
            bookBtn.style.display = 'none';
            const availabilityBadge = featuredVehicleContainer.querySelector('.availability-badge');
            if(availabilityBadge) availabilityBadge.textContent = 'Not Available';
        }
    }

    // Other vehicles
    vehicleGrid.innerHTML = '';
    vehicles.slice(1).forEach(v => {
        vehicleGrid.innerHTML += `
        <div class="vehicle-card">
            <div class="card-image">
                <img src="/storage/${v.image}" alt="${v.model}">
            </div>
            <div class="card-header">
                <h3 class="card-name">${v.model}</h3>
                <p class="card-type">${v.vehicleType}</p>
                <div class="card-price">RM${v.pricePerDay}</div>
            </div>
            <div class="card-specs">
                <span>Seats: ${v.seat}</span>
                <span>AC: ${v.ac}</span>
                <span>Transmission: ${v.transmission}</span>
                <span>Fuel: ${v.fuelType}</span>
            </div>
        </div>`;
    });
}

// Initial load
updateVehicles();
</script>


</body>
</html>
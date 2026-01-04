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
            cursor: default;
            pointer-events: none;
            opacity: 0.6;
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

        .step.enabled{
            border-color: #d94242;
            color: #d94242;
            cursor: pointer;          
            pointer-events: auto;
            opacity: 1;
        }

        /* Booking Form */
        .booking-form {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 20px;
            display: flex;
            justify-content: center;
        }

        .booking-form form {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .date-time-row {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: flex-end;
        }

        .input-wrapper {
            position: relative;
            flex: 0 1 auto;
            min-width: 150px;
        }

        .input-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
        }

        input[type="date"]:hover,
        input[type="time"]:hover {
            border-color: #d94242;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
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
            padding: 12px 40px;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            color: #2E7D32;
            font-weight: 600;
            background-color: #EAF8EC;
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
        display: flex;
        align-items: center;
        gap: 6px;
        }

        .card-spec-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            color: #444;
        }


        @media (max-width: 1024px) {
            .vehicle-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .date-time-group {
                flex-wrap: wrap;
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
            
            .date-time-group {
                flex-direction: column;
                width: 100%;
            }
            
            .input-wrapper {
                width: 100%;
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
                <a href="{{ route('customer.profile') }}" class="dropdown-item" role="menuitem">My Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                    @csrf
                    <button type="submit" class="dropdown-item" role="menuitem">Logout</button>
                </form>
            @endauth
               
            </div>
        </div>

        @guest
            <a id="username" href="{{ route('login') }}">Log in</a>
        @endguest
            @auth
        <span id="username">{{ Auth::user()->name }}</span>
    @endauth

         </div>
    </div>
    
    <!-- Progress Steps -->
    <div class="progress-container">
        <div class="steps">
            <div class="step active" id="step-vehicle"><span class="step-icon">✓</span><span>Vehicle</span></div>
            <div class="step-connector"></div>
            <div class="step" id="step-booking"><span class="step-icon">✓</span><span>Booking Details</span></div>
            <div class="step-connector"></div>
            <div class="step" id="step-payment"><span class="step-icon">✓</span><span>Payment</span></div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="booking-form">
        <form id="bookingForm" action="{{ route('selectVehicle', $featuredVehicle->vehicleID) }}" method="GET">
            <div class="date-time-group">
                <div class="input-wrapper">
                    <div class="input-label">Pickup Date</div>
                    <input type="date" name="pickup_date" value="{{ $pickupDate }}" onchange="this.form.submit()">
                    <span class="input-icon"></span>
                </div>
                <div class="input-wrapper">
                    <div class="input-label">Pickup Time</div>
                    <input type="time" name="pickup_time" value="{{ $pickupTime }}" onchange="this.form.submit()">
                    <span class="input-icon"></span>
                </div>
                <div class="input-wrapper">
                    <div class="input-label">Return Date</div>
                    <input type="date" name="return_date" value="{{ $returnDate }}" onchange="this.form.submit()">
                    <span class="input-icon"></span>
                </div>
                <div class="input-wrapper">
                    <div class="input-label">Return Time</div>
                    <input type="time" name="return_time" value="{{ $returnTime }}" onchange="this.form.submit()">
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
                <span>Transmission: {{ $featuredVehicle->transmission }}</span>
                <span>Fuel: {{ $featuredVehicle->fuelType }}</span>
            </div>

            <div class="availability-section">
                @if($featuredVehicle->status == 'available')
                    <button class="availability-badge">Available!</button>
                    <button class="book-btn" onclick="handleBooking()">Book</button>
                    
                    <script>
                        function handleBooking(){
                            @auth
                                window.location.href = "{{ route('booking.form', ['vehicleID' => $featuredVehicle->vehicleID]) }}";
                            @else
                                window.location.href = "{{ route('login') }}";
                            @endauth
                        }
                    </script>
                @else
                    <span class="availability-badge" style="border-color: #999; color: #999;">Not Available</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Other Vehicles -->
    <div class="vehicle-grid">
        @foreach($otherVehicles as $vehicle)
        <div class="vehicle-card">
            <div class="card-image">
                <img src="{{ asset($vehicle->image ?? 'img/vehicles/default.jpg') }}" alt="{{ $vehicle->model }}">
            </div>

            <div class="card-header">
                <h3 class="card-name">{{ $vehicle->model }}</h3>
                <div class="card-price">RM{{ $vehicle->pricePerDay }}</div>
            </div>
            
            <p class="card-type">{{ $vehicle->vehicleType }}</p>

            <div class="card-specs">
    
    <!-- Seats -->
    <div class="card-spec">
        <div class="card-spec-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="5" y="3" width="14" height="8" rx="2"/>
                <path d="M7 11v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-5"/>
                <path d="M5 21h14"/>
            </svg>
        </div>
        <div>{{ $vehicle->seat }} Seats</div>
    </div>

    <!-- Aircond -->
    <div class="card-spec">
        <div class="card-spec-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.6"
                stroke-linecap="round" stroke-linejoin="round">

                <circle cx="12" cy="12" r="2"/>
                <path d="M12 4c2 0 4 2 4 4s-2 4-4 4"/>
                <path d="M12 20c-2 0-4-2-4-4s2-4 4-4"/>
                <path d="M4 12c0-2 2-4 4-4s4 2 4 4"/>
                <path d="M20 12c0 2-2 4-4 4s-4-2-4-4"/>
            </svg>
        </div>

        <div>{{ $vehicle->ac }}</div>
    </div>

    <!-- Transmission -->
    <div class="card-spec">
        <div class="card-spec-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="8" y="3" width="8" height="6" rx="2"/>
                <path d="M12 9v6"/>
                <circle cx="12" cy="19" r="2"/>
            </svg>
        </div>
        <div>{{ $vehicle->transmission }}</div>
    </div>

    <!-- Fuel -->
    <div class="card-spec">
        <div class="card-spec-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="12" height="18" rx="2"/>
                <path d="M15 7h2l3 3v7a2 2 0 0 1-2 2h-1"/>
                <path d="M6 10h6"/>
            </svg>
        </div>
        <div>{{ $vehicle->fuelType }}</div>
    </div>

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
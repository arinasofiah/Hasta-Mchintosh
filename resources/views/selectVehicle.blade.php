<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Vehicle Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/selectVehicle.css') }}">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <div class="step active">
                <i class="fas fa-car"></i>
                <span>Vehicle</span>
            </div>
            <div class="step-connector"></div>
            <div class="step">
                <i class="fas fa-calendar-check"></i>
                <span>Booking Details</span>
            </div>
            <div class="step-connector"></div>
            <div class="step">
                <i class="fas fa-credit-card"></i>
                <span>Payment</span>
            </div>
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
            @if($featuredVehicle->vehiclePhoto)
                <img src="{{ Storage::url($featuredVehicle->vehiclePhoto) }}" 
                        alt="{{ $featuredVehicle->model }}"
                        onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
            @else
                <img src="{{ asset('img/vehicles/default.jpg') }}" alt="{{ $featuredVehicle->model }}">
            @endif
        </div>

        <div class="vehicle-details">
            <h2 class="vehicle-name">{{ $featuredVehicle->model }}</h2>
            <p class="vehicle-type">{{ $featuredVehicle->vehicleType }}</p>
            
            <div class="vehicle-price">
                <span class="price-amount">RM {{ $featuredVehicle->pricePerDay }}</span> / day
            </div>

            <div class="vehicle-specs">
                <span>Seats: {{ $featuredVehicle->seat }}</span>
                <span>AC: {{ $featuredVehicle->ac == 1 ? 'Yes' : 'No' }}</span>
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
            @if($vehicle->vehiclePhoto)
                <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" 
                        alt="{{ $vehicle->model }}"
                        onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
            @else
                <img src="{{ asset('img/vehicles/default.jpg') }}" alt="{{ $vehicle->model }}">
            @endif
        </div>

        <div class="card-header">
            <h3 class="card-name">{{ $vehicle->model }}</h3>
            <div class="card-price">RM{{ $vehicle->pricePerDay }}</div>
        </div>
        
        <p class="card-type">{{ $vehicle->vehicleType }}</p>

            <div class="card-specs">
                <div class="car-features">
                    <span class="feature-item"><i class="fas fa-user-friends"></i> {{ $vehicle->seats ?? 5 }} seats</span>
                    <span class="feature-item"><i class="fas fa-wind"></i> {{ $vehicle->ac ? 'AC' : 'No AC' }}</span>
                    <span class="feature-item"><i class="fas fa-cog"></i> {{ $vehicle->transmission ?? 'Auto' }}</span>
                    <span class="feature-item"><i class="fas fa-gas-pump"></i> {{ $vehicle->fuelType ?? 'Petrol' }}</span>
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

let userEditedReturnDate = false;
let userEditedReturnTime = false;

// Defaults
if (!pickupInput.value) pickupInput.value = new Date().toISOString().split('T')[0];
if (!pickupTimeInput.value) pickupTimeInput.value = '08:00';

function suggestReturnDateTime() {

    // Stop overwriting if user already changed it manually
    if (userEditedReturnDate || userEditedReturnTime) return;

    const pickupDT = new Date(`${pickupInput.value}T${pickupTimeInput.value}`);
    if (isNaN(pickupDT)) return;

    pickupDT.setHours(pickupDT.getHours() + 24);

    const returnDate = pickupDT.toISOString().split('T')[0];
    const returnTime = pickupDT.toTimeString().slice(0, 5);

    // Set min to prevent earlier selection
    returnInput.min = returnDate;

    // Auto-fill suggested values
    returnInput.value = returnDate;
    returnTimeInput.value = returnTime;
}

// Detect manual overrides
returnInput.addEventListener('input', () => userEditedReturnDate = true);
returnTimeInput.addEventListener('input', () => userEditedReturnTime = true);

// Re-suggest when pickup changes
pickupInput.addEventListener('change', () => {
    userEditedReturnDate = false;
    suggestReturnDateTime();
});

pickupTimeInput.addEventListener('change', () => {
    userEditedReturnTime = false;
    suggestReturnDateTime();
});

// Initial suggestion
suggestReturnDateTime();

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
        <span class="feature-item"><i class="fas fa-user-friends"></i> ${featured.seat}</span>
        <span class="feature-item"><i class="fas fa-wind"></i> ${featured.ac}</span>
        <span class="feature-item"><i class="fas fa-cog"></i> ${featured.transmission}</span>
        <span class="feature-item"><i class="fas fa-gas-pump"></i> ${featured.fuelType}</span>
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
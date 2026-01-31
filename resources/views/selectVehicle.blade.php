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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
/* General Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: #f5f5f5;
}

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
    margin: 40px auto; 
    padding: 0 20px; 
}
.steps { 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 8px; 
    margin-bottom: 40px; 
    flex-wrap: wrap;
}
.step {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 25px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    min-width: 140px;
    text-align: center;
    transition: all 0.3s ease;
}
.step.filled { 
    background: #d94444; 
    color: white; 
    border: none;
}
.step.active { 
    border: 2px solid #d94444; 
    color: #d94444; 
    background: white;
}
.step-connector { 
    width: 80px; 
    height: 2px; 
    background: #ddd;
}

/* Booking Summary (Updated - Read Only) */
.booking-summary {
    max-width: 1200px;
    margin: 0 auto 50px;
    padding: 0 50px;
}

.summary-container {
    background-color: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.summary-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.summary-title i {
    color: #d94242;
}

.date-time-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.summary-label {
    font-size: 12px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-value {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    padding: 12px 15px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.summary-value i {
    color: #d94242;
}

.duration-display {
    grid-column: span 2;
    background-color: #eaf8ec;
    border: 2px solid #4CAF50;
    border-radius: 5px;
    padding: 15px;
    margin-top: 10px;
}

.duration-label {
    font-size: 14px;
    color: #2E7D32;
    font-weight: 600;
    margin-bottom: 5px;
}

.duration-value {
    font-size: 18px;
    color: #2E7D32;
    font-weight: 700;
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
    border-radius: 8px;
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
    text-decoration: none;
    display: inline-block;
    text-align: center;
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
    border-radius: 5px;
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

.car-features {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Media Queries */
@media (max-width: 1024px) {
    .vehicle-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .date-time-group {
        grid-template-columns: 1fr;
    }
    
    .duration-display {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .booking-summary {
        padding: 0 20px;
    }
    
    .featured-vehicle {
        flex-direction: column;
        padding: 20px;
    }

    .vehicle-grid {
        grid-template-columns: 1fr;
    }
    
    .availability-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .availability-badge,
    .book-btn {
        width: 100%;
        text-align: center;
    }
}
</style>

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

    <!-- Booking Summary -->
    <div class="booking-summary">
        <div class="summary-container">
            <div class="summary-title">
                <i class="fas fa-calendar-alt"></i>
                Your Trip Details
            </div>
            
            <div class="date-time-group">
                <div class="summary-item">
                    <div class="summary-label">Pickup Date</div>
                    <div class="summary-value">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($pickupDate)->format('M d, Y') }}
                    </div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Pickup Time</div>
                    <div class="summary-value">
                        <i class="fas fa-clock"></i>
                        {{ date('g:i A', strtotime($pickupTime)) }}
                    </div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Return Date</div>
                    <div class="summary-value">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($returnDate)->format('M d, Y') }}
                    </div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Return Time</div>
                    <div class="summary-value">
                        <i class="fas fa-clock"></i>
                        {{ date('g:i A', strtotime($returnTime)) }}
                    </div>
                </div>
                
                @php
                    // Calculate duration (same as vehiclesIndex)
                    $pickupCarbon = \Carbon\Carbon::parse("$pickupDate $pickupTime");
                    $returnCarbon = \Carbon\Carbon::parse("$returnDate $returnTime");
                    $diffHours = $pickupCarbon->diffInHours($returnCarbon);
                    $days = floor($diffHours / 24);
                    $hours = $diffHours % 24;
                    
                    $durationText = '';
                    if ($days > 0) {
                        $durationText = $days . ' day' . ($days > 1 ? 's' : '');
                        if ($hours > 0) {
                            $durationText .= ' ' . $hours . ' hour' . ($hours > 1 ? 's' : '');
                        }
                    } else {
                        $durationText = $hours . ' hour' . ($hours != 1 ? 's' : '');
                    }
                @endphp
                
                <div class="duration-display">
                    <div class="duration-label">Trip Duration</div>
                    <div class="duration-value">{{ $durationText }}</div>
                </div>
            </div>
        </div>
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
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-user-friends"></i></div>
                    <div class="spec-value">{{ $featuredVehicle->seat }} Seats</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-wind"></i></div>
                    <div class="spec-value">{{ $featuredVehicle->ac == 1 ? 'AC' : 'No AC' }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-cog"></i></div>
                    <div class="spec-value">{{ $featuredVehicle->transmission }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-gas-pump"></i></div>
                    <div class="spec-value">{{ $featuredVehicle->fuelType }}</div>
                </div>
            </div>

            <!-- UPDATED BOOKING SECTION -->
            <div class="availability-section">
                @if($featuredVehicle->status == 'available')
                    <button class="availability-badge">Available!</button>
                    
                    @auth
                        <!-- Logged in users: Direct link to booking form -->
                        <a href="{{ route('booking.form', [
                            'vehicleID' => $featuredVehicle->vehicleID,
                            'pickup_date' => $pickupDate,
                            'pickup_time' => $pickupTime,
                            'return_date' => $returnDate,
                            'return_time' => $returnTime
                        ]) }}" class="book-btn">Book Now</a>
                    @else
                        <!-- Guest users: Button that stores booking and redirects to login -->
                        <button class="book-btn" onclick="handleGuestBooking()">Book Now</button>
                        
                        <script>
                            function handleGuestBooking() {
                                // Store booking details in session via AJAX
                                fetch('/store-pending-booking', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        vehicleID: '{{ $featuredVehicle->vehicleID }}',
                                        pickup_date: '{{ $pickupDate }}',
                                        pickup_time: '{{ $pickupTime }}',
                                        return_date: '{{ $returnDate }}',
                                        return_time: '{{ $returnTime }}'
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // Redirect to login page
                                    window.location.href = "{{ route('login') }}";
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    // Fallback: redirect with URL parameters
                                    const redirectUrl = "{{ route('booking.form', ['vehicleID' => $featuredVehicle->vehicleID]) }}" + 
                                        "?pickup_date={{ $pickupDate }}" +
                                        "&pickup_time={{ $pickupTime }}" +
                                        "&return_date={{ $returnDate }}" +
                                        "&return_time={{ $returnTime }}";
                                    
                                    window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(redirectUrl);
                                });
                            }
                        </script>
                    @endauth
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
                <span class="feature-item"><i class="fas fa-user-friends"></i> {{ $vehicle->seat ?? 5 }} seats</span>
                <span class="feature-item"><i class="fas fa-wind"></i> {{ $vehicle->ac ? 'AC' : 'No AC' }}</span>
                <span class="feature-item"><i class="fas fa-cog"></i> {{ $vehicle->transmission ?? 'Auto' }}</span>
                <span class="feature-item"><i class="fas fa-gas-pump"></i> {{ $vehicle->fuelType ?? 'Petrol' }}</span>
            </div>
        </div>

    </div>
    @endforeach
    </div>

<script>

function handleGuestBooking() {
    // Show loading state
    const bookBtn = document.querySelector('.book-btn');
    const originalText = bookBtn.textContent;
    bookBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    bookBtn.disabled = true;
    
    // Store booking details in session via AJAX
    fetch('/store-pending-booking', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            vehicleID: '{{ $featuredVehicle->vehicleID }}',
            pickup_date: '{{ $pickupDate }}',
            pickup_time: '{{ $pickupTime }}',
            return_date: '{{ $returnDate }}',
            return_time: '{{ $returnTime }}'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Redirect to login page
            window.location.href = "{{ route('login') }}";
        } else {
            throw new Error('Failed to store booking');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Restore button
        bookBtn.textContent = originalText;
        bookBtn.disabled = false;
        
        // Show error message
        alert('There was an error processing your booking. Please try again.');
        
        // Fallback: redirect with URL parameters
        const redirectUrl = "{{ route('booking.form', ['vehicleID' => $featuredVehicle->vehicleID]) }}" + 
            "?pickup_date={{ $pickupDate }}" +
            "&pickup_time={{ $pickupTime }}" +
            "&return_date={{ $returnDate }}" +
            "&return_time={{ $returnTime }}";
        
        // Ask user if they want to try the fallback method
        if (confirm('Would you like to try an alternative method?')) {
            window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(redirectUrl);
        }
    });
}

// Optional: Add a confirmation for guest users before redirecting to login
document.addEventListener('DOMContentLoaded', function() {
    // If there are guest booking buttons, add click handlers
    const guestBookButtons = document.querySelectorAll('.book-btn[onclick]');
    guestBookButtons.forEach(button => {
        // Remove the inline onclick and replace with event listener
        const originalOnclick = button.getAttribute('onclick');
        button.removeAttribute('onclick');
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show confirmation dialog for guests
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                if (confirm('You need to login to book this vehicle. Do you want to continue to login?')) {
                    handleGuestBooking();
                }
            }
        });
    });
    
    // Make other vehicle cards clickable to view their details
    const otherVehicleCards = document.querySelectorAll('.vehicle-card:not(.featured-vehicle .vehicle-card)');
    otherVehicleCards.forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            // Extract vehicle ID from the card (you might need to add data-vehicle-id attribute)
            const vehicleId = this.getAttribute('data-vehicle-id') || 
                             this.querySelector('a[href*="/vehicles/select/"]')?.href?.match(/\/vehicles\/select\/(\d+)/)?.[1];
            
            if (vehicleId) {
                // Redirect to this vehicle's select page with same dates
                window.location.href = `/vehicles/select/${vehicleId}?pickup_date={{ $pickupDate }}&pickup_time={{ $pickupTime }}&return_date={{ $returnDate }}&return_time={{ $returnTime }}`;
            }
        });
    });
});

// Optional: Add back button functionality to return to vehicles list
function goBackToVehicles() {
    // Return to vehicles index with the same search parameters
    window.location.href = "{{ route('vehicles.index') }}?pickup_date={{ $pickupDate }}&pickup_time={{ $pickupTime }}&return_date={{ $returnDate }}&return_time={{ $returnTime }}";
}

// Add event listener for Escape key to go back
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        goBackToVehicles();
    }
});
</script>

</body>
</html>
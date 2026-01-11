<!DOCTYPE html>
<html lang="en">
<head>
    <title>Available Vehicles - Hasta Travel & Tour</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vehicles.css') }}" rel="stylesheet">
</head>

<body>
    <div id="header">
    <a href="{{ url('/') }}">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}" alt="Logo">
    </a>

    <div id="menu">
        <a href="{{ url('/') }}"><button class="head_button">Home</button></a>
        <a href="{{ route('vehicles.index') }}"><button class="head_button">Vehicles</button></a>
        
    </div>

    <div id="profile">
        {{-- Profile Dropdown: Only show functionality if Auth, or simple links if Guest --}}
        <div id="profile-container">
            {{-- Default icon or User Avatar --}}
            <img id="pfp" src="{{ asset('img/racc_icon.png') }}" alt="Profile">

            <div id="profile-dropdown">
                @guest
                    {{-- Guest Links --}}
                    <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                    <a href="{{ route('register') }}" class="dropdown-item">Register</a>
                @endguest

                @auth
                    {{-- Authenticated Links --}}
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; color: #d94242; font-weight: bold;">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        {{-- Username Toggle --}}
        @guest
            <a id="username" href="{{ route('login') }}">Log in</a>
        @endguest

        @auth
            <span id="username">{{ Auth::user()->name }}</span>
        @endauth
    </div>
</div>

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
    
    <main>
        <div class="search-summary">
            <h3>Your Trip Details</h3>
            <div class="search-details">
                <div class="search-detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <strong>Pickup:</strong> {{ \Carbon\Carbon::parse($searchParams['pickup_date'])->format('M d, Y') }} at {{ date('g:i A', strtotime($searchParams['pickup_time'])) }}
                    </div>
                </div>
                <div class="search-detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <strong>Return:</strong> {{ \Carbon\Carbon::parse($searchParams['return_date'])->format('M d, Y') }} at {{ date('g:i A', strtotime($searchParams['return_time'])) }}
                    </div>
                </div>
                <div class="search-detail-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Duration:</strong> {{ $durationText ?? 'Calculating...' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="vehicles-container">
            <div class="vehicles-header">
                <div>
                    <h2>Available Vehicles</h2>
                    <div class="vehicles-count">{{ count($vehicles) }} vehicles available</div>
                </div>
                <div class="vehicle-filters">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="sedan">Sedan</button>
                    <button class="filter-btn" data-filter="hatchback">Hatchback</button>
                    <button class="filter-btn" data-filter="mpv">MPV</button>
                    <button class="filter-btn" data-filter="suv">SUV</button>
                    <button class="filter-btn" data-filter="suv">Motorcycle</button>
                </div>
            </div>

            @if(count($vehicles) > 0)
                <div class="vehicles-grid">
                    @foreach($vehicles as $vehicle)
                    <div class="vehicle-card" data-category="{{ strtolower($vehicle->category) }}">
                        @if($vehicle->vehiclePhoto)
                            <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" class="vehicle-image" alt="{{ $vehicle->model }}">
                        @else
                            <img src="{{ asset('img/default-car.jpg') }}" class="vehicle-image" alt="Default Car">
                        @endif
                        
                        <div class="vehicle-content">
                            <div class="vehicle-name">
                                <h4>{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                            </div>
                            
                            <div class="vehicle-details">
                                <div class="detail-row"><i class="fas fa-users"></i><span>{{ $vehicle->seat }}</span></div>
                                <div class="detail-row"><i class="fas fa-snowflake"></i><span>Yes</span></div>
                                <div class="detail-row"><i class="fas fa-cog"></i><span>{{ $vehicle->transmission == 'Automatic' ? 'Auto' : 'Man' }}</span></div>
                                <div class="detail-row"><i class="fas fa-gas-pump"></i><span>Full</span></div>
                                <div class="detail-row"><i class="fas fa-tint"></i><span>{{ $vehicle->fuelType }}</span></div>
                                <div class="detail-row"><i class="fas fa-car-side"></i><span>{{ $vehicle->vehicleType }}</span></div>
                            </div>
                            
                            <div class="vehicle-footer">
                                <div class="price-container">
                                    <span class="currency">MYR</span>
                                    <span class="price-value">{{ number_format($vehicle->pricePerDay, 2) }}</span>
                                    <span class="price-period">/day</span>
                                </div>
                                <!-- CHANGED: Book Now button -->
                                <a href="{{ route('booking.form', [
                                    'vehicleID' => $vehicle->vehicleID,
                                    'pickup_date' => $searchParams['pickup_date'] ?? '',
                                    'pickup_time' => $searchParams['pickup_time'] ?? '',
                                    'return_date' => $searchParams['return_date'] ?? '',
                                    'return_time' => $searchParams['return_time'] ?? ''
                                ]) }}" class="btn-book-now">
                                    <i class="fas fa-calendar-check"></i> BOOK NOW
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="no-vehicles">
                    <i class="fas fa-car"></i>
                    <h3>No Vehicles Available</h3>
                    <p>Try different dates or contact us for assistance.</p>
                    <a href="{{ url('/') }}" class="btn-book-now">Back to Search</a>
                </div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Existing Filtering Logic
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                const filter = this.getAttribute('data-filter');
                const vehicles = document.querySelectorAll('.vehicle-card');
                vehicles.forEach(vehicle => {
                    if (filter === 'all' || vehicle.getAttribute('data-category') === filter) {
                        vehicle.style.display = 'block';
                    } else {
                        vehicle.style.display = 'none';
                    }
                });
                const visibleCount = document.querySelectorAll('.vehicle-card[style*="block"], .vehicle-card:not([style])').length;
                document.querySelector('.vehicles-count').textContent = `${visibleCount} vehicles available`;
            });
        });
    </script>
</body>
</html>
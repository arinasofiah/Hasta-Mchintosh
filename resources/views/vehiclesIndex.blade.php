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
    @include('profile.partials.header')

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
                            
                           <!-- In your vehicles-grid section -->
                            <div class="vehicle-footer">
                                <div class="price-container">
                                    <span class="currency">MYR</span>
                                    <span class="price-value">{{ number_format($vehicle->pricePerDay, 2) }}</span>
                                </div>
                                <!-- CHANGE THIS LINE: Book Now → Select Vehicle -->
                                <a href="{{ route('selectVehicle', [
                                    'id' => $vehicle->vehicleID,
                                    'pickup_date' => $searchParams['pickup_date'] ?? '',
                                    'pickup_time' => $searchParams['pickup_time'] ?? '',
                                    'return_date' => $searchParams['return_date'] ?? '',
                                    'return_time' => $searchParams['return_time'] ?? ''
                                ]) }}" class="btn-select">Select Vehicle</a>
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
                    <a href="{{ url('/') }}" class="btn-select">Back to Search</a>
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
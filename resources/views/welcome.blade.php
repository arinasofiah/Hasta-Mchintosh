<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vehicles.css') }}" rel="stylesheet">
      <style>
        .browse-header {
            text-align: center;
            padding: 60px 20px 20px;
            background-color: #fff;
        }
    </style>
</head>
</head>

<body>
    
@if(session('error'))
    <div class="alert alert-danger text-center" style="margin: 0; padding: 12px; font-weight: 500;">
        {{ session('error') }}
    </div>
@endif

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

<div class="browse-header">
        <h1>Our Car Models</h1>
        <p>Explore our extensive range of car models from compact cars to spacious SUVs.</p>

        <form action="{{ url()->current() }}" method="GET" id="filterForm">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search Car Model..." onchange="this.form.submit()">
                </div>
            </div>

            <div class="filter-container">
                <input type="hidden" name="category" id="categoryInput" value="{{ request('category', 'All') }}">
                
                @php
                    $categories = [
                        'All' => 'All vehicles',
                        'Sedan' => ' Sedan',
                        'Hatchback' => 'Hatchback',
                        'MPV' => 'MPV',
                        'SUV' => 'SUV',
                        'Minivan' => 'Minivan'
                    ];
                @endphp

                @foreach($categories as $key => $label)
                    <button type="button" 
                            onclick="filterCategory('{{ $key }}')"
                            class="filter-pill {{ (request('category', 'All') == $key) ? 'active' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </form>
    </div>


<div id="body">
    <div class="car-grid"> <!-- Removed the extra < character -->
        @php
            // Ensure $vehicles is always defined
            $vehicles = $vehicles ?? collect();
        @endphp
        
        @forelse($vehicles as $vehicle)
            <div class="car-card"> <!-- ADD THIS OPENING DIV -->
                @if($vehicle->vehiclePhoto)
                    <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" 
                         alt="{{ $vehicle->model }}"
                         onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
                @else
                    <img src="{{ asset('img/vehicles/'.$vehicle->vehicleID.'.png') }}" 
                         alt="{{ $vehicle->model }}"
                         onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
                @endif   
                
                <h4>{{ $vehicle->model }}</h4>
                
                <div style="margin: 10px 0;">
                    <h3 style="color: #bc3737; margin: 5px 0;">RM{{ number_format($vehicle->pricePerDay, 2) }}/day</h3>
                </div>
                
                <div class="specs-grid">
                    <div class="spec-item" title="Seats">
                        <i class="fas fa-users"></i>
                        <span>{{ $vehicle->seat }}</span>
                    </div>
                    <div class="spec-item" title="Fuel Level">
                        <i class="fas fa-gas-pump"></i>
                        <span>{{ $vehicle->fuelType }}</span>
                    </div>
                    <div class="spec-item" title="Transmission">
                        <i class="fas fa-cog"></i>
                        <span>{{ str_contains(strtolower($vehicle->transmission), 'auto') ? 'Auto' : 'Manual' }}</span>
                    </div>
                    <div class="spec-item" title="AC">
                        <i class="fas fa-snowflake"></i>
                        <span>{{ $vehicle->ac ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
               
                <a href="{{ route('selectVehicle', $vehicle->vehicleID) }}" class="btn">
                    View Details & Book
                </a>
            </div> <!-- ADD THIS CLOSING DIV -->
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px;">
                <h3>No vehicles found</h3>
                <p>Try adjusting your filters or search keywords.</p>
            </div>
        @endforelse
    </div>
</div>

</div>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <div class="footer-logo">
                <img src="{{ asset('img/hasta_logo.jpg') }}" alt="Hasta Logo">
            </div>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-x-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-section contact-info">
            <div class="contact-item">
                <span class="contact-icon">📍</span>
                <div class="contact-text">
                    <span class="label">Address</span>
                    <p>Student Mall UTM<br>Skudai, 81300, Johor Bahru</p>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">✉️</span>
                <div class="contact-text">
                    <span class="label">Email</span>
                    <p><a href="mailto:hastatravel@gmail.com">hastatravel@gmail.com</a></p>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📞</span>
                <div class="contact-text">
                    <span class="label">Phone</span>
                    <p><a href="tel:01110900700">011-1090 0700</a></p>
                </div>
            </div>
        </div>

        <div class="footer-section links-column">
            <h5>Useful links</h5>
            <ul>
                <li><a href="#">About us</a></li>
                <li><a href="#">Contact us</a></li>
                <li><a href="#">Gallery</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">F.A.Q</a></li>
            </ul>
        </div>

        <div class="footer-section links-column">
            <h5>Vehicles</h5>
            <ul>
                <li><a href="#">Sedan</a></li>
                <li><a href="#">Hatchback</a></li>
                <li><a href="#">MPV</a></li>
                <li><a href="#">Minivan</a></li>
                <li><a href="#">SUV</a></li>
            </ul>
        </div>
    </div>
     <div class="copyright">

        © {{ date('Y') }} Hasta Travel & Tour. All rights reserved.

    </div>
</footer>
</body>
</html>
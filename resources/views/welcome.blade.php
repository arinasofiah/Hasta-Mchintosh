<!--<!DOCTYPE html>
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

<body>
    @include('profile.partials.header') <main>
        </main>
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
                       placeholder="Search Car Model..." id="searchInput">
                <button type="submit" style="display: none;"></button> 
            </div>
        </div>

        <div class="filter-container">
            <input type="hidden" name="category" id="categoryInput" value="{{ request('category', 'All') }}">
            
            @php
                $categories = [
                    'All' => 'All vehicles',
                    'Sedan' => 'Sedan',
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
    <div class="car-grid">
        @php
            // Ensure $vehicles is always defined
            $vehicles = $vehicles ?? collect();
        @endphp
        
        @forelse($vehicles as $vehicle)
            <div class="car-card">
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
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px;">
                <h3>No vehicles found</h3>
                <p>Try adjusting your filters or search keywords.</p>
                @if(request('category') || request('search'))
                    <a href="{{ url()->current() }}" class="btn" style="background-color: #bc3737; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">
                        Clear All Filters
                    </a>
                @endif
            </div>
        @endforelse
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

<script>
    // Function to handle category filtering
    function filterCategory(category) {
        // Update the hidden input value
        document.getElementById('categoryInput').value = category;
        
        // Remove active class from all buttons
        document.querySelectorAll('.filter-pill').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to clicked button
        event.target.classList.add('active');
        
        // Submit the form
        document.getElementById('filterForm').submit();
    }
    
    // Add event listener for search input (submit on Enter key or after delay)
    document.getElementById('searchInput').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            document.getElementById('filterForm').submit();
        }
    });
    
    // Optional: Add a debounced search (submit after user stops typing for 500ms)
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
    
    // Clear all filters button functionality
    function clearFilters() {
        window.location.href = "{{ url()->current() }}";
    }
</script>


</body>
</html>-->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <style>
        /* Add the same styles from your previous welcome page */
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; }
        .hero-wrapper { padding: 80px 20px; display: flex; justify-content: center; align-items: center; }
        .gradient-card { width: 100%; max-width: 800px; padding: 60px 40px; border-radius: 24px; background: linear-gradient(135deg, #FF6F6F 0%, #E63946 100%); box-shadow: 0 20px 40px rgba(230, 57, 70, 0.2); text-align: center; }
        .gradient-card h1 { color: white; font-size: 2.8rem; font-weight: 700; margin-bottom: 40px; }
        .booking-container { background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; overflow: hidden; }
        .tab-header { display: flex; background: #ffffff; border-bottom: 1px solid #eee; }
        .tab-item { padding: 15px 40px; font-weight: 700; color: #E63946; display: flex; align-items: center; gap: 10px; border-bottom: 4px solid #E63946; font-size: 0.9rem; }
        .booking-form { padding: 30px; display: flex; flex-direction: column; gap: 20px; align-items: center; }
        .datetime-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; width: 100%; max-width: 600px; }
        .datetime-group { display: flex; flex-direction: column; gap: 8px; }
        .datetime-label { font-size: 0.9rem; font-weight: 600; color: #333; display: flex; align-items: center; gap: 8px; }
        .datetime-label i { color: #E63946; }
        .datetime-inputs { display: flex; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: all 0.3s ease; height: 50px; }
        .datetime-inputs:hover { border-color: #E63946; box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.1); }
        .date-input { border: none; padding: 0 15px; flex: 1; outline: none; font-size: 0.9rem; font-family: 'Poppins', sans-serif; color: #333; background: white; }
        .time-select { border: none; border-left: 1px solid #eee; padding: 0 15px; outline: none; font-size: 0.9rem; background-color: #fafafa; font-family: 'Poppins', sans-serif; cursor: pointer; min-width: 100px; color: #333; background: white; }
        .btn-search { background: #2c3e50; color: white; border: none; padding: 14px 40px; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .btn-search:hover { background: #1a252f; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        @media (max-width: 768px) { .datetime-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('profile.partials.header')
    
    <main class="hero-wrapper">
        <div class="gradient-card">
            <h1>Rent A Car in Malaysia</h1>
            <div class="booking-container">
                <div class="tab-header">
                    <div class="tab-item"><i class="fas fa-car"></i> CAR</div>
                </div>
                
                <!-- FORM GOES TO VEHICLES.INDEX -->
                <form action="{{ route('vehicles.index') }}" method="GET" class="booking-form">
                    <div class="datetime-grid">
                        <div class="datetime-group">
                            <div class="datetime-label"><i class="fas fa-calendar-alt"></i><span>Pickup Date & Time</span></div>
                            <div class="datetime-inputs">
                                <input type="date" name="pickup_date" class="date-input" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                                <select name="pickup_time" class="time-select" required>
                                    <option value="08:00">8:00 AM</option><option value="09:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option><option value="11:00">11:00 AM</option>
                                    <option value="12:00" selected>12:00 PM</option><option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option><option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option><option value="17:00">5:00 PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="datetime-group">
                            <div class="datetime-label"><i class="fas fa-calendar-alt"></i><span>Return Date & Time</span></div>
                            <div class="datetime-inputs">
                                <input type="date" name="return_date" class="date-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                <select name="return_time" class="time-select" required>
                                    <option value="08:00">8:00 AM</option><option value="09:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option><option value="11:00">11:00 AM</option>
                                    <option value="12:00" selected>12:00 PM</option><option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option><option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option><option value="17:00">5:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search Available Vehicles</button>
                </form>
            </div>
        </div>
    </main>
    
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
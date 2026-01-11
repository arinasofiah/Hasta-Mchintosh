<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour - Customer Dashboard</title>
    <<meta charset="utf-8">
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
                <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
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
    </div>
</div>

<main class="hero-wrapper">
        <div class="gradient-card">
            <h1>Rent A Car in Malaysia</h1>
            <div class="booking-container">
                <div class="tab-header">
                    <div class="tab-item"><i class="fas fa-car"></i> CAR</div>
                </div>
                
                <!-- FORM GOES TO VEHICLES.INDEX -->
                <form action="{{ route('vehicles.index') }}" method="GET" class="booking-form" id="bookingForm">
                    <div class="datetime-grid">
                        <div class="datetime-group">
                            <div class="datetime-label"><i class="fas fa-calendar-alt"></i><span>Pickup Date & Time</span></div>
                            <div class="datetime-inputs">
                                <input type="date" name="pickup_date" id="pickup_date" class="date-input" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                                <select name="pickup_time" id="pickup_time" class="time-select" required>
                                    <option value="09:00" selected>9:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                    <option value="17:00">5:00 PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="datetime-group">
                            <div class="datetime-label"><i class="fas fa-calendar-alt"></i><span>Return Date & Time</span></div>
                            <div class="datetime-inputs">
                                <input type="date" name="return_date" id="return_date" class="date-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                <select name="return_time" id="return_time" class="time-select" required>
                                    <option value="09:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00" selected>12:00 PM</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                    <option value="17:00">5:00 PM</option>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pickupDateInput = document.getElementById('pickup_date');
            const pickupTimeSelect = document.getElementById('pickup_time');
            const returnDateInput = document.getElementById('return_date');
            const returnTimeSelect = document.getElementById('return_time');
            
            // Set initial minimum return date (tomorrow)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            returnDateInput.min = tomorrow.toISOString().split('T')[0];
            
            // Function to auto-set return date when pickup date changes
            function updateReturnDate() {
                if (pickupDateInput.value) {
                    const pickupDate = new Date(pickupDateInput.value);
                    const returnDate = new Date(pickupDate);
                    returnDate.setDate(pickupDate.getDate() + 1);
                    
                    // Format date as YYYY-MM-DD
                    const formattedReturnDate = returnDate.toISOString().split('T')[0];
                    
                    // Set min and value for return date
                    returnDateInput.min = formattedReturnDate;
                    returnDateInput.value = formattedReturnDate;
                }
            }
            
            // Function to validate and adjust return time based on pickup time
            function updateReturnTimeOptions() {
                const pickupTime = pickupTimeSelect.value;
                const pickupHour = parseInt(pickupTime.split(':')[0]);
                
                // Clear current options
                returnTimeSelect.innerHTML = '';
                
                // Generate time slots from 9 AM to 5 PM
                const timeSlots = [
                    '09:00', '10:00', '11:00', '12:00', 
                    '13:00', '14:00', '15:00', '16:00', '17:00'
                ];
                
                // Add options, disabling times earlier than or equal to pickup time for same day
                timeSlots.forEach(time => {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = formatTimeDisplay(time);
                    
                    // Check if pickup and return are on the same day
                    const isSameDay = pickupDateInput.value === returnDateInput.value;
                    
                    if (isSameDay) {
                        const returnHour = parseInt(time.split(':')[0]);
                        // Disable return times that are before or equal to pickup time
                        if (returnHour <= pickupHour) {
                            option.disabled = true;
                        }
                    }
                    
                    // Set default selection to 12:00 PM if it's enabled
                    if (time === '12:00' && !option.disabled) {
                        option.selected = true;
                    }
                    
                    returnTimeSelect.appendChild(option);
                });
                
                // If no option is selected, select the first enabled option
                if (!returnTimeSelect.value) {
                    const firstEnabledOption = Array.from(returnTimeSelect.options).find(opt => !opt.disabled);
                    if (firstEnabledOption) {
                        firstEnabledOption.selected = true;
                    }
                }
            }
            
            // Helper function to format time for display
            function formatTimeDisplay(time) {
                const [hour, minute] = time.split(':');
                const hourNum = parseInt(hour);
                const ampm = hourNum >= 12 ? 'PM' : 'AM';
                const displayHour = hourNum % 12 || 12;
                return `${displayHour}:${minute} ${ampm}`;
            }
            
            // Event listeners
            pickupDateInput.addEventListener('change', function() {
                updateReturnDate();
                updateReturnTimeOptions();
            });
            
            pickupTimeSelect.addEventListener('change', updateReturnTimeOptions);
            returnDateInput.addEventListener('change', updateReturnTimeOptions);
            
            // Initialize on page load
            updateReturnTimeOptions();
            
            // Form validation
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                const pickupDate = new Date(pickupDateInput.value);
                const returnDate = new Date(returnDateInput.value);
                const pickupTime = pickupTimeSelect.value;
                const returnTime = returnTimeSelect.value;
                
                // Check if return date is before pickup date
                if (returnDate < pickupDate) {
                    e.preventDefault();
                    alert('Return date cannot be before pickup date!');
                    return;
                }
                
                // Check if return time is before pickup time on same day
                if (pickupDate.toDateString() === returnDate.toDateString() && returnTime <= pickupTime) {
                    e.preventDefault();
                    alert('Return time must be after pickup time for same day rentals!');
                    return;
                }
            });
        });
    </script>
</body>
</html>
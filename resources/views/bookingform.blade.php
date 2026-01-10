<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>HASTA – Booking Details</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/header.css') }}" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ====== Reset & Body ====== */
* {margin:0;padding:0;box-sizing:border-box;}
body {font-family:'Inter',sans-serif;background:#f5f5f5;color:#333;padding-bottom:120px;}


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

/* ====== Container ====== */
.container {max-width:1200px; margin:0 auto; padding:0 20px; display:grid; grid-template-columns:1fr; gap:20px;}

/* ====== Card ====== */
.card {background:white; border-radius:10px; padding:30px; border:2px solid #eee;}

/* ====== Labels & Inputs ====== */
.section-title {font-weight:700; margin-bottom:15px; font-size:18px;}
.row2 {display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:20px;}
.field-label {font-size:12px; font-weight:600; color:#666; margin-bottom:7px;}
.input {width:100%; padding:12px 15px; border:1px solid #ddd; border-radius:5px; font-size:14px; font-family:'Inter',sans-serif;}
.input:focus {outline:none; border-color:#d94242;}
textarea.input {resize:vertical; min-height:100px;}
.duration-box .input {background:#f7f7f7; font-weight:600; cursor:not-allowed;}

/* Map Button */
.map-btn {
    background: white;
    border: 1px solid #d94242;
    color: #d94242;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.2s;
}
.map-btn:hover {
    background: #d94242;
    color: white;
}

/* ====== Summary ====== */
.summary-box {background:white; border-radius:10px; padding:25px; border:2px solid #eee;}
.summary-title {color:#d94242; font-weight:700; margin-bottom:15px;}
.charge-row {display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px;}
.charge-row.grand {font-weight:800; font-size:15px; border-top:2px solid #333; padding-top:10px; margin-top:10px;}

/* ====== Bottom Bar ====== */
.bottom-bar {position:fixed; bottom:0; left:0; right:0; background:white; border-top:1px solid #eee; display:flex; justify-content:space-between; align-items:center; padding:15px 60px; box-shadow:0 -2px 10px rgba(0,0,0,0.05);}
.selected-car {display:flex; align-items:center; gap:20px;}
.car-badge {background:#ffe5e5; color:#d94242; font-size:11px; padding:5px 12px; border-radius:20px; font-weight:600; margin-bottom:5px;}
.grand-total-bottom {text-align:right;}
.grand-label {color:#999; font-size:14px; margin-bottom:5px;}
.grand-amount {font-size:20px; font-weight:900;}
.next-btn {background:#d94242; color:white; border:none; padding:12px 30px; border-radius:5px; font-size:16px; font-weight:600; cursor:pointer;}
.next-btn:hover {background:#c23535;}
#profile-container {
    position: relative;
    display: inline-block;
}

#profile-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 10px 0;
    z-index: 1000;
    margin-top: 10px;
}

.hint {
    font-size: 11px;
    color: #999;
    margin-top: 3px;
    display: block;
}

@media(max-width:768px){
    .container{grid-template-columns:1fr;}
    .bottom-bar{flex-wrap:wrap; gap:15px; padding:15px 20px;}
}
</style>
</head>
<body>

<!-- HEADER -->
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
    <div id="profile-container" aria-haspopup="true" aria-expanded="false">
        <img id="pfp" src="{{ asset('img/racc_icon.png') }}" alt="Profile Picture" role="button" tabindex="0">
        
        <div id="profile-dropdown" role="menu" aria-label="User menu">
            @guest
                <a href="{{ route('login') }}" class="dropdown-item" role="menuitem">Login</a>
                <a href="{{ route('register') }}" class="dropdown-item" role="menuitem">Register</a>
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
        <div class="step filled">
            <i class="fas fa-car"></i>
            <span>Vehicle</span>
        </div>
        <div class="step-connector"></div>
        <div class="step active">
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

<!-- FORM START -->
<form id="bookingForm" action="{{ route('payment.form', $vehicle->vehicleID )}}" method="POST">
    @csrf
    <input type="hidden" name="vehicleID" value="{{ $vehicle->vehicleID }}">
    
    <div class="container">
        <!-- Booking Details Card -->
        <div class="card">
           <div class="section-title">Pickup</div>
<div class="row2">
    <div>
        <p><b>Date:</b> {{ $pickupDate ?? '' }}</p>
        <input type="hidden" name="pickup_date" value="{{ $pickupDate ?? '' }}">
    </div>
    <div>
        <p><b>Time:</b> {{ $pickupTime ?? '' }}</p>
        <input type="hidden" name="pickup_time" value="{{ $pickupTime ?? '' }}">
    </div>
</div>

<div class="section-title">Return</div>
<div class="row2">
    <div>
        <p><b>Date:</b> {{ $returnDate ?? '' }}</p>
        <input type="hidden" name="return_date" value="{{ $returnDate ?? '' }}">
    </div>
    <div>
        <p><b>Time:</b> {{ $returnTime ?? '' }}</p>
        <input type="hidden" name="return_time" value="{{ $returnTime ?? '' }}">
    </div>
</div>

            <div class="row2">
                <div>
                    <div class="field-label">Pickup Location</div>
                    <select id="pickupLocationType" class="input" required onchange="handleLocationChange('pickup')">
                        <option value="">-- Select --</option>
                        <option value="hasta">HASTA Office</option>
                        <option value="student_mall">Student Mall</option>
                        <option value="others">Others (within UTM)</option>
                    </select>
                    <input type="hidden" id="pickupLocation" name="pickupLocation">
                    <div id="pickupDeliveryNotice" style="color: #d9534f; font-size: 0.9em; margin-top: 5px; display: none;">
                        ⚠️ Delivery charge of RM15 applies.
                    </div>

                    <div id="pickupOthersFields" style="display: none; margin-top: 10px;">
                        <select id="pickupCategory" class="input" required>
                            <option value="">-- Faculty / College / Office --</option>
                            <option value="faculty">Faculty</option>
                            <option value="college">College</option>
                            <option value="office">Office</option>
                        </select>
                        <input type="text" id="pickupDetails" class="input" placeholder="e.g., K01, Kolej Tun Razak" style="margin-top: 5px;" required>
                    </div>
                </div>

                <div>
                    <div class="field-label">Return Location</div>
                        <select id="returnLocationType" class="input" required onchange="handleLocationChange('return')">
                            <option value="">-- Select --</option>
                            <option value="hasta">HASTA Office, Student Mall</option>
                            <option value="others">Others (within UTM)</option>
                        </select>

                        <input type="hidden" id="returnLocation" name="returnLocation">

                        <div id="returnDeliveryNotice" style="color: #d9534f; font-size: 0.9em; margin-top: 5px; display: none;">
                            ⚠️ Delivery charge of RM15 applies.
                        </div>

                        <div id="returnOthersFields" style="display: none; margin-top: 10px;">
                            <select id="returnCategory" class="input" required>
                                <option value="">-- Faculty / College / Office --</option>
                                <option value="faculty">Faculty</option>
                                <option value="college">College</option>
                                <option value="office">Office</option>
                            </select>
                            <input type="text" id="returnDetails" class="input" placeholder="e.g., N28, FC" style="margin-top: 5px;" required>
                        </div>
                </div>
            </div>

            <div class="row2">
                <div>
                    <div class="field-label">Destination</div>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="destination" name="destination" class="input" placeholder="Enter destination">
                    </div>
                </div>

                <div class="duration-box">
                    <div class="field-label">Duration</div>
                    <input type="text" id="durationInput" class="input" readonly>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <div class="field-label">Remark</div>
                <textarea name="remark" class="input" rows="4" placeholder="Any special notes or requirements"></textarea>
            </div>

            <div style="margin-top: 20px;">
                <label style="font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" id="forSomeoneElse" name="for_someone_else" value="1" onchange="toggleDriverInfo()">
                    <span>I am not a driver for this vehicle. I am making this reservation for someone else.</span>
                </label>
            </div>

            <!-- Driver Info Section (Hidden by default) -->
            <div id="driverInfoSection" style="display: none; border: 1px solid #ddd; padding: 15px; margin-top: 15px; border-radius: 5px; background: #f9f9f9;">
                <h5 style="margin-bottom: 15px;">Driver Information</h5>
                
                <!-- Success/Error Messages -->
                <div id="driverMessage" style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 5px;"></div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">Email Address</div>
                    <input type="email" id="driverEmail" name="driver_email" class="input" placeholder="e.g. driver@gmail.com">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">Full Name</div>
                    <input type="text" id="driverName" name="driver_name" class="input" placeholder="Enter full name (as per IC)">
                    <small class="hint">Ensure it matches IC (Capital Letters).</small>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">IC/Passport Number</div>
                    <input type="text" id="driverIC" name="driver_ic" class="input" placeholder="e.g. 990101-01-1234">
                    <small class="hint">With dash (-).</small>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">Phone Number</div>
                    <input type="tel" id="driverPhone" name="driver_phone" class="input" placeholder="e.g. 0196507378">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">Matric Number</div>
                    <input type="text" id="matricNumber" name="matricNumber" class="input" placeholder="e.g. A21CS0001">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">License Number</div>
                    <input type="text" id="licenseNumber" name="licenseNumber" class="input" placeholder="Enter license number">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">College</div>
                    <input type="text" id="college" name="college" class="input" placeholder="Enter college">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">Faculty</div>
                    <input type="text" id="faculty" name="faculty" class="input" placeholder="Enter faculty">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div class="field-label">Deposit Balance (RM)</div>
                    <input type="number" step="0.01" id="depoBalance" name="depoBalance" class="input" placeholder="0.00">
                </div>
                
                <button type="button" id="registerDriverBtn" class="next-btn" style="width: 100%;">
                    Register Driver
                </button>
            </div>
        </div>

        <!-- Charges Card -->
        <div class="card summary-box">
            <div class="summary-title">Summary of Charges</div>

            <div class="charge-row">
                <span>Price Per Day</span>
                <span>MYR {{ number_format($vehicle->pricePerDay, 2) }}</span>
            </div>
            <div class="charge-row">
                <span>Price Per Hour</span>
                <span>MYR {{ number_format($vehicle->pricePerHour, 2) }}</span>
            </div>
            <div class="charge-row">
                <span>Rental Duration</span>
                <span id="durationDisplay">-</span>
            </div>
            <div class="charge-row">
                <span>Delivery Charge</span>
                <span id="deliveryCharge">MYR 0.00</span>
            </div>
            <div class="charge-row">
                <span>Total Price (by hour)</span>
                <span id="totalByHour">MYR 0.00</span>
            </div>
            <div class="charge-row" style="color: #28a745;">
                <span>Promotion Discount</span>
                <span id="promotionDiscount">- MYR 0.00</span>
            </div>
            <div class="charge-row grand">
                <span>Grand Total</span>
                <span id="grandTotal">MYR 0.00</span>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="bottom-bar">
        <div class="selected-car">
            @if($vehicle->vehiclePhoto)
                <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" width="60" style="object-fit:contain;" alt="Car">
            @else
                <img src="{{ asset('img/car-axia.png') }}" width="60" style="object-fit:contain;" alt="Car">
            @endif
            <div>
                <div class="car-badge">Selected Car</div>
                <b>{{ $vehicle->model }}</b>
            </div>
        </div>
        <div class="grand-total-bottom">
            <div class="grand-label">Grand Total</div>
            <div class="grand-amount" id="bottomBarTotal">MYR 0.00</div>
        </div>
        <div>
            <button type="button" class="next-btn" onclick="goToPayment()">Next →</button>
        </div>
    </div>
</form>

<script>
// ===== GLOBAL VARIABLES =====
let baseGrandTotal = 0;
let promotionDiscount = 0;
let deliveryCharge = 0; // ← Now global

const pricePerHour = {{ $vehicle->pricePerHour }};
const pricePerDay = {{ $vehicle->pricePerDay }};

// ===== LOCATION HANDLING =====
function handleLocationChange(type) {
    const locType = document.getElementById(`${type}LocationType`).value;
    const notice = document.getElementById(`${type}DeliveryNotice`);
    const othersFields = document.getElementById(`${type}OthersFields`);
    const hiddenInput = document.getElementById(`${type}Location`);

    hiddenInput.value = '';

    if (locType === 'hasta') {
        hiddenInput.value = 'HASTA Office, Student Mall';
        notice.style.display = 'none';
        othersFields.style.display = 'none';
    } else if (locType === 'others') {
        notice.style.display = 'block';
        othersFields.style.display = 'block';
    } else {
        notice.style.display = 'none';
        othersFields.style.display = 'none';
    }

    updateDeliveryCharge();
}

function updateDeliveryCharge() {
    let charge = 0;
    const pickupType = document.getElementById('pickupLocationType').value;
    const returnType = document.getElementById('returnLocationType').value;

    if (pickupType === 'others') charge += 15;
    if (returnType === 'others') charge += 15;

    deliveryCharge = charge;
    recalculateTotal(); // Update total whenever delivery changes
}

// Update hidden location input when user types details
document.addEventListener('DOMContentLoaded', () => {
    ['pickup', 'return'].forEach(type => {
        const detailsInput = document.getElementById(`${type}Details`);
        const categorySelect = document.getElementById(`${type}Category`);

        if (detailsInput && categorySelect) {
            const updateLocation = () => {
                const details = detailsInput.value.trim();
                const category = categorySelect.value;
                let fullLocation = '';

                if (category && details) {
                    const labels = { faculty: 'Faculty', college: 'College', office: 'Office' };
                    fullLocation = `${labels[category]}: ${details}`;
                }
                document.getElementById(`${type}Location`).value = fullLocation;
            };

            detailsInput.addEventListener('input', updateLocation);
            categorySelect.addEventListener('change', updateLocation);
        }
    });
});

// ===== PRICING & TOTAL CALCULATION =====
function calculateDurationAndPrice() {
    const pickupDateInput = document.querySelector('input[name="pickup_date"]');
    const pickupTimeInput = document.querySelector('input[name="pickup_time"]');
    const returnDateInput = document.querySelector('input[name="return_date"]');
    const returnTimeInput = document.querySelector('input[name="return_time"]');

    if (!pickupDateInput || !pickupTimeInput || !returnDateInput || !returnTimeInput) {
        console.error('Missing date/time inputs');
        return;
    }

    const pickupDate = pickupDateInput.value;
    const pickupTime = pickupTimeInput.value;
    const returnDate = returnDateInput.value;
    const returnTime = returnTimeInput.value;

    if (!pickupDate || !pickupTime || !returnDate || !returnTime) {
        console.error('Missing date/time values');
        return;
    }

    const pickup = new Date(`${pickupDate}T${pickupTime}`);
    const returnDT = new Date(`${returnDate}T${returnTime}`);

    if (isNaN(pickup.getTime()) || isNaN(returnDT.getTime())) {
        console.error('Invalid date format');
        return;
    }

    if (returnDT <= pickup) {
        console.error('Return date must be after pickup date');
        return;
    }

    const diffMs = returnDT - pickup;
    const diffHours = Math.ceil(diffMs / (1000 * 60 * 60));
    const days = Math.floor(diffHours / 24);
    const remainingHours = diffHours % 24;

    const durationText = days > 0
        ? `${days} day${days > 1 ? 's' : ''} ${remainingHours} hour${remainingHours !== 1 ? 's' : ''}`
        : `${diffHours} hour${diffHours !== 1 ? 's' : ''}`;

    const durationInput = document.getElementById('durationInput');
    const durationDisplay = document.getElementById('durationDisplay');
    if (durationInput) durationInput.value = durationText;
    if (durationDisplay) durationDisplay.textContent = durationText;

    // Calculate base rental cost
    const totalByHour = diffHours * {{ $vehicle->pricePerHour }};
    baseGrandTotal = (days * {{ $vehicle->pricePerDay }}) + (remainingHours * {{ $vehicle->pricePerHour }});

    const totalByHourEl = document.getElementById('totalByHour');
    if (totalByHourEl) {
        totalByHourEl.textContent = `MYR ${totalByHour.toFixed(2)}`;
    }

    console.log('Duration Hours:', diffHours);
    console.log('Base Grand Total:', baseGrandTotal);

    // Trigger promotion check AFTER base total is set
    checkPromotion();
}

function checkPromotion() {
    const today = new Date();
    const dayName = today.toLocaleDateString('en-US', { weekday: 'long' });

    fetch('/check-promotion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            day: dayName,
            amount: baseGrandTotal
        })
    })
    .then(response => response.json())
    .then(data => {
        promotionDiscount = data.hasPromotion ? data.discount : 0;

        const promoDiscountEl = document.getElementById('promotionDiscount');
        if (promoDiscountEl) {
            promoDiscountEl.textContent = data.hasPromotion 
                ? `- RM ${promotionDiscount.toFixed(2)}` 
                : '- RM 0.00';
            promoDiscountEl.style.color = data.hasPromotion ? '#28a745' : '#333';
        }

        // Set promo ID if exists
        let promoInput = document.getElementById('appliedPromoId');
        if (data.hasPromotion) {
            if (!promoInput) {
                promoInput = document.createElement('input');
                promoInput.type = 'hidden';
                promoInput.id = 'appliedPromoId';
                promoInput.name = 'promo_id';
                document.querySelector('form').appendChild(promoInput);
            }
            promoInput.value = data.promoID;
        } else if (promoInput) {
            promoInput.remove();
        }

        recalculateTotal();
    })
    .catch(error => {
        console.error('Error checking promotion:', error);
        promotionDiscount = 0;
        recalculateTotal();
    });
}

function recalculateTotal() {
    const finalTotal = baseGrandTotal + deliveryCharge - promotionDiscount;

    // Update delivery charge display
    const deliveryChargeEl = document.getElementById('deliveryCharge');
    if (deliveryChargeEl) {
        deliveryChargeEl.textContent = `RM ${deliveryCharge.toFixed(2)}`;
    }

    // Update grand total
    const grandTotalEl = document.getElementById('grandTotal');
    const bottomBarTotalEl = document.getElementById('bottomBarTotal');
    if (grandTotalEl) grandTotalEl.textContent = `RM ${finalTotal.toFixed(2)}`;
    if (bottomBarTotalEl) bottomBarTotalEl.textContent = `RM ${finalTotal.toFixed(2)}`;

    window.finalTotal = finalTotal;
    console.log('Final Total (with delivery & promo):', finalTotal);
}

// ===== OTHER FUNCTIONS (toggleDriverInfo, registerDriver, goToPayment) =====
function toggleDriverInfo() {
    const checkbox = document.getElementById('forSomeoneElse');
    const section = document.getElementById('driverInfoSection');
    const inputs = section.querySelectorAll('input:not([type="button"])');
    
    if (checkbox.checked) {
        section.style.display = 'block';
        inputs.forEach(input => {
            if (input.id !== 'depoBalance' && input.id !== 'licenseNumber') {
                input.setAttribute('required', 'required');
            }
        });
    } else {
        section.style.display = 'none';
        inputs.forEach(input => {
            input.removeAttribute('required');
            input.value = '';
        });
        document.getElementById('driverMessage').style.display = 'none';
    }
}

document.getElementById('registerDriverBtn')?.addEventListener('click', function() {
    const button = this;
    const message = document.getElementById('driverMessage');
    
    const driverData = {
        email: document.getElementById('driverEmail').value,
        name: document.getElementById('driverName').value,
        icNumber: document.getElementById('driverIC').value,
        phone: document.getElementById('driverPhone').value,
        matricNumber: document.getElementById('matricNumber').value,
        licenseNumber: document.getElementById('licenseNumber').value,
        college: document.getElementById('college').value,
        faculty: document.getElementById('faculty').value,
        depoBalance: document.getElementById('depoBalance').value,
        _token: '{{ csrf_token() }}'
    };
    
    if (!driverData.email || !driverData.name || !driverData.icNumber || !driverData.phone || !driverData.matricNumber) {
        showMessage('Please fill in all required fields.', 'error');
        return;
    }
    
    button.disabled = true;
    button.textContent = 'Registering...';
    
    fetch('/register-customer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(driverData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Driver registered successfully!', 'success');
            document.querySelectorAll('#driverInfoSection input').forEach(input => {
                input.setAttribute('readonly', 'readonly');
            });
            button.textContent = 'Registered ✓';
        } else {
            showMessage(data.message || 'Registration failed. Please try again.', 'error');
            button.disabled = false;
            button.textContent = 'Register Driver';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
        button.disabled = false;
        button.textContent = 'Register Driver';
    });
});

function showMessage(text, type) {
    const message = document.getElementById('driverMessage');
    message.textContent = text;
    message.style.display = 'block';
    message.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
    message.style.color = type === 'success' ? '#155724' : '#721c24';
    message.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
}

function goToPayment() {
    const form = document.getElementById('bookingForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    if (!document.getElementById('pickupLocation').value || !document.getElementById('returnLocation').value) {
        alert('Please fill in both pickup and return locations');
        return;
    }
    if (typeof window.finalTotal === 'undefined') {
        alert('Please wait for pricing calculation to complete');
        return;
    }
    document.querySelectorAll('input.dynamic-field').forEach(el => el.remove());
    const hiddenFields = [
        { name: 'subtotal', value: baseGrandTotal || 0 },
        { name: 'promotionDiscount', value: promotionDiscount || 0 },
        { name: 'deliveryCharge', value: deliveryCharge || 0 },
        { name: 'total', value: window.finalTotal || 0 },
        { name: 'duration', value: document.getElementById('durationInput').value },
        { name: 'promo_id', value: document.getElementById('appliedPromoId')?.value || '' }
    ];
    hiddenFields.forEach(field => {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = field.name;
        input.value = field.value;
        input.classList.add('dynamic-field');
        form.appendChild(input);
    });
    console.log('Submitting form with calculated data');
    form.submit();
}

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    calculateDurationAndPrice();
});
</script>

</body>
</html>
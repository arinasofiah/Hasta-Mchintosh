<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>HASTA – Booking Details</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ====== Reset & Body ====== */
* {margin:0;padding:0;box-sizing:border-box;}
body {font-family:'Inter',sans-serif;background:#f5f5f5;color:#333;padding-bottom:120px;}

/* ====== Header ====== */
#header {background-color: #d94242; padding: 15px 50px; display:flex; justify-content:space-between; align-items:center;}
#logo {height:45px;}
#profile {display:flex; align-items:center; gap:15px;}
#profile-container {width:45px; height:45px; background:white; border-radius:50%; overflow:hidden;}
#pfp {width:100%; height:100%; object-fit:cover;}

/* ====== Progress Steps ====== */
.progress-container {max-width:1200px; margin:50px auto; padding:0 20px;}
.steps {display:flex; justify-content:center; align-items:center; margin-bottom:50px;}
.step {display:flex; align-items:center; gap:10px; padding:15px 40px; border:2px solid #ddd; border-radius:50px; background:white; color:#999; font-weight:600;}
.step.filled {border-color:#d94242; background:#d94242; color:white;}
.step.active {border-color:#d94242; color:#d94242;}
.step-connector {width:100px; height:2px; background-color:#ddd;}

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
.location-input-wrapper {position:relative;}
.location-input-wrapper .input {padding-right:40px;}
.map-icon {position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#d94242; cursor:pointer; font-size:18px; transition:transform 0.2s;}
.map-icon:hover {transform:translateY(-50%) scale(1.2);}

/* ====== Summary ====== */
.summary-box {background:white; border-radius:10px; padding:25px; border:2px solid #eee;}
.summary-title {color:#d94242; font-weight:700; margin-bottom:15px;}
.charge-row {display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px;}
.charge-row.grand {font-weight:800; font-size:15px; border-top:2px solid #333; padding-top:10px;}

/* ====== Bottom Bar ====== */
.bottom-bar {position:fixed; bottom:0; left:0; right:0; background:white; border-top:1px solid #eee; display:flex; justify-content:space-between; align-items:center; padding:15px 60px; box-shadow:0 -2px 10px rgba(0,0,0,0.05);}
.selected-car {display:flex; align-items:center; gap:20px;}
.car-badge {background:#ffe5e5; color:#d94242; font-size:11px; padding:5px 12px; border-radius:20px; font-weight:600; margin-bottom:5px;}
.grand-total-bottom {text-align:right;}
.grand-label {color:#999; font-size:14px; margin-bottom:5px;}
.grand-amount {font-size:20px; font-weight:900;}
.next-btn {background:#d94242; color:white; border:none; padding:12px 30px; border-radius:5px; font-size:16px; font-weight:600; cursor:pointer;}
.next-btn:hover {background:#c23535;}
@media(max-width:768px){.container{grid-template-columns:1fr;}.bottom-bar{flex-wrap:wrap; gap:15px; padding:15px 20px;}}
</style>
</head>
<body>

<!-- HEADER -->
<div id="header">
    <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
    <div id="profile">
        <div id="profile-container">
            <img id="pfp" src="{{ asset('img/racc_icon.png') }}">
        </div>
    </div>
</div>

<!-- Progress Steps -->
<div class="progress-container">
    <div class="steps">
        <div class="step filled"><span class="step-icon">✓</span><span>Vehicle</span></div>
        <div class="step-connector"></div>
        <div class="step active"><span class="step-icon">✓</span><span>Booking Details</span></div>
        <div class="step-connector"></div>
        <div class="step"><span class="step-icon">✓</span><span>Payment</span></div>
    </div>
</div>

<!-- FORM START -->
<form action="{{ route('booking.store', $vehicle->vehicleID) }}" method="POST">
    @csrf
<div class="container">

    <!-- Pickup & Return Card -->
    <div class="card">

        <div class="section-title">Pickup</div>
        <div class="row2">
            <div>
                <p><b>Date:</b> {{ $pickupDate }}</p>
                <input type="hidden" name="pickup_date" value="{{ $pickupDate }}">
            </div>
            <div>
                <p><b>Time:</b> {{ $pickupTime }}</p>
                <input type="hidden" name="pickup_time" value="{{ $pickupTime }}">
            </div>
        </div>

        <div class="section-title">Return</div>
        <div class="row2">
            <div>
                <p><b>Date:</b> {{ $returnDate }}</p>
                <input type="hidden" name="return_date" value="{{ $returnDate }}">
            </div>
            <div>
                <p><b>Time:</b> {{ $returnTime }}</p>
                <input type="hidden" name="return_time" value="{{ $returnTime }}">
            </div>
        </div>

        <div class="row2">
            <div>
                <div class="field-label">Destination</div>
                <input type="text" name="destination" class="input" placeholder="Full Address">
            </div>

            <div class="duration-box">
                <div class="field-label">Duration</div>
                <input type="text" class="input" readonly>
            </div>
        </div>

        <div class="row2">
            <div class="location-input-wrapper">
                <div class="field-label">Pickup Location</div>
                <input type="text" name="pickup_location" class="input" id="pickupLocation" value="H20, Kolej Tun Fatimah, UTM Skudai" required>
                <span class="map-icon" onclick="openMap('pickup')">📍</span>
            </div>

            <div class="location-input-wrapper">
                <div class="field-label">Return Location</div>
                <input type="text" name="return_location" class="input" id="returnLocation" value="H20, Kolej Tun Fatimah, UTM Skudai" required>
                <span class="map-icon" onclick="openMap('return')">📍</span>
            </div>
        </div>

        <div>
            <div class="field-label">Remark</div>
            <textarea name="remark" class="input" rows="4" placeholder="Any notes"></textarea>
        </div>

        <label style="font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
            <input type="checkbox" name="for_someone_else" value="1">
            <span>I am not a driver for this vehicle. I am making this reservation for someone else.</span>
        </label>
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
        <span id="pricePerHour">MYR {{ number_format($vehicle->pricePerHour, 2) }}</span>
    </div>
    <div class="charge-row">
        <span>Rental Duration</span>
        <span id="durationDisplay">-</span>
    </div>
    <div class="charge-row">
        <span>Total Price (by hour)</span>
        <span id="totalByHour">MYR 0.00</span>
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
        <img src="car-axia.png" width="60" style="object-fit:contain;">
        <div>
            <div class="car-badge">Selected Car</div>
            <b>{{ $vehicle->model }}</b>
        </div>
    </div>
    <div class="grand-total-bottom">
        <div class="grand-label">Grand Total</div>
        <div class="grand-amount" id="grandTotal">MYR</div>

    </div>
    <button type="submit" class="next-btn">Next →</button>
</div>
</form>

<script>
function openMap(type){
    const loc = type==='pickup'?document.getElementById('pickupLocation'):document.getElementById('returnLocation');
    const addr = loc.value;
    if(addr){window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(addr)}`,'_blank');}
    else{alert('Please enter a location first');}
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pickupDateInput = document.querySelector('input[name="pickup_date"]');
    const pickupTimeInput = document.querySelector('input[name="pickup_time"]');
    const returnDateInput = document.querySelector('input[name="return_date"]');
    const returnTimeInput = document.querySelector('input[name="return_time"]');
    const durationInput = document.querySelector('.duration-box input');

    const durationDisplayEl = document.getElementById('durationDisplay');
    const totalByHourEl = document.getElementById('totalByHour');
    const grandTotalEl = document.getElementById('grandTotal');

    const pricePerHour = {{ $vehicle->pricePerHour }};
    const pricePerDay = {{ $vehicle->pricePerDay }};

    function calculateDurationAndPrice() {
        const pickup = new Date(`${pickupDateInput.value}T${pickupTimeInput.value}`);
        const returnDT = new Date(`${returnDateInput.value}T${returnTimeInput.value}`);

        if (!pickupDateInput.value || !pickupTimeInput.value || !returnDateInput.value || !returnTimeInput.value || returnDT <= pickup) {
            durationInput.value = '';
            durationDisplayEl.textContent = '-';
            totalByHourEl.textContent = 'MYR 0.00';
            grandTotalEl.textContent = 'MYR 0.00';
            const bottomBarGrand = document.querySelector('.bottom-bar .grand-amount');
            if (bottomBarGrand) bottomBarGrand.textContent = 'MYR 0.00';
            return;
        }

        const diffMs = returnDT - pickup;
        const diffHours = Math.ceil(diffMs / (1000 * 60 * 60)); // round up to next hour

        const days = Math.floor(diffHours / 24);
        const remainingHours = diffHours % 24;

        // Duration text
        const durationText = days > 0
            ? `${days} day${days > 1 ? 's' : ''} ${remainingHours} hour${remainingHours !== 1 ? 's' : ''}`
            : `${diffHours} hour${diffHours !== 1 ? 's' : ''}`;

        durationInput.value = durationText;
        durationDisplayEl.textContent = durationText;

        // Total price by hour
        const totalByHour = diffHours * pricePerHour;

        // Grand total: full days + remaining hours
        const grandTotal = (days * pricePerDay) + (remainingHours * pricePerHour);

        // Update Summary Card
        totalByHourEl.textContent = `MYR ${totalByHour.toFixed(2)}`;
        grandTotalEl.textContent = `MYR ${grandTotal.toFixed(2)}`;

        // Update Bottom Bar
        const bottomBarGrand = document.querySelector('.bottom-bar .grand-amount');
        if (bottomBarGrand) bottomBarGrand.textContent = `MYR ${grandTotal.toFixed(2)}`;
    }

    // Initial calculation
    calculateDurationAndPrice();

    // Recalculate when any input changes
    [pickupDateInput, pickupTimeInput, returnDateInput, returnTimeInput].forEach(el =>
        el.addEventListener('change', calculateDurationAndPrice)
    );
});
</script>


</body>
</html>

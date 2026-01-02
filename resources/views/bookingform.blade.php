<?php
// Example placeholders (you may replace with $_POST[] or DB values later)

$pickup_date   = $pickup_date   ?? "2025-08-17";
$pickup_time   = $pickup_time   ?? "08:00";

$return_date   = $return_date   ?? "2025-08-18";
$return_time   = $return_time   ?? "08:00";

$destination   = $destination   ?? "";
$pickup_loc    = $pickup_loc    ?? "";
$return_loc    = $return_loc    ?? "";
$remark        = $remark        ?? "";

$duration_text = $duration_text ?? "1 day 0 hour 0 minute";

$rental_price  = $rental_price  ?? 120.00;
$discount      = $discount      ?? 0.00;
$total_amount  = $total_amount  ?? 120.00;
$grand_total   = $grand_total   ?? 120.00;

$car_name      = $car_name      ?? "Perodua Axia 2018";
$car_image     = $car_image     ?? "car-axia.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>HASTA – Booking Details</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/header.css') }}" rel="stylesheet">

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #fafafa;
    color: #222;
}

/* ===== HEADER ===== */

.header {
    background: #d94242;
    padding: 15px 50px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    border: 3px solid #fff;
    padding: 6px 14px;
    color: #fff;
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 2px;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
}

.user-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #fff;
    font-weight: 600;
}

.user-icon {
    width: 38px;
    height: 38px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d94242;
}

/* ===== STEP PROGRESS BAR ===== */

.stepper {
    max-width: 1100px;
    margin: 40px auto;
    display: flex;
    justify-content: center;
    gap: 60px;
}

.step {
    padding: 12px 32px;
    border-radius: 40px;
    border: 2px solid #ddd;
    background: #fff;
    color: #777;
    font-weight: 600;
    display: flex;
    gap: 10px;
    align-items: center;
}

.step.active {
    border-color: #d94242;
    color: #d94242;
}

/* ===== PAGE LAYOUT ===== */

.container {
    max-width: 1150px;
    margin: 10px auto 140px;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
}

/* ===== LEFT CARD FORM ===== */

.card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #eee;
}

.section-title {
    font-weight: 700;
    margin-bottom: 12px;
}

.row2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.row3 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 8px;
}

.field-label {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    margin-bottom: 4px;
}

.input {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.duration-box input {
    background: #f7f7f7;
}

/* ===== RIGHT SUMMARY PANEL ===== */

.summary-box {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eee;
    padding: 22px;
}

.summary-title {
    color: #d94242;
    font-weight: 700;
    margin-bottom: 10px;
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 14px;
}

.time-item {
    display: flex;
    gap: 10px;
    align-items: center;
}

.charges {
    border-top: 1px solid #eee;
    margin-top: 15px;
    padding-top: 12px;
}

.charge-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
    font-size: 14px;
}

.grand {
    margin-top: 10px;
    padding-top: 8px;
    border-top: 1px solid #ddd;
    font-weight: 800;
}

/* ===== BOTTOM BAR ===== */

.bottom-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 60px;
}

.selected-car {
    display: flex;
    align-items: center;
    gap: 16px;
}

.car-badge {
    background: #ffe5e5;
    color: #d94242;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 20px;
}

.next-btn {
    background: #d94242;
    color: #fff;
    border: none;
    padding: 12px 26px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
}

.next-btn:hover {
    background: #b83535;
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
                @auth
                     <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
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

<!-- STEP BAR -->
<div class="stepper">
    <div class="step">Vehicle</div>
    <div class="step">Register</div>
    <div class="step active">Booking Details</div>
    <div class="step">Payment</div>
</div>

<!-- MAIN CONTENT -->
<div class="container">

    <!-- LEFT FORM -->
    <div class="card">

        <div class="section-title">Pickup</div>
        <div class="row2">
            <div>
                <div class="field-label">Date</div>
                <input type="date" class="input" value="<?php echo $pickup_date; ?>">
            </div>
            <div>
                <div class="field-label">Time</div>
                <input type="time" class="input" value="<?php echo $pickup_time; ?>">
            </div>
        </div>

        <br>

        <div class="section-title">Return</div>
        <div class="row2">
            <div>
                <div class="field-label">Date</div>
                <input type="date" class="input" value="<?php echo $return_date; ?>">
            </div>
            <div>
                <div class="field-label">Time</div>
                <input type="time" class="input" value="<?php echo $return_time; ?>">
            </div>
        </div>

        <br>

        <div class="row2">
            <div>
                <div class="field-label">Destination</div>
                <input type="text" class="input" value="<?php echo $destination; ?>" placeholder="Full Address">
            </div>

            <div class="duration-box">
                <div class="field-label">Duration</div>
                <input type="text" class="input" value="<?php echo $duration_text; ?>" readonly>
            </div>
        </div>

        <br>

        <div class="row2">
            <div>
                <div class="field-label">Pickup Location</div>
                <input type="text" class="input" value="<?php echo $pickup_loc; ?>" placeholder="Full Address">
            </div>

            <div>
                <div class="field-label">Return Location</div>
                <input type="text" class="input" value="<?php echo $return_loc; ?>" placeholder="Full Address">
            </div>
        </div>

        <br>

        <div>
            <div class="field-label">Remark</div>
            <textarea class="input" rows="4" placeholder="Any notes"><?php echo $remark; ?></textarea>
        </div>

        <br>

        <label style="font-size:13px">
            <input type="checkbox">
            I am not a driver for this vehicle. I am making this reservation for someone else.
        </label>

    </div>

    <!-- RIGHT SUMMARY -->
    <div>

        <div class="summary-box">
            <div class="summary-title">Pickup & Return</div>

            <div class="timeline">

                <div class="time-item">
                    ⏰ <?php echo date("g:i a, d M Y", strtotime("$pickup_date $pickup_time")); ?>
                </div>
                📍 <?php echo $pickup_loc ?: "H20, Kolej Tun Fatimah, UTM Skudai"; ?>

                <div class="time-item">
                    ⏰ <?php echo date("g:i a, d M Y", strtotime("$return_date $return_time")); ?>
                </div>
                📍 <?php echo $return_loc ?: "H20, Kolej Tun Fatimah, UTM Skudai"; ?>

            </div>

            <div class="time-item">
                ⏳ <?php echo $duration_text; ?>
            </div>

            <div class="charges">
                <div class="summary-title">Summary of Charges</div>

                <div class="charge-row">
                    <span>Rental</span>
                    <span><?php echo number_format($rental_price, 2); ?></span>
                </div>

                <div class="charge-row">
                    <span>Discount Amount</span>
                    <span>-<?php echo number_format($discount, 2); ?></span>
                </div>

                <div class="charge-row">
                    <span>Total Amount</span>
                    <span><?php echo number_format($total_amount, 2); ?></span>
                </div>

                <div class="charge-row grand">
                    <span>Grand Total (MYR)</span>
                    <span><?php echo number_format($grand_total, 2); ?></span>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- BOTTOM BAR -->
<div class="bottom-bar">

    <div class="selected-car">
        <img src="<?php echo $car_image; ?>" width="60">
        <div>
            <div class="car-badge">Selected Car</div>
            <b><?php echo $car_name; ?></b>
        </div>
    </div>

    <div>
        <div style="text-align:right;font-weight:700">Grand Total</div>
        <div style="font-size:18px;font-weight:900">MYR <?php echo number_format($grand_total, 2); ?></div>
    </div>

    <button class="next-btn">Next →</button>
</div>

</body>
</html>

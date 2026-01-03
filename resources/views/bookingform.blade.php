<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HASTA – Booking Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            padding-bottom: 120px;
        }

        /* HEADER */
        #header {
            background-color: #d94242;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #logo {
            height: 45px;
        }

        #menu {
            display: flex;
            gap: 10px;
        }

        .head_button {
            background: transparent;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .head_button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        #profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        #profile-container {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        #pfp {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #username {
            color: white;
            font-weight: 600;
        }

        /* PROGRESS STEPS */
        .progress-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 50px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 40px;
            border: 2px solid #ddd;
            border-radius: 50px;
            background-color: white;
            color: #999;
            font-weight: 600;
        }

        .step.filled {
            border-color: #d94242;
            background-color: #d94242;
            color: white;
        }

        .step.active {
            border-color: #d94242;
            color: #d94242;
        }

        .step-icon {
            width: 24px;
            height: 24px;
        }

        .step-connector {
            width: 100px;
            height: 2px;
            background-color: #ddd;
        }

        /* CONTAINER */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        /* CARD */
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            border: 2px solid #eee;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            margin-bottom: 7px;
        }

        .input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }

        .input:focus {
            outline: none;
            border-color: #d94242;
        }

        .duration-box .input {
            background: #f7f7f7;
            font-weight: 600;
            cursor: not-allowed;
        }

        textarea.input {
            resize: vertical;
            min-height: 100px;
        }

        /* SUMMARY */
        .summary-box {
            background: white;
            border-radius: 10px;
            padding: 25px;
            border: 2px solid #eee;
        }

        .summary-title {
            color: #d94242;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .timeline {
            margin-bottom: 20px;
        }

        .time-item {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 8px;
        }

        .charges {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        .charge-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .grand {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            font-weight: 800;
        }

        /* BOTTOM BAR */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 60px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }

        .selected-car {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .car-badge {
            background: #ffe5e5;
            color: #d94242;
            font-size: 11px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .next-btn {
            background: #d94242;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .next-btn:hover {
            background: #c23535;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            #header {
                padding: 15px 20px;
            }
            
            #menu {
                display: none;
            }
        }
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

<!-- MAIN CONTENT -->
<div class="container">

    <!-- LEFT FORM -->
    <div class="card">

        <div class="section-title">Pickup</div>
        <div class="row2">
            <div>
                <div class="field-label">Date</div>
                <input type="date" class="input">
            </div>
            <div>
                <div class="field-label">Time</div>
                <input type="time" class="input">
            </div>
        </div>

        <div class="section-title">Return</div>
        <div class="row2">
            <div>
                <div class="field-label">Date</div>
                <input type="date" class="input">
            </div>
            <div>
                <div class="field-label">Time</div>
                <input type="time" class="input">
            </div>
        </div>

        <div class="row2">
            <div>
                <div class="field-label">Destination</div>
                <input type="text" class="input" placeholder="Full Address">
            </div>

            <div class="duration-box">
                <div class="field-label">Duration</div>
                <input type="text" class="input" readonly>
            </div>
        </div>

        <div class="row2">
            <div>
                <div class="field-label">Pickup Location</div>
                <input type="text" class="input" placeholder="Full Address">
            </div>

            <div>
                <div class="field-label">Return Location</div>
                <input type="text" class="input" placeholder="Full Address">
            </div>
        </div>

        <div>
            <div class="field-label">Remark</div>
            <textarea class="input" rows="4" placeholder="Any notes"></textarea>
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
                    ⏰ 8:00 am, 17 Dec 2025
                </div>
                📍 H20, Kolej Tun Fatimah, UTM Skudai

                <br>

                <div class="time-item">
                    ⏰ 8:00 am, 18 Dec 2025
                </div>
                📍 H20, Kolej Tun Fatimah, UTM Skudai

            </div>

            <div class="time-item">
                ⏳ 1 day 0 hour 0 minute
            </div>

            <div class="charges">
                <div class="summary-title">Summary of Charges</div>

                <div class="charge-row">
                    <span>Rental</span>
                    <span>120.00</span>
                </div>

                <div class="charge-row">
                    <span>Discount Amount</span>
                    <span>-0.00</span>
                </div>

                <div class="charge-row">
                    <span>Total Amount</span>
                    <span>120.00</span>
                </div>

                <div class="charge-row grand">
                    <span>Grand Total (MYR)</span>
                    <span>120.00</span>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- BOTTOM BAR -->
<div class="bottom-bar">

    <div class="selected-car">
        <img src="car-axia.png" width="60">
        <div>
            <div class="car-badge">Selected Car</div>
            <b>Perodua Axia 2018</b>
        </div>
    </div>

    <div>
        <div style="text-align:right;font-weight:700">Grand Total</div>
        <div style="font-size:18px;font-weight:900">MYR 120.00</div>
    </div>

    <button class="next-btn">Next →</button>
</div>

</body>
</html>
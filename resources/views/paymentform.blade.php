<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <title>HASTA - Payment</title>


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">


    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; }
        button { cursor: pointer; }

        /* Header */
        #header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: #d94444; color: white; }
        #header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: #d94444; color: white; }
        #logo { height: 45px; }

        #menu a {
            margin-right: 10px;
            background: transparent;
            border: none;
            color: white;

        #menu a {
            margin-right: 10px;
            background: transparent;
            border: none;
            color: white;
            font-weight: 600;
            text-decoration: none;
        }

        #profile-container { width: 45px; height: 45px; border-radius: 50%; overflow: hidden; background: white; }
        #pfp { width: 100%; height: 100%; object-fit: cover; }
        #username { color: white; margin-left: 10px; text-decoration: none; }

        #profile-container { width: 45px; height: 45px; border-radius: 50%; overflow: hidden; background: white; }
        #pfp { width: 100%; height: 100%; object-fit: cover; }
        #username { color: white; margin-left: 10px; text-decoration: none; }

        /* Progress Steps */
        .progress-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .steps { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 40px; }

        .progress-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .steps { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 40px; }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 25px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .step a { text-decoration: none; color: inherit; }

        .step.filled { background: #d94444; color: white; }
        .step.active { border: 2px solid #d94444; color: #d94444; background: white; }
        .step-connector { width: 80px; height: 2px; background: #ddd; }
        }

        .step a { text-decoration: none; color: inherit; }

        .step.filled { background: #d94444; color: white; }
        .step.active { border: 2px solid #d94444; color: #d94444; background: white; }
        .step-connector { width: 80px; height: 2px; background: #ddd; }

        /* Layout */
        .container { max-width: 1200px; margin: 0 auto 60px; padding: 0 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .section { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { font-size: 24px; margin-bottom: 25px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto 60px; padding: 0 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .section { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { font-size: 24px; margin-bottom: 25px; color: #333; }

        /* Car Info */
        .car-info { display: flex; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 25px; }
        .car-image { width: 150px; border-radius: 8px; }
        .car-model { font-weight: bold; color: #d94444; }
        .car-type { font-size: 14px; color: #999; margin-bottom: 10px; }
        .car-features { display: flex; gap: 15px; font-size: 12px; color: #666; }

        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .info-label { color: #666; font-weight: 500; }
        .info-value { font-weight: 600; color: #333; }
        .discount-row { color: #28a745; }
        .total-row { display: flex; justify-content: space-between; padding: 15px 0; font-weight: bold; font-size: 16px; margin-top: 10px; }
        .car-info { display: flex; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 25px; }
        .car-image { width: 150px; border-radius: 8px; }
        .car-model { font-weight: bold; color: #d94444; }
        .car-type { font-size: 14px; color: #999; margin-bottom: 10px; }
        .car-features { display: flex; gap: 15px; font-size: 12px; color: #666; }

        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .info-label { color: #666; font-weight: 500; }
        .info-value { font-weight: 600; color: #333; }
        .discount-row { color: #28a745; }
        .total-row { display: flex; justify-content: space-between; padding: 15px 0; font-weight: bold; font-size: 16px; margin-top: 10px; }

        /* Form */
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }

        .radio-group { display: flex; gap: 20px; margin-bottom: 20px; }
        .radio-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; }

        * Voucher Tabs */
        .voucher-tabs { display: flex; gap: 10px; margin-bottom: 15px; }
        .tab-btn { 
            flex: 1; 
            padding: 10px; 
            border: 1px solid #ddd; 
            background: white; 
            border-radius: 5px;
            transition: all 0.3s;
            font-size: 14px;
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }

        .radio-group { display: flex; gap: 20px; margin-bottom: 20px; }
        .radio-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; }

        * Voucher Tabs */
        .voucher-tabs { display: flex; gap: 10px; margin-bottom: 15px; }
        .tab-btn { 
            flex: 1; 
            padding: 10px; 
            border: 1px solid #ddd; 
            background: white; 
            border-radius: 5px;
            transition: all 0.3s;
            font-size: 14px;
        }
        .tab-btn.active { background: #d94444; color: white; border-color: #d94444; }
        .tab-btn:hover { background: #f5f5f5; }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        .voucher-item {
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            margin-bottom: 10px;
        .tab-btn.active { background: #d94444; color: white; border-color: #d94444; }
        .tab-btn:hover { background: #f5f5f5; }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        .voucher-item {
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
            transition: all 0.3s;
        }
        .voucher-item:hover { border-color: #d94444; background: #fff5f5; }
        .voucher-item.selected { border-color: #d94444; background: #ffe6e6; }
        .voucher-item:hover { border-color: #d94444; background: #fff5f5; }
        .voucher-item.selected { border-color: #d94444; background: #ffe6e6; }

        /* QR */
        .qr-section { text-align: center; margin: 25px 0; }
        .qr-code { width: 200px; height: 200px; border: 3px solid #d94444; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 15px auto; background: white; }
        .qr-code img { max-width: 100%; max-height: 100%; }
        .company-name { font-weight: bold; margin-top: 10px; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .qr-section { text-align: center; margin: 25px 0; }
        .qr-code { width: 200px; height: 200px; border: 3px solid #d94444; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 15px auto; background: white; }
        .qr-code img { max-width: 100%; max-height: 100%; }
        .company-name { font-weight: bold; margin-top: 10px; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Upload */
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: #fafafa;
            transition: all 0.3s ease;
            min-height: 180px; /* ← ADD THIS */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .upload-area:hover { border-color: #d94444; background: #fff; }
        .browse-btn { background: #999; color: white; padding: 10px 30px; border: none; border-radius: 5px; }

        /* Terms */
        .terms { text-align: center; margin: 30px 0; display: flex; justify-content: center; align-items: center; }
        .terms a { color: #d94444; }

        /* Submit */
        .submit-btn { display: block; margin: 0 auto; background: #d94444; color: white; padding: 15px 60px; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; }
        .submit-btn:hover { background: #c23939; }

        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 30px; border-radius: 10px; max-width: 500px; }
        .modal-content h3 { margin-bottom: 15px; color: #333; }
        .modal-btns { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
        .modal-btn { padding: 12px 30px; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: #d94444; color: white; }
        .btn-primary:hover { background: #c23939; }
        .btn-secondary { background: #ddd; color: #333; }
        .btn-secondary:hover { background: #ccc; }
    </style>
</head>

<body>

<!-- Header -->
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
        </div>

        @guest
            <a id="username" href="{{ route('login') }}">Log in</a>
        @endguest
    </div>
</div>

<!-- Progress Steps -->
    <div class="progress-container">
        <div class="steps">
            <div class="step fill" id="step-vehicle"><span class="step-icon">✓</span><span>Vehicle</span></div>
            <div class="step-connector"></div>
            <div class="step fill" id="step-booking"><span class="step-icon">✓</span><span>Booking Details</span></div>
            <div class="step-connector"></div>
            <div class="step active" id="step-payment"><span class="step-icon">✓</span><span>Payment</span></div>
        </div>
    </div>

<!-- Payment Form -->
<form id="paymentForm" method="POST" action="{{ route('booking.confirm') }}" enctype="multipart/form-data">
@csrf

<!-- Hidden Booking Data -->
<input type="hidden" name="vehicleID" value="{{ $vehicle->vehicleID }}">
<input type="hidden" name="pickup_date" value="{{ $pickupDate }}">
<input type="hidden" name="pickup_time" value="{{ $pickupTime }}">
<input type="hidden" name="return_date" value="{{ $returnDate }}">
<input type="hidden" name="return_time" value="{{ $returnTime }}">
<input type="hidden" name="pickupLocation" value="{{ $pickupLocation }}">
<input type="hidden" name="returnLocation" value="{{ $returnLocation }}">
<input type="hidden" name="destination" value="{{ $destination ?? '' }}">
<input type="hidden" name="remark" value="{{ $remark ?? '' }}">
<input type="hidden" name="for_someone_else" value="{{ $forSomeoneElse ?? 0 }}">
<input type="hidden" name="matricNumber" value="{{ $matricNumber ?? '' }}">
<input type="hidden" name="licenseNumber" value="{{ $licenseNumber ?? '' }}">
<input type="hidden" name="college" value="{{ $college ?? '' }}">
<input type="hidden" name="faculty" value="{{ $faculty ?? '' }}">
<input type="hidden" name="depoBalance" value="{{ $depoBalance ?? 0 }}">
<input type="hidden" name="promo_id" value="{{ $promoDetails?->promoID ?? '' }}">
<input type="hidden" name="voucher_id" id="selected_voucher_id" value="">
<input type="hidden" name="deposit_amount" id="depositInput" value="{{ $deposit }}">
<input type="hidden" name="final_total" id="finalTotalInput" value="{{ $finalTotal }}">

<div class="container">

    <!-- Order Summary -->
    <div class="section">
        <h2>Order Summary</h2>

        <div class="car-info">
            @if($vehicle->image)
                <img src="{{ asset('storage/' . $vehicle->image) }}" class="car-image">
            @else
                <div class="car-image" style="background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#999;">
                    No Image
                </div>
            @endif

            <div>
                <h3>
                    {{ $vehicle->model }} {{ $vehicle->year }}
                    <span class="car-model">RM{{ number_format($vehicle->pricePerDay, 2) }}/day</span>
                </h3>

                <p class="car-type">{{ $vehicle->type ?? 'Vehicle' }}</p>

                <div class="car-features">
                    <span>🚗 {{ $vehicle->transmission ?? 'Auto' }}</span>
                    <span>❄️ AC</span>
                    <span>👤 {{ $vehicle->seats ?? 5 }} seats</span>
                </div>
            </div>
        </div>

        <div class="info-row"><span class="info-label">Pickup :</span><span class="info-value">{{ $pickupLocation }}</span></div>
        <div class="info-row"><span class="info-label">Return :</span><span class="info-value">{{ $returnLocation }}</span></div>
        <div class="info-row"><span class="info-label">Date :</span><span class="info-value">{{ $dateRange }}</span></div>
        <div class="info-row"><span class="info-label">Duration :</span><span class="info-value">{{ $durationText }}</span></div>
        <div class="info-row"><span class="info-label">Rental :</span><span class="info-value">MYR {{ number_format($finalSubtotal, 2) }}</span></div>
        
        @if(($deliveryCharge ?? 0) > 0)
        <div class="info-row">
            <span class="info-label">Delivery Charge :</span>
            <span class="info-value">MYR {{ number_format($deliveryCharge, 2) }}</span>
        </div>
        @endif

        @if(($promotionDiscount ?? 0) > 0)
        <div class="info-row discount-row">
            <span class="info-label">Promotion Discount :</span>
            <span class="info-value">- MYR {{ number_format($promotionDiscount, 2) }}</span>
        </div>
        @endif

        <div class="total-row">
            <span>Deposit Payable :</span>
            <span id="depositAmount">MYR {{ number_format($deposit, 2) }}</span>
        </div>

        <div class="total-row">
            <span>Total Payable :</span>
            <span id="totalAmount">MYR {{ number_format($finalTotal, 2) }}</span>
        </div>

        <div class="total-row" id="remainingRow" style="display: none;">
            <span>Remaining Balance :</span>
            <span id="remainingAmount">MYR 0.00</span>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="section">
        <h2>Payment Details</h2>
        
        <!-- Voucher Section -->
        <div class="form-group">
            <label>Apply Voucher (Optional)</label>
            <div class="voucher-tabs">
                <button type="button" class="tab-btn active" data-tab="eligible">Eligible</button>
                <button type="button" class="tab-btn" data-tab="enter">Enter Code</button>
            </div>

            <div id="tab-eligible" class="tab-content active">
                <div class="voucher-list">
                    @if(isset($eligibleVouchers) && count($eligibleVouchers) > 0)
                        @foreach($eligibleVouchers as $v)
                            <div class="voucher-item" 
                                    data-id="{{ $v->promoID }}" 
                                    data-val="{{ $v->discountValue }}"
                                    onclick="selectVoucher(this)">
                                <strong>{{ $v->title }}</strong><br>
                                <small>Code: {{ $v->code }} (Disc: RM{{ $v->discountValue }})</small>
                            </div>
                        @endforeach
                    @else
                        <p style="color:#999; font-size:13px; padding:10px;">No eligible vouchers found.</p>
                    @endif
                </div>
            </div>

            <div id="tab-enter" class="tab-content">
                <div style="display:flex; gap:10px;">
                    <input type="text" id="voucher_code" placeholder="Enter voucher code">
                    <button type="button" id="apply_voucher" class="browse-btn" style="padding:10px 20px;">Apply</button>
                </div>
                <div id="voucher_message" style="margin-top:5px; font-size:13px;"></div>
            </div>
        </div>
        
        <div class="info-row discount-row" id="voucher_discount_row" style="display:none;">
            <span class="info-label">Voucher Discount :</span>
            <span class="info-value" id="voucher_discount_display">- MYR 0.00</span>
        </div>

        <div class="form-group">
            <label>Bank Name</label>
            <select name="bank_name" required>
                <option value="">Select Bank</option>
                <option value="Maybank">Maybank</option>
                <option value="CIMB Bank">CIMB Bank</option>
                <option value="Public Bank">Public Bank</option>
                <option value="RHB Bank">RHB Bank</option>
                <option value="Hong Leong Bank">Hong Leong Bank</option>
                <option value="Affin Bank">Affin Bank</option>
                <option value="Alliance Bank">Alliance Bank</option>
                <option value="AmBank">AmBank</option>
                <option value="BSN">BSN</option>
                <option value="Bank Islam">Bank Islam</option>
                <option value="Bank Muamalat">Bank Muamalat</option>
                <option value="Bank Rakyat">Bank Rakyat</option>
                <option value="Touch 'n Go eWallet">Touch 'n Go eWallet</option>
            </select>
        </div>

        <div class="form-group">
            <label>Bank Owner Name</label>
            <input type="text" name="bank_owner_name" placeholder="Enter account holder name" required>
        </div>

        <div class="form-group">
            <label>Pay Amount</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="payAmount" value="full" checked> Full
                </label>
                <label>
                    <input type="radio" name="payAmount" value="deposit"> Deposit
                </label>
            </div>
        </div>

        <div class="qr-section">
            <label>Scan QR here to make the payment</label>
            <div class="qr-code">
                @if(file_exists(public_path('storage/qrImage.jpeg')))
                    <img src="{{ asset('storage/qrImage.jpeg') }}" alt="HASTA Payment QR Code">
                @else
                    <div style="color:#999;">QR Code Not Available</div>
                @endif
            </div>
            <p class="company-name">HASTA TRAVEL SDN BHD</p>
        </div>


        <div class="form-group">
            <label>Upload Receipt</label>
            <p style="color: #999; font-size: 13px; margin-bottom: 10px;">Kindly upload a screenshot of receipt payment</p>
            <div class="upload-area" id="uploadContainer">
                <div class="upload-icon">📄</div>
                <p class="upload-text" id="uploadText">Drag files here or click "Browse" to upload</p>
                <button type="button" class="browse-btn" id="browseBtn">Browse</button>
                <input type="file" id="fileInput" name="payment_receipt" accept="image/*" style="display: none;" required>
                <!-- Preview container -->
                <div id="imagePreview" style="display: none; margin-top: 15px;">
                    <img id="previewImage" src="" alt="Receipt Preview" style="max-width: 100%; max-height: 200px; border: 1px solid #ddd; border-radius: 5px;">
                    <button type="button" id="removeImage" style="margin-top: 10px; background: #d94444; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Remove</button>
                </div>
                <!-- Error message container -->
                <div id="fileError" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
            </div>
        </div>

        <div class="terms">
            <label>
                <input type="checkbox" required>
                I agree to the <a href="#" onclick="showTermsModal(); return false;">Terms & Conditions</a>
            </label>
        </div>

        <button type="submit" class="submit-btn">Confirm Payment</button>
    </div>
        <button type="submit" class="submit-btn">Confirm Payment</button>
    </div>

    </div>
    </form>

<!-- Terms Modal -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <h3>Terms and Conditions</h3>
        <p style="text-align:left;margin:20px 0;line-height:1.5;">
            By proceeding with this booking, you agree to the following terms:<br>
            - Vehicle must be returned in the same condition as received.<br>
            - Full liability applies for damages or late return.<br>
            - HASTA Travel reserves the right to cancel bookings for suspicious activity.
        </p>
        <button class="modal-btn btn-primary" onclick="document.getElementById('termsModal').style.display='none'">Close</button>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <h3>Booking Submitted!</h3>
        <p style="margin:20px 0;">
            Your booking form has been submitted.<br>
            We have notified our staff and will confirm your booking shortly.<br>
            Kindly trace your booking below.
        </p>
        <div class="modal-btns">
            <button class="modal-btn btn-primary" onclick="window.location='{{ route('booking.history') }}'">View My Bookings</button>
            <button class="modal-btn btn-secondary" onclick="window.location='{{ route('booking.history') }}'">Close</button>
        </div>
    </div>
</div>

<script>
    // Initial values
    const baseTotal = {{ $finalTotal ?? 0 }};
    const baseDeposit = {{ $deposit ?? 0 }};
    let currentVoucherValue = 0;
    let currentTotal = baseTotal;

    // Toggle voucher tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    // Apply voucher
    function applyVoucher(id, amount) {
        document.getElementById('selected_voucher_id').value = id;
        currentVoucherValue = amount;
        currentTotal = Math.max(0, baseTotal - amount);
        updatePaymentDisplay();
        
        // Show voucher discount
        document.getElementById('voucher_discount_row').style.display = 'flex';
        document.getElementById('voucher_discount_display').textContent = '- MYR ' + amount.toFixed(2);
    }

    function resetVoucher() {
        document.getElementById('selected_voucher_id').value = '';
        currentVoucherValue = 0;
        currentTotal = baseTotal;
        document.getElementById('voucher_discount_row').style.display = 'none';
        updatePaymentDisplay();
    }

    // Voucher selection
    document.querySelectorAll('.voucher-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.voucher-item').forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
            applyVoucher(item.dataset.id, parseFloat(item.dataset.val));
        });
    });

    // Apply voucher code
    document.getElementById('apply_voucher')?.addEventListener('click', function() {
        const code = document.getElementById('voucher_code').value.trim();
        if (!code) {
            document.getElementById('voucher_message').innerHTML = '<span style="color:red;">Please enter a code</span>';
            return;
        }

        fetch("{{ route('validate.voucher') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                document.getElementById('voucher_message').innerHTML = '<span style="color:green;">Valid voucher!</span>';
                applyVoucher(data.voucher_id, parseFloat(data.amount));
            } else {
                document.getElementById('voucher_message').innerHTML = '<span style="color:red;">Invalid voucher</span>';
                resetVoucher();
            }
        })
        .catch(() => {
            document.getElementById('voucher_message').innerHTML = '<span style="color:red;">Error validating voucher</span>';
        });
    });

    // Update payment display based on selection
    function updatePaymentDisplay() {
        const isDeposit = document.querySelector('input[name="payAmount"]:checked').value === 'deposit';
        const depositAmount = currentTotal * 0.5;
        
        if (isDeposit) {
            document.getElementById('payNowAmount').textContent = 'MYR ' + depositAmount.toFixed(2);
            document.getElementById('remainingRow').style.display = 'flex';
            document.getElementById('remainingAmount').textContent = 'MYR ' + depositAmount.toFixed(2);
            document.getElementById('depositInput').value = depositAmount;
        } else {
            document.getElementById('payNowAmount').textContent = 'MYR ' + currentTotal.toFixed(2);
            document.getElementById('remainingRow').style.display = 'none';
            document.getElementById('depositInput').value = currentTotal;
        }
        document.getElementById('finalTotalInput').value = currentTotal;
    }

    // Handle payment type change
    document.querySelectorAll('input[name="payAmount"]').forEach(radio => {
        radio.addEventListener('change', updatePaymentDisplay);
    });

    // Initialize
    updatePaymentDisplay();

    // File upload (keep your existing file upload code - it looks correct now)
    const fileInput = document.getElementById('fileInput');
    const browseBtn = document.getElementById('browseBtn');
    const uploadArea = document.getElementById('uploadArea');
    const uploadText = document.getElementById('uploadText');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const removeImageBtn = document.getElementById('removeImage');
    const fileError = document.getElementById('fileError');

    function setFile(file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        uploadText.textContent = file.name;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
        hideError();
    }

    function validateFile(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            showError('Please select a valid image file.');
            return false;
        }
        if (file.size > 5 * 1024 * 1024) {
            showError('File size must be under 5MB.');
            return false;
        }
        return true;
    }

    if (browseBtn) {
        browseBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                if (validateFile(file)) setFile(file);
            }
        });
    }

    if (uploadArea) {
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
            uploadArea.style.borderColor = '#d94444';
            uploadArea.style.backgroundColor = '#fff';
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '#fafafa';
        });
        uploadArea.addEventListener('drop', (e) => {
            console.log('File dropped!', e.dataTransfer.files);
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '#fafafa';
            if (e.dataTransfer.files.length > 0) {
                const file = e.dataTransfer.files[0];
                if (validateFile(file)) setFile(file);
            }
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.value = '';
            uploadText.textContent = 'Drag files here or click "Browse" to upload';
            imagePreview.style.display = 'none';
            previewImage.src = '';
            hideError();
        });
    }

    function showError(msg) {
        if (fileError) {
            fileError.textContent = msg;
            fileError.style.display = 'block';
        }
    }
    function hideError() {
        if (fileError) {
            fileError.style.display = 'none';
        }
    }

    // Form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!document.querySelector('input[type="checkbox"][required]').checked) {
            alert('Please accept Terms & Conditions');
            return;
        }

        if (!fileInput.files.length) {
            showError('Please upload a payment receipt.');
            return;
        }

        const formData = new FormData(this);
        const submitBtn = this.querySelector('.submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';

        fetch("{{ route('booking.confirm') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('successModal').style.display = 'block';
            } else {
                alert(data.message || 'Submission failed.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirm Payment';
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Confirm Payment';
        });
    });
</script>

</body>
</html>
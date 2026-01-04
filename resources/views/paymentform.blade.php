<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <title>HASTA - Payment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
        button { cursor: pointer; }

        /* Header */
        #header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 20px 40px; 
            background: #d94444; 
            color: white; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        #logo { height: 45px; }
        #profile-container { 
            width: 45px; 
            height: 45px; 
            border-radius: 50%; 
            overflow: hidden; 
            background: white; 
        }
        #pfp { width: 100%; height: 100%; object-fit: cover; }
        #username { 
            color: white; 
            margin-left: 10px; 
            text-decoration: none; 
            font-weight: 600;
        }

        /* Progress Steps */
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

        /* Layout */
        .container { 
            max-width: 1200px; 
            margin: 0 auto 60px; 
            padding: 0 20px;
            display: grid; 
            grid-template-columns: 1fr; 
            gap: 30px;
        }
        @media (min-width: 992px) {
            .container {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
                padding: 0 40px;
            }
        }
        .section { 
            background: white; 
            padding: 30px; 
            border-radius: 16px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .section:hover {
            transform: translateY(-2px);
        }
        h2 { 
            font-size: 24px; 
            margin-bottom: 25px; 
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        h2 i {
            color: #d94444;
        }

        /* Car Info */
        .car-info { 
            display: flex; 
            gap: 20px; 
            margin-bottom: 25px; 
            padding-bottom: 25px; 
            border-bottom: 1px solid #eee; 
            flex-wrap: wrap;
        }
        .car-image { 
            width: 150px; 
            height: 100px;
            border-radius: 12px; 
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .car-model { 
            font-weight: bold; 
            color: #d94444; 
        }
        .car-type { 
            font-size: 14px; 
            color: #666; 
            margin-bottom: 10px; 
        }
        .car-features { 
            display: flex; 
            gap: 15px; 
            font-size: 12px; 
            color: #666; 
            flex-wrap: wrap;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .feature-item i {
            color: #d94444;
            font-size: 14px;
        }

        .info-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px solid #f0f0f0; 
        }
        .info-label { 
            color: #666; 
            font-weight: 500; 
        }
        .info-value { 
            font-weight: 600; 
            color: #333; 
        }
        .discount-row { 
            color: #28a745; 
        }
        .total-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 15px 0; 
            font-weight: bold; 
            font-size: 18px; 
            margin-top: 15px;
            border-top: 2px solid #333;
        }

        /* Form */
        .form-group { 
            margin-bottom: 24px; 
        }
        label { 
            display: block; 
            margin-bottom: 10px; 
            color: #333; 
            font-weight: 600;
            font-size: 15px;
        }
        .form-control {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #d94444;
            box-shadow: 0 0 0 3px rgba(217, 68, 68, 0.1);
        }
        select.form-control {
            appearance: none;
            background: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e") no-repeat right 12px center/15px 12px;
            padding-right: 40px;
        }

        .radio-group { 
            display: flex; 
            gap: 20px; 
            flex-wrap: wrap;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 10px 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .radio-option:hover {
            border-color: #d94444;
        }
        .radio-option input {
            width: auto;
        }
        .radio-option.selected {
            border-color: #d94444;
            background-color: #fff5f5;
        }

        /* QR */
        .qr-section { 
            text-align: center; 
            margin: 25px 0;
            padding: 25px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        .qr-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        .qr-code { 
            width: 180px; 
            height: 180px; 
            border: 3px solid #d94444; 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 15px; 
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .qr-code img { 
            max-width: 100%; 
            max-height: 100%; 
        }
        .company-name { 
            font-weight: bold; 
            margin-top: 10px; 
            color: #d94444;
            font-size: 16px;
        }

        /* Upload */
        .upload-container {
            border: 2px dashed #ddd;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-container:hover, .upload-container.drag-over {
            border-color: #d94444;
            background: #fff;
        }
        .upload-icon {
            font-size: 48px;
            color: #999;
            margin-bottom: 15px;
        }
        .upload-text {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .browse-btn {
            background: #d94444;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .browse-btn:hover {
            background: #c23535;
        }
        .file-input {
            display: none;
        }
        .preview-container {
            margin-top: 20px;
            display: none;
        }
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .remove-btn:hover {
            background: #c82333;
        }
        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 8px;
            display: none;
        }

        /* Terms */
        .terms-container {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        .terms-checkbox {
            margin-top: 4px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .terms-text {
            font-size: 14px;
            line-height: 1.6;
        }
        .terms-link {
            color: #d94444;
            text-decoration: none;
            font-weight: 600;
        }
        .terms-link:hover {
            text-decoration: underline;
        }

        /* Submit */
        .submit-btn { 
            width: 100%;
            background: #d94444; 
            color: white; 
            padding: 16px; 
            border: none; 
            border-radius: 12px; 
            font-size: 18px; 
            font-weight: 700; 
            cursor: pointer;
            transition: background 0.3s ease;
            box-shadow: 0 4px 12px rgba(217, 68, 68, 0.3);
        }
        .submit-btn:hover { 
            background: #c23535; 
        }
        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow: auto;
        }
        .modal-content {
            background: white;
            margin: 80px auto;
            padding: 40px;
            border-radius: 20px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: modalAppear 0.4s ease;
        }
        @keyframes modalAppear {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #d94444;
            margin-bottom: 20px;
        }
        .modal-text {
            margin-bottom: 30px;
            line-height: 1.7;
            color: #333;
            text-align: left;
        }
        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .modal-btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #d94444;
            color: white;
        }
        .btn-primary:hover {
            background: #c23535;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Voucher Tabs */
        .voucher-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 8px 16px;
            background: #f0f0f0;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .tab-btn:hover {
            background: #e0e0e0;
        }
        .tab-btn.active {
            background: #d94444;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .voucher-list {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .voucher-item {
            padding: 12px;
            background: #f9f9f9;
            margin-bottom: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .voucher-item:hover {
            background: #e9e9e9;
        }
        .voucher-item.selected {
            background: #d94444;
            color: white;
        }
        .apply-voucher-section {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .apply-voucher-section input {
            flex: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            .steps {
                gap: 5px;
            }
            .step {
                padding: 10px 15px;
                min-width: 120px;
                font-size: 12px;
            }
            .car-info {
                flex-direction: column;
                text-align: center;
            }
            .car-image {
                width: 120px;
                height: 80px;
                margin: 0 auto 15px;
            }
            .radio-group {
                gap: 10px;
            }
            .radio-option {
                padding: 8px 15px;
                font-size: 14px;
            }
            .submit-btn {
                padding: 14px;
                font-size: 16px;
            }
        }
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
        <div class="step filled">
            <i class="fas fa-car"></i>
            <span>Vehicle</span>
        </div>
        <div class="step-connector"></div>
        <div class="step filled">
            <i class="fas fa-calendar-check"></i>
            <span>Booking Details</span>
        </div>
        <div class="step-connector"></div>
        <div class="step active">
            <i class="fas fa-credit-card"></i>
            <span>Payment</span>
        </div>
    </div>
</div>

<!-- Payment Form -->
<form id="paymentForm" method="POST" enctype="multipart/form-data">
    @csrf
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

    <div class="container">
        <!-- Order Summary -->
        <div class="section">
            <h2><i class="fas fa-receipt"></i> Order Summary</h2>

            <div class="car-info">
                @if($vehicle->image)
                    <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->model }}" class="car-image">
                @else
                    <div class="car-image" style="background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#999;">
                        <i class="fas fa-car" style="font-size:40px;"></i>
                    </div>
                @endif

                <div>
                    <h3>
                        {{ $vehicle->model }} {{ $vehicle->year }}
                        <span class="car-model">RM{{ number_format($vehicle->pricePerDay, 2) }}/day</span>
                    </h3>

                    <p class="car-type">{{ $vehicle->type ?? 'Vehicle' }}</p>

                    <div class="car-features">
                        <span class="feature-item"><i class="fas fa-user-friends"></i> {{ $vehicle->seats ?? 5 }} seats</span>
                        <span class="feature-item"><i class="fas fa-wind"></i> {{ $vehicle->ac ? 'AC' : 'No AC' }}</span>
                        <span class="feature-item"><i class="fas fa-cog"></i> {{ $vehicle->transmission ?? 'Auto' }}</span>
                        <span class="feature-item"><i class="fas fa-gas-pump"></i> {{ $vehicle->fuelType ?? 'Petrol' }}</span>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <span class="info-label">Pickup Location</span>
                <span class="info-value">{{ $pickupLocation }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Return Location</span>
                <span class="info-value">{{ $returnLocation }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rental Period</span>
                <span class="info-value">{{ $dateRange }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Duration</span>
                <span class="info-value">{{ $durationText }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rental Price</span>
                <span class="info-value">MYR {{ number_format($finalSubtotal, 2) }}</span>
            </div>
            <div class="info-row discount-row">
                <span class="info-label">Promotion Discount</span>
                <span class="info-value">- MYR {{ number_format($promotionDiscount, 2) }}</span>
            </div>

            @if($promotionDiscount > 0)
            <div class="info-row discount-row">
                <span class="info-label">Promotion Discount</span>
                <span class="info-value">- MYR {{ number_format($promotionDiscount, 2) }}</span>
            </div>
            @endif

            <div class="info-row" id="voucher_discount_row" style="display:none;">
                <span class="info-label">Voucher Discount</span>
                <span class="info-value" id="voucher_discount_display">- MYR 0.00</span>
            </div>

            <div class="info-row">
                <span class="info-label">Deposit Payable</span>
                <span class="info-value">MYR {{ number_format($deposit, 2) }}</span>
            </div>
            <div class="total-row">
                <span class="info-label">Total Payable</span>
                <span class="info-value">MYR {{ number_format($finalTotal, 2) }}</span>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="section">
            <h2><i class="fas fa-credit-card"></i> Payment Details</h2>
            
            <!-- Voucher Section -->
            <div class="form-group">
                <label>Apply Voucher (Optional)</label>
                <div class="voucher-tabs">
                    <button type="button" class="tab-btn active" data-tab="eligible">Eligible Vouchers</button>
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
                            <p style="color:#999; font-size:13px; padding:10px; text-align:center;">No eligible vouchers found.</p>
                        @endif
                    </div>
                </div>

                <div id="tab-enter" class="tab-content">
                    <div class="apply-voucher-section">
                        <input type="text" id="voucher_code" class="form-control" placeholder="Enter voucher code">
                        <button type="button" id="apply_voucher" class="browse-btn">Apply</button>
                    </div>
                    <div id="voucher_message" style="margin-top:10px; font-size:13px;"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Bank Name</label>
                <select name="bank_name" class="form-control" required>
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
                <label>Account Holder Name</label>
                <input type="text" name="bank_owner_name" class="form-control" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label>Payment Type</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="payAmount" value="full" checked>
                        Full Payment
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="payAmount" value="deposit">
                        Deposit Only (50%)
                    </label>
                </div>
            </div>

            <div class="qr-section">
                <div class="qr-title">Scan QR Code to Pay</div>
                <div class="qr-code">
                    @if(file_exists(public_path('storage/qrImage.jpeg')))
                        <img src="{{ asset('storage/qrImage.jpeg') }}" alt="HASTA Payment QR Code">
                    @else
                        <div style="color:#999; font-size:14px;">QR Code Not Available</div>
                    @endif
                </div>
                <div class="company-name">HASTA TRAVEL SDN BHD</div>
            </div>

            <div class="form-group">
                <label>Upload Payment Receipt</label>
                <div class="upload-container" id="uploadContainer">
                    <div class="upload-icon">
                        <i class="fas fa-file-image"></i>
                    </div>
                    <div class="upload-text">Drag & drop your payment receipt here</div>
                    <button type="button" class="browse-btn" id="browseBtn">Browse Files</button>
                    <input type="file" id="fileInput" name="payment_receipt" class="file-input" accept="image/*" required>
                    <div class="error-message" id="fileError"></div>
                </div>
                
                <div class="preview-container" id="previewContainer">
                    <img id="previewImage" class="preview-image" src="" alt="Receipt Preview">
                    <button type="button" class="remove-btn" id="removeBtn">Remove Receipt</button>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="terms-container">
                <input type="checkbox" id="termsCheckbox" class="terms-checkbox" required>
                <div class="terms-text">
                    I have read and accepted the 
                    <a href="#" class="terms-link" id="termsLink">Terms and Conditions</a>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Confirm Payment
            </button>
        </div>
    </div>
</form>

</body>
</html>
<!-- Terms Modal -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Terms and Conditions</h3>
        <p class="modal-text">
            By proceeding with this booking, you agree to the following terms:<br><br>
            <strong>1. Payment Requirements</strong><br>
            • Payments must be made within 10 minutes of booking confirmation.<br>
            • Failure to upload valid proof of payment will result in automatic cancellation.<br><br>
            
            <strong>2. Vehicle Usage</strong><br>
            • Vehicle must be returned in the same condition as received.<br>
            • Full liability applies for damages, excessive cleaning, or late returns.<br>
            • Late returns will incur additional charges of RM50 per hour.<br><br>
            
            <strong>3. Cancellation Policy</strong><br>
            • Full refund for cancellations made 24 hours before pickup<br>
            • 50% refund for cancellations made within 24 hours of pickup<br>
            • No refund for cancellations after pickup time<br><br>
            
            <strong>4. General Terms</strong><br>
            • HASTA Travel reserves the right to cancel bookings for suspicious activity.<br>
            • Driver must have valid license and meet age requirements (21+ years).<br>
            • Maximum 2 drivers allowed per booking.<br>
            • Smoking and pets are strictly prohibited in vehicles.
        </p>
        <div class="modal-actions">
            <button class="modal-btn btn-primary" id="closeTerms">I Agree</button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <div style="color: #28a745; font-size: 60px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="modal-title">Booking Submitted!</h3>
        <p class="modal-text">
            Your booking form has been submitted successfully!<br><br>
            We have notified our staff and will confirm your booking shortly.<br>
            Kindly trace your booking below.
        </p>
        <div class="modal-actions">
            <button class="modal-btn btn-primary" id="viewBookings">View My Bookings</button>
            <button class="modal-btn btn-secondary" id="closeSuccess">Close</button>
        </div>
    </div>
</div>

<script>
// DOM Elements
const fileInput = document.getElementById('fileInput');
const browseBtn = document.getElementById('browseBtn');
const uploadContainer = document.getElementById('uploadContainer');
const previewContainer = document.getElementById('previewContainer');
const previewImage = document.getElementById('previewImage');
const removeBtn = document.getElementById('removeBtn');
const fileError = document.getElementById('fileError');
const termsLink = document.getElementById('termsLink');
const termsModal = document.getElementById('termsModal');
const closeTerms = document.getElementById('closeTerms');
const termsCheckbox = document.getElementById('termsCheckbox');
const submitBtn = document.getElementById('submitBtn');
const successModal = document.getElementById('successModal');
const viewBookings = document.getElementById('viewBookings');
const closeSuccess = document.getElementById('closeSuccess');

// Initialize
let originalTotal = {{ $finalTotal ?? 0 }};
let currentVoucherValue = 0;

// Event Listeners
if (browseBtn) {
    browseBtn.addEventListener('click', () => fileInput.click());
}

if (fileInput) {
    fileInput.addEventListener('change', handleFileSelect);
}

if (uploadContainer) {
    uploadContainer.addEventListener('dragover', handleDragOver);
    uploadContainer.addEventListener('dragleave', handleDragLeave);
    uploadContainer.addEventListener('drop', handleDrop);
}

if (removeBtn) {
    removeBtn.addEventListener('click', removeFile);
}

if (termsLink) {
    termsLink.addEventListener('click', (e) => {
        e.preventDefault();
        termsModal.style.display = 'block';
    });
}

if (closeTerms) {
    closeTerms.addEventListener('click', () => {
        termsModal.style.display = 'none';
        termsCheckbox.checked = true;
    });
}

if (viewBookings) {
    viewBookings.addEventListener('click', () => {
        window.location.href = "{{ route('booking.history') }}";
    });
}

if (closeSuccess) {
    closeSuccess.addEventListener('click', () => {
        successModal.style.display = 'none';
    });
}

// Form Submission
if (document.getElementById('paymentForm')) {
    document.getElementById('paymentForm').addEventListener('submit', handleFormSubmit);
}

// Voucher Handling
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
});

document.querySelectorAll('.voucher-item').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.voucher-item').forEach(i => i.classList.remove('selected'));
        item.classList.add('selected');
        
        const id = item.dataset.id;
        const amount = parseFloat(item.dataset.val);
        applyVoucherMath(id, amount);
    });
});

if (document.getElementById('apply_voucher')) {
    document.getElementById('apply_voucher').addEventListener('click', applyVoucherCode);
}

// Functions
function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) validateAndPreview(file);
}

function handleDragOver(e) {
    e.preventDefault();
    uploadContainer.classList.add('drag-over');
}

function handleDragLeave(e) {
    e.preventDefault();
    uploadContainer.classList.remove('drag-over');
}

function handleDrop(e) {
    e.preventDefault();
    uploadContainer.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        validateAndPreview(file);
    }
}

function removeFile() {
    fileInput.value = '';
    previewContainer.style.display = 'none';
    hideError();
}

function validateAndPreview(file) {
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        showError('Please select a valid image file (JPEG, JPG, PNG, or GIF)');
        fileInput.value = '';
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) {
        showError('File size exceeds 5MB. Please select a smaller file');
        fileInput.value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImage.src = e.target.result;
        previewContainer.style.display = 'block';
        hideError();
    };
    reader.readAsDataURL(file);
}

function applyVoucherCode() {
    const code = document.getElementById('voucher_code')?.value;
    const msgEl = document.getElementById('voucher_message');
    
    if (!code) {
        if (msgEl) msgEl.innerHTML = '<span style="color:red;">Please enter a code</span>';
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
        if (msgEl) {
            if (data.valid) {
                msgEl.innerHTML = '<span style="color:green;">' + data.message + '</span>';
                applyVoucherMath(data.voucher_id, parseFloat(data.amount));
            } else {
                msgEl.innerHTML = '<span style="color:red;">' + (data.message || 'Invalid voucher') + '</span>';
                resetVoucher(); 
            }
        }
    })
    .catch(() => {
        if (msgEl) msgEl.innerHTML = '<span style="color:red;">Error validating voucher</span>';
    });
}

function applyVoucherMath(id, amount) {
    document.getElementById('selected_voucher_id').value = id;
    currentVoucherValue = amount;
    
    const discountRow = document.getElementById('voucher_discount_row');
    const discountDisplay = document.getElementById('voucher_discount_display');
    
    if (discountRow && discountDisplay) {
        discountRow.style.display = 'flex';
        discountDisplay.textContent = '- MYR ' + amount.toFixed(2);
    }
    
    updateGrandTotal();
}

function resetVoucher() {
    document.getElementById('selected_voucher_id').value = '';
    currentVoucherValue = 0;
    const discountRow = document.getElementById('voucher_discount_row');
    if (discountRow) discountRow.style.display = 'none';
    updateGrandTotal();
}

function updateGrandTotal() {
    let newTotal = Math.max(0, originalTotal - currentVoucherValue);
    let deposit = newTotal * 0.3; // 30% deposit
    
    const totalRows = document.querySelectorAll('.info-row .info-value, .total-row .info-value');
    
    if (totalRows.length >= 2) {
        // Find deposit row and total row
        const depositRow = Array.from(totalRows).find(el => 
            el.parentElement.querySelector('.info-label')?.textContent.includes('Deposit')
        );
        const totalRow = document.querySelector('.total-row .info-value');
        
        if (depositRow) depositRow.textContent = 'MYR ' + deposit.toFixed(2);
        if (totalRow) totalRow.textContent = 'MYR ' + newTotal.toFixed(2);
    }
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    if (!termsCheckbox.checked) {
        alert('Please accept the Terms and Conditions');
        return;
    }
    
    if (!fileInput.files.length) {
        showError('Please upload your payment receipt');
        return;
    }
    
    // Show loading state
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    }
    
    const formData = new FormData(document.getElementById('paymentForm'));
    
    fetch("{{ route('booking.confirm') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            successModal.style.display = 'block';
        } else {
            alert(data.message || 'Submission failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Confirm Payment';
        }
    });
}

function showError(message) {
    if (fileError) {
        fileError.textContent = message;
        fileError.style.display = 'block';
    }
}

function hideError() {
    if (fileError) {
        fileError.style.display = 'none';
        fileError.textContent = '';
    }
}

// Close modals when clicking outside
window.addEventListener('click', (e) => {
    if (e.target === termsModal) termsModal.style.display = 'none';
    if (e.target === successModal) successModal.style.display = 'none';
});
</script>

</body>
</html>
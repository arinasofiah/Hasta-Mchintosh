<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HASTA - Payment</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #d94444;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        #logo {
            height: 45px;
        }

        #profile-container {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 50%;
            overflow: hidden;
        }

        #pfp {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .progress-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .step.filled {
            background-color: #d94444;
            color: white;
            border: none;
        }

        .step.active {
            border: 2px solid #d94444;
            color: #d94444;
            background: white;
        }

        .step.inactive {
            background: white;
            color: #999;
            border: 2px solid #ddd;
        }

        .step-connector {
            width: 80px;
            height: 2px;
            background-color: #ddd;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto 60px;
            padding: 0 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .section {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            font-size: 24px;
            margin-bottom: 25px;
            color: #333;
        }

        .car-info {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }

        .car-image {
            width: 150px;
            border-radius: 8px;
        }

        .car-details h3 {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .car-model {
            color: #d94444;
            font-weight: bold;
        }

        .car-type {
            color: #999;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .car-features {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #666;
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
            color: #333;
            font-weight: 600;
        }

        .discount-row {
            color: #28a745;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0;
            cursor: pointer;
        }

        .qr-section {
            text-align: center;
            margin: 25px 0;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin: 15px auto;
            padding: 10px;
            border: 3px solid #d94444;
            border-radius: 10px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code img {
            max-width: 100%;
            max-height: 100%;
        }

        .company-name {
            font-weight: bold;
            margin-top: 10px;
        }

        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background-color: #fafafa;
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            border-color: #d94444;
            background-color: #fff;
        }

        .upload-icon {
            font-size: 48px;
            color: #999;
            margin-bottom: 10px;
        }

        .upload-text {
            color: #666;
            margin-bottom: 15px;
        }

        .browse-btn {
            background-color: #999;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .browse-btn:hover {
            background-color: #777;
        }

        /* ✅ FIX: Center Terms Checkbox */
        .terms {
            text-align: center;
            margin: 30px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .terms label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .terms input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }

        .terms a {
            color: #d94444;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        .submit-btn {
            display: block;
            margin: 0 auto;
            background-color: #d94444;
            color: white;
            padding: 15px 60px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #c23939;
        }

        .submit-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Voucher Tabs */
        .voucher-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .tab-btn {
            padding: 8px 16px;
            background: #f0f0f0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .voucher-item {
            padding: 8px;
            background: #f9f9f9;
            margin-bottom: 5px;
            border-radius: 4px;
            cursor: pointer;
        }

        .voucher-item:hover {
            background: #e9e9e9;
        }

        .voucher-item.selected {
            background: #d94444;
            color: white;
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
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            text-align: center;
        }

        .modal-btns {
            margin-top: 20px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary {
            background: #d94444;
            color: white;
        }

        .btn-secondary {
            background: #666;
            color: white;
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
        <div class="step filled"><span>✓</span><span>Vehicle</span></div>
        <div class="step-connector"></div>
        <div class="step filled"><span>✓</span><span>Booking Details</span></div>
        <div class="step-connector"></div>
        <div class="step active"><span>✓</span><span>Payment</span></div>
    </div>
</div>

<!-- Form -->
<form id="paymentForm" method="POST" action="{{ route('booking.confirm') }}" enctype="multipart/form-data">
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
            <h2>Order Summary</h2>
            
            <div class="car-info">
                @if($vehicle->image)
                    <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->model }}" class="car-image">
                @else
                    <div class="car-image" style="background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#999;">No Image</div>
                @endif
                <div class="car-details">
                    <h3>{{ $vehicle->model }} {{ $vehicle->year }}
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

            <div class="info-row">
                <span class="info-label">Pickup :</span>
                <span class="info-value">{{ $pickupLocation }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Return :</span>
                <span class="info-value">{{ $returnLocation }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date :</span>
                <span class="info-value">{{ $dateRange }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Duration :</span>
                <span class="info-value">{{ $durationText }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rental :</span>
                <span class="info-value">MYR {{ number_format($finalSubtotal, 2) }}</span>
            </div>

            @if($promotionDiscount > 0)
            <div class="info-row discount-row">
                <span class="info-label">Promotion Discount :</span>
                <span class="info-value">- MYR {{ number_format($promotionDiscount, 2) }}</span>
            </div>
            @endif

            <div class="total-row" style="border-top: 2px solid #eee; padding-top: 15px;">
                <span>Deposit Payable :</span>
                <span>MYR {{ number_format($deposit, 2) }}</span>
            </div>
            <div class="total-row" style="border-top: 2px solid #333;">
                <span>Total Payable :</span>
                <span>MYR {{ number_format($finalTotal, 2) }}</span>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="section">
            <h2>Payment Details</h2>
            
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
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">📄</div>
                    <p class="upload-text" id="uploadText">Drag files here or click "Browse" to upload</p>
                    <button type="button" class="browse-btn" id="browseBtn">Browse</button>
                    <input type="file" id="fileInput" name="payment_receipt" accept="image/*" style="display: none;" required>
                    <div id="imagePreview" style="display: none; margin-top: 15px;">
                        <img id="previewImage" src="" alt="Receipt Preview" style="max-width: 100%; max-height: 200px; border: 1px solid #ddd; border-radius: 5px;">
                        <button type="button" id="removeImage" style="margin-top: 10px; background: #d94444; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Remove</button>
                    </div>
                    <div id="fileError" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ FIX: Centered Terms Checkbox -->
    <div class="terms">
        <label>
            <input type="checkbox" id="termsCheckbox" required>
            <span>I have read and accepted the <a href="#" onclick="showTermsModal(); return false;">Terms and Conditions</a></span>
        </label>
    </div>

    <button type="submit" class="submit-btn" id="submitBtn">Submit</button>
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
            <button class="modal-btn btn-secondary" onclick="window.location='{{ route('welcome') }}'">Close</button>
        </div>
    </div>
</div>

<script>
// ✅ FIX: File Upload - Simplified and Working
const fileInput = document.getElementById('fileInput');
const browseBtn = document.getElementById('browseBtn');
const uploadText = document.getElementById('uploadText');
const imagePreview = document.getElementById('imagePreview');
const previewImage = document.getElementById('previewImage');
const removeImageBtn = document.getElementById('removeImage');
const fileError = document.getElementById('fileError');
const uploadArea = document.getElementById('uploadArea');

// Browse button click handler
browseBtn.onclick = function(e) {
    e.preventDefault();
    fileInput.click();
};

// File selection handler
fileInput.onchange = function(e) {
    handleFileSelect(e.target.files[0]);
};

// Drag and drop handlers
uploadArea.ondragover = function(e) {
    e.preventDefault();
    uploadArea.style.borderColor = '#d94444';
    uploadArea.style.backgroundColor = '#fff';
};

uploadArea.ondragleave = function(e) {
    uploadArea.style.borderColor = '#ddd';
    uploadArea.style.backgroundColor = '#fafafa';
};

uploadArea.ondrop = function(e) {
    e.preventDefault();
    uploadArea.style.borderColor = '#ddd';
    uploadArea.style.backgroundColor = '#fafafa';
    if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        handleFileSelect(e.dataTransfer.files[0]);
    }
};

// Remove image handler
removeImageBtn.onclick = function(e) {
    e.preventDefault();
    fileInput.value = '';
    uploadText.textContent = 'Drag files here or click "Browse" to upload';
    imagePreview.style.display = 'none';
    previewImage.src = '';
    hideError();
};

// Handle file selection
function handleFileSelect(file) {
    if (!file) return;
    
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        showError('Please select a valid image file (JPEG, JPG, PNG, or GIF).');
        fileInput.value = '';
        return;
    }
    
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        showError('File size exceeds 5MB. Please select a smaller file.');
        fileInput.value = '';
        return;
    }
    
    uploadText.textContent = file.name;
    showImagePreview(file);
    hideError();
}

function showImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
        imagePreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

function showError(message) {
    fileError.textContent = message;
    fileError.style.display = 'block';
}

function hideError() {
    fileError.style.display = 'none';
    fileError.textContent = '';
}

function showTermsModal() {
    document.getElementById('termsModal').style.display = 'block';
}

// Form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!document.getElementById('termsCheckbox').checked) {
        alert('Please accept Terms and Conditions');
        return;
    }

    if (!fileInput.files.length) {
        showError('Please upload a payment receipt.');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';

    const formData = new FormData(this);

    // ✅ Debug: Log what we're sending
    console.log('Submitting form data:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    fetch("{{ route('booking.confirm') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        console.log('Response status:', response.status); // ✅ Debug
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data); // ✅ Debug
        
        if (data.success) {
            document.getElementById('successModal').style.display = 'block';
        } else {
            alert(data.message || 'Submission failed. Please try again.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error); // ✅ Debug
        alert('An error occurred. Please try again.');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit';
    });
});
</script>

</body>
</html>
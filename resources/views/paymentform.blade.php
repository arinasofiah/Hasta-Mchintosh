<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Payment</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
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

        .logo {
            font-size: 32px;
            font-weight: bold;
            border: 3px solid white;
            padding: 5px 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-icon {
            width: 35px;
            height: 35px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            gap: 20px;
        }

        .step {
            background-color: #d94444;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .step.inactive {
            background-color: white;
            color: #666;
            border: 2px solid #ddd;
        }

        .step-connector {
            width: 50px;
            height: 2px;
            background-color: #ddd;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px 40px;
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
        }

        .qr-section {
            text-align: center;
            margin: 25px 0;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            background-color: #fff;
            border: 3px solid #d94444;
            border-radius: 10px;
            margin: 15px auto;
            padding: 10px;
        }

        .qr-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #ff69b4 25%, transparent 25%, transparent 75%, #ff69b4 75%, #ff69b4),
                        linear-gradient(45deg, #ff69b4 25%, transparent 25%, transparent 75%, #ff69b4 75%, #ff69b4);
            background-size: 10px 10px;
            background-position: 0 0, 5px 5px;
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
            cursor: pointer;
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
        }

        .terms {
            text-align: center;
            margin: 30px 0;
        }

        .terms input {
            width: auto;
            margin-right: 8px;
        }

        .terms a {
            color: #d94444;
            text-decoration: none;
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

        .checkmark {
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">HASTA</div>
        <div class="user-profile">
            <div class="user-icon">👤</div>
            <span>AMINAH TALIB</span>
        </div>
    </div>

    <div class="progress-bar">
        <div class="step">
            <span class="checkmark">✓</span>
            <span>Vehicle</span>
        </div>
        <div class="step-connector"></div>
        <div class="step">
            <span class="checkmark">✓</span>
            <span>Register</span>
        </div>
        <div class="step-connector"></div>
        <div class="step">
            <span class="checkmark">✓</span>
            <span>Booking Details</span>
        </div>
        <div class="step-connector"></div>
        <div class="step inactive">
            <span class="checkmark">✓</span>
            <span>Payment</span>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <h2>Order Summary</h2>
            
            <div class="car-info">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 120'%3E%3Crect fill='%23d94444' width='200' height='120' rx='10'/%3E%3Cpath fill='%23fff' d='M40 50 L80 40 L120 40 L140 50 L140 80 L40 80 Z'/%3E%3Ccircle cx='60' cy='85' r='12' fill='%23333'/%3E%3Ccircle cx='120' cy='85' r='12' fill='%23333'/%3E%3Cpath fill='%23b8d4ff' d='M50 45 L75 40 L85 50 L65 50 Z M90 40 L115 40 L125 50 L95 50 Z'/%3E%3C/svg%3E" alt="Perodua Axia" class="car-image">
                <div class="car-details">
                    <h3>Perodua Axia 2018 <span class="car-model">RM120</span></h3>
                    <p class="car-type">Hatchback</p>
                    <div class="car-features">
                        <span>🚗 Auto</span>
                        <span>❄️ AC</span>
                        <span>👤 5 seats</span>
                        <span>🎒 2 bags</span>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <span class="info-label">Pickup :</span>
                <span class="info-value">H20, KTF</span>
            </div>
            <div class="info-row">
                <span class="info-label">Return :</span>
                <span class="info-value">H20, KTF</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date :</span>
                <span class="info-value">17-18 Dec 2025</span>
            </div>
            <div class="info-row">
                <span class="info-label">Duration :</span>
                <span class="info-value">1 day 0 hour 0 minute</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rental :</span>
                <span class="info-value">MYR 120.00</span>
            </div>

            <div class="total-row" style="border-top: 2px solid #eee; padding-top: 15px;">
                <span>Deposit Payable :</span>
                <span>MYR 60.00</span>
            </div>
            <div class="total-row" style="border-top: 2px solid #333;">
                <span>Total Payable :</span>
                <span>MYR 120.00</span>
            </div>
        </div>

        <div class="section">
            <h2>Payment Details</h2>
            
            <div class="form-group">
                <label>Bank Name</label>
                <select>
                    <option value="">Select Bank</option>
                    <option value="maybank">Maybank</option>
                    <option value="cimb">CIMB Bank</option>
                    <option value="public">Public Bank</option>
                    <option value="rhb">RHB Bank</option>
                    <option value="hong-leong">Hong Leong Bank</option>
                </select>
            </div>

            <div class="form-group">
                <label>Bank Owner Name</label>
                <input type="text" placeholder="Enter account holder name">
            </div>

            <div class="form-group">
                <label>Pay Amount</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="payAmount" value="full" checked>
                        Full
                    </label>
                    <label>
                        <input type="radio" name="payAmount" value="deposit">
                        Deposit
                    </label>
                </div>
            </div>

            <div class="qr-section">
                <label>Scan QR here to make the payment</label>
                <div class="qr-code">
                    <div class="qr-placeholder"></div>
                </div>
                <p class="company-name">HASTA TRAVEL SDN BHD</p>
            </div>

            <div class="form-group">
                <label>Upload Receipt</label>
                <p style="color: #999; font-size: 13px; margin-bottom: 10px;">Kindly upload a screenshot of receipt payment</p>
                <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-icon">📄</div>
                    <p class="upload-text">Drag files to upload</p>
                    <button class="browse-btn">Browse</button>
                    <input type="file" id="fileInput" style="display: none;" accept="image/*">
                </div>
            </div>
        </div>
    </div>

    <div class="terms">
        <label>
            <input type="checkbox" id="termsCheckbox">
            I have read and accepted the <a href="#">Terms and Conditions</a>
        </label>
    </div>

    <button class="submit-btn" onclick="handleSubmit()">Submit</button>

    <script>
        const fileInput = document.getElementById('fileInput');
        const uploadArea = document.querySelector('.upload-area');

        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                uploadArea.querySelector('.upload-text').textContent = `Selected: ${fileName}`;
            }
        });

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#d94444';
        });

        uploadArea.addEventListener('dragleave', function(e) {
            uploadArea.style.borderColor = '#ddd';
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                const fileName = e.dataTransfer.files[0].name;
                uploadArea.querySelector('.upload-text').textContent = `Selected: ${fileName}`;
            }
        });

        function handleSubmit() {
            const termsCheckbox = document.getElementById('termsCheckbox');
            const bankName = document.querySelector('select').value;
            const bankOwner = document.querySelector('input[type="text"]').value;
            const fileUploaded = fileInput.files.length > 0;

            if (!termsCheckbox.checked) {
                alert('Please accept the Terms and Conditions');
                return;
            }

            if (!bankName) {
                alert('Please select a bank');
                return;
            }

            if (!bankOwner.trim()) {
                alert('Please enter the bank owner name');
                return;
            }

            if (!fileUploaded) {
                alert('Please upload your payment receipt');
                return;
            }

            alert('Payment submitted successfully!');
        }
    </script>
</body>
</html>
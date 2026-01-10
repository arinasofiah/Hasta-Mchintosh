<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Remaining Balance - Hasta</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; color: #333; }
        
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 12px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #d94444; font-size: 28px; margin-bottom: 10px; }
        .header p { color: #666; font-size: 16px; }
        
        .booking-info { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .info-label { color: #666; font-weight: 500; }
        .info-value { color: #333; font-weight: 600; }
        
        .payment-summary { background: #e8f5e9; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .summary-title { color: #2e7d32; font-size: 18px; font-weight: 600; margin-bottom: 15px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px; }
        .summary-row.total { font-size: 20px; font-weight: bold; border-top: 2px solid #2e7d32; padding-top: 15px; margin-top: 15px; }
        
        .form-group { margin-bottom: 25px; }
        .form-label { display: block; margin-bottom: 8px; color: #333; font-weight: 600; font-size: 15px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; }
        .form-control:focus { outline: none; border-color: #d94444; box-shadow: 0 0 0 3px rgba(217, 68, 68, 0.1); }
        
        .upload-container { border: 2px dashed #ddd; border-radius: 8px; padding: 30px; text-align: center; background: #fafafa; }
        .upload-icon { font-size: 48px; color: #999; margin-bottom: 15px; }
        .upload-text { color: #666; margin-bottom: 15px; }
        .browse-btn { background: #d94444; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .file-input { display: none; }
        .preview-container { margin-top: 20px; display: none; }
        .preview-image { max-width: 100%; max-height: 200px; border-radius: 6px; margin-bottom: 10px; }
        
        .submit-btn { width: 100%; background: #d94444; color: white; padding: 16px; border: none; border-radius: 8px; font-size: 18px; font-weight: 700; cursor: pointer; margin-top: 20px; }
        .submit-btn:hover { background: #c23535; }
        
        .back-link { display: inline-block; margin-top: 20px; color: #d94444; text-decoration: none; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>Pay Remaining Balance</h1>
                <p>Complete your payment for Booking #{{ $booking->booking_code }}</p>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Booking Information -->
            <div class="booking-info">
                <h3 style="margin-bottom: 15px; color: #333;">Booking Details</h3>
                <div class="info-row">
                    <span class="info-label">Vehicle:</span>
                    <span class="info-value">{{ $booking->vehicle->model ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rental Period:</span>
                    <span class="info-value">{{ date('d M Y', strtotime($booking->startDate)) }} - {{ date('d M Y', strtotime($booking->endDate)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Duration:</span>
                    <span class="info-value">{{ $booking->bookingDuration ?? 1 }} day(s)</span>
                </div>
            </div>
            
            <!-- Payment Summary -->
            <div class="payment-summary">
                <h3 class="summary-title">Payment Summary</h3>
                <div class="summary-row">
                    <span>Rental Price:</span>
                    <span>RM {{ number_format($booking->totalPrice, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Fixed Deposit:</span>
                    <span>RM 50.00</span>
                </div>
                <div class="summary-row">
                    <span>Total Cost:</span>
                    <span>RM {{ number_format($booking->totalPrice + 50, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Already Paid:</span>
                    <span>RM {{ number_format($booking->totalPaid, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span>Remaining Balance:</span>
                    <span style="color: #d94444;">RM {{ number_format($remainingBalance, 2) }}</span>
                </div>
            </div>
            
            <!-- Payment Form -->
            <form method="POST" action="{{ route('payment.remaining', $booking->bookingID) }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Bank Name</label>
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
                    <label class="form-label">Account Holder Name</label>
                    <input type="text" name="bank_owner_name" class="form-control" placeholder="Enter full name" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Upload Payment Receipt</label>
                    <div class="upload-container" id="uploadContainer">
                        <div class="upload-icon">
                            <i class="fas fa-file-image"></i>
                        </div>
                        <div class="upload-text">Drag & drop your payment receipt here or click browse</div>
                        <button type="button" class="browse-btn" id="browseBtn">Browse Files</button>
                        <input type="file" id="fileInput" name="payment_receipt" class="file-input" accept="image/*" required>
                    </div>
                    
                    <div class="preview-container" id="previewContainer">
                        <img id="previewImage" class="preview-image" src="" alt="Receipt Preview">
                        <button type="button" class="browse-btn" id="removeBtn" style="background: #dc3545;">Remove</button>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-credit-card"></i> Submit Payment for RM {{ number_format($remainingBalance, 2) }}
                </button>
                
                <a href="{{ route('booking.history') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Booking History
                </a>
            </form>
        </div>
    </div>
    
    <script>
        // File upload handling
        const fileInput = document.getElementById('fileInput');
        const browseBtn = document.getElementById('browseBtn');
        const uploadContainer = document.getElementById('uploadContainer');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const removeBtn = document.getElementById('removeBtn');
        
        browseBtn.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    fileInput.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        
        removeBtn.addEventListener('click', function() {
            fileInput.value = '';
            previewContainer.style.display = 'none';
            previewImage.src = '';
        });
        
        // Drag and drop
        uploadContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadContainer.style.borderColor = '#d94444';
        });
        
        uploadContainer.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadContainer.style.borderColor = '#ddd';
        });
        
        uploadContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadContainer.style.borderColor = '#ddd';
            const file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
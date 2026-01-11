<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents - Hasta Travel & Tour</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background-color: #f8f9fa;
        }

       .content-with-sidebar {
    display: flex;
    min-height: calc(100vh - 180px);
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
    gap: 30px;
}

.sidebar {
    width: 250px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 25px 0;
    flex-shrink: 0;
    height: fit-content;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    margin: 0;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 15px 25px;
    color: #555;
    text-decoration: none;
    transition: all 0.3s;
    border-left: 4px solid transparent;
    font-size: 15px;
}

.sidebar-menu a:hover {
    background-color: #f8f9fa;
    color: #bc3737;
    border-left-color: #bc3737;
}

.sidebar-menu a.active {
    background-color: #f8f9fa;
    color: #bc3737;
    font-weight: 600;
    border-left-color: #bc3737;
}

.sidebar-icon {
    margin-right: 12px;
    font-size: 18px;
    width: 24px;
    text-align: center;
}

        .document-upload-page {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .upload-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .upload-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #eaeaea;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: #fff5f5;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-icon i {
            font-size: 20px;
            color: #e63946;
        }

        .section-title-info {
            flex: 1;
            min-width: 200px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .section-subtitle {
            font-size: 13px;
            color: #666;
        }

        .upload-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .requirements-title {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .requirements-list li {
            font-size: 12px;
            color: #666;
            margin-bottom: 6px;
            padding-left: 15px;
            position: relative;
        }

        .requirements-list li:before {
            content: "•";
            color: #e63946;
            position: absolute;
            left: 0;
        }

        .upload-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
        }

        .upload-btn {
            background: #e63946;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .upload-btn:hover {
            background: #d32f2f;
            transform: translateY(-2px);
        }

        .upload-btn i {
            font-size: 14px;
        }

        .file-info {
            font-size: 13px;
            color: #666;
            flex: 1;
        }

        .preview-container {
            margin-top: 10px;
        }

        .preview-file {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            border: 1px solid #eaeaea;
        }

        .file-icon {
            width: 36px;
            height: 36px;
            background: #e63946;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .file-icon i {
            color: white;
            font-size: 16px;
        }

        .file-details {
            flex: 1;
        }

        .file-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .file-size {
            font-size: 12px;
            color: #666;
        }

        .file-actions {
            display: flex;
            gap: 8px;
        }

        .file-action-btn {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .file-action-btn:hover {
            background: #eaeaea;
            color: #e63946;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: #e63946;
            color: white;
            border-color: #e63946;
        }

        .btn-primary:hover {
            background: #d32f2f;
            border-color: #d32f2f;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-uploaded {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-missing {
            background: #f8d7da;
            color: #721c24;
        }

        .file-input {
            display: none;
        }

        .upload-count {
            font-size: 13px;
            color: #666;
            margin-left: 8px;
        }

        .no-file {
            font-size: 13px;
            color: #999;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .content-with-sidebar {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 15px 0;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .sidebar-menu li {
                flex-shrink: 0;
            }
            
            .sidebar-menu a {
                padding: 10px 15px;
                border-left: none;
                border-bottom: 3px solid transparent;
            }
            
            .sidebar-menu a.active {
                border-left: none;
                border-bottom: 3px solid #e63946;
            }
            
            .document-upload-page {
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .upload-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="has-scrollable-content">
    
    {{-- Header --}}
    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}" alt="Hasta Logo">
        
        <div id="menu">
            <button class="head_button" onclick="window.location.href='{{ route('customer.dashboard') }}'">Home</button>
            <button class="head_button">Vehicles</button>
        </div>
        
        <div id="profile">
            <div id="profile-container">
                <img id="pfp" src="{{ asset('img/racc_icon.png') }}" alt="Profile">
                
                <div id="profile-dropdown">
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
                    <a href="{{ route('customer.documents') }}" class="dropdown-item">Upload Documents</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
            
            @auth
                <span id="username">{{ Auth::user()->name }}</span>
            @endauth
        </div>
    </div>

    {{-- Main Content with Sidebar --}}
    <div class="content-with-sidebar">
        {{-- Sidebar Menu --}}
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('customer.profile') }}">
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookingHistory') }}">
                         My Bookings
                    </a>
                </li>
               
                <li>
                    <a href="{{ route('customer.documents') }}" class="active">
                         Upload Documents
                    </a>
                </li>
               
            </ul>
        </div>

        {{-- Document Upload Page --}}
        <div class="document-upload-page">
            <div class="upload-container">
                <div class="page-header">
                    <h1 class="page-title">Upload Documents</h1>
                    <div class="upload-status">
                        @php
                            $uploadedCount = 0;
                            if ($uploadedFiles) {
                                $uploadedCount = count(array_filter($uploadedFiles, function($file) {
                                    return $file !== null;
                                }));
                            }
                        @endphp
                        @if($uploadedCount > 0)
                            <span class="status-badge status-uploaded">{{ $uploadedCount }}/3 Uploaded</span>
                        @else
                            <span class="status-badge status-missing">0/3 Uploaded</span>
                        @endif
                    </div>
                </div>

                @if(session('success'))
                    <div class="success-message" id="successMessage">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="error-message" id="errorMessage">
                        <i class="fas fa-exclamation-circle"></i> 
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.documents.upload') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- IC/Passport Upload -->
                    <div class="upload-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="section-title-info">
                                <h3 class="section-title">IC / Passport</h3>
                                <p class="section-subtitle">Identification document (JPG, PNG, PDF, max 5MB)</p>
                            </div>
                            @if(isset($uploadedFiles['ic_passport']) && $uploadedFiles['ic_passport'])
                                <span class="status-badge status-uploaded">Uploaded</span>
                            @else
                                <span class="status-badge status-missing">Not Uploaded</span>
                            @endif
                        </div>

                        <div class="upload-requirements">
                            <h4 class="requirements-title">Requirements:</h4>
                            <ul class="requirements-list">
                                <li>Clear photo of your IC or Passport</li>
                                <li>All details must be visible and readable</li>
                            </ul>
                        </div>

                        @if(isset($uploadedFiles['ic_passport']) && $uploadedFiles['ic_passport'])
                            <div class="preview-container" id="icPreview">
                                <div class="preview-file">
                                    <div class="file-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name">{{ basename($uploadedFiles['ic_passport']['path']) }}</div>
                                        <div class="file-size">Uploaded</div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ $uploadedFiles['ic_passport']['url'] }}" target="_blank" class="file-action-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="file-action-btn" onclick="confirmDelete('ic_passport')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="upload-row">
                            <button type="button" class="upload-btn" onclick="document.getElementById('ic_file').click()">
                                <i class="fas fa-plus"></i> Add File
                            </button>
                            <div class="file-info">
                                @if(isset($uploadedFiles['ic_passport']) && $uploadedFiles['ic_passport'])
                                    File uploaded successfully
                                @else
                                    No file uploaded yet
                                @endif
                            </div>
                            <input type="file" name="ic_passport" id="ic_file" class="file-input" accept="image/*,.pdf" onchange="previewFile(this, 'icPreview', 'IC/Passport uploaded')">
                        </div>
                    </div>

                    <!-- Driving License Upload -->
                    <div class="upload-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="section-title-info">
                                <h3 class="section-title">Driving License</h3>
                                <p class="section-subtitle">Valid driving license (JPG, PNG, PDF, max 5MB)</p>
                            </div>
                            @if(isset($uploadedFiles['driving_license']) && $uploadedFiles['driving_license'])
                                <span class="status-badge status-uploaded">Uploaded</span>
                            @else
                                <span class="status-badge status-missing">Not Uploaded</span>
                            @endif
                        </div>

                        <div class="upload-requirements">
                            <h4 class="requirements-title">Requirements:</h4>
                            <ul class="requirements-list">
                                <li>Clear photo of front and back of license</li>
                                <li>License must be valid and not expired</li>
                            </ul>
                        </div>

                        @if(isset($uploadedFiles['driving_license']) && $uploadedFiles['driving_license'])
                            <div class="preview-container" id="licensePreview">
                                <div class="preview-file">
                                    <div class="file-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name">{{ basename($uploadedFiles['driving_license']['path']) }}</div>
                                        <div class="file-size">Uploaded</div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ $uploadedFiles['driving_license']['url'] }}" target="_blank" class="file-action-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="file-action-btn" onclick="confirmDelete('driving_license')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="upload-row">
                            <button type="button" class="upload-btn" onclick="document.getElementById('license_file').click()">
                                <i class="fas fa-plus"></i> Add File
                            </button>
                            <div class="file-info">
                                @if(isset($uploadedFiles['driving_license']) && $uploadedFiles['driving_license'])
                                    File uploaded successfully
                                @else
                                    No file uploaded yet
                                @endif
                            </div>
                            <input type="file" name="driving_license" id="license_file" class="file-input" accept="image/*,.pdf" onchange="previewFile(this, 'licensePreview', 'Driving License uploaded')">
                        </div>
                    </div>

                    <!-- Matric Card Upload -->
                    <div class="upload-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="section-title-info">
                                <h3 class="section-title">Matric Card</h3>
                                <p class="section-subtitle">Student identification (JPG, PNG, PDF, max 5MB)</p>
                            </div>
                            @if(isset($uploadedFiles['matric_card']) && $uploadedFiles['matric_card'])
                                <span class="status-badge status-uploaded">Uploaded</span>
                            @else
                                <span class="status-badge status-missing">Not Uploaded</span>
                            @endif
                        </div>

                        <div class="upload-requirements">
                            <h4 class="requirements-title">Requirements:</h4>
                            <ul class="requirements-list">
                                <li>Clear photo of your matric card or student ID</li>
                                <li>Matric number must be visible</li>
                            </ul>
                        </div>

                        @if(isset($uploadedFiles['matric_card']) && $uploadedFiles['matric_card'])
                            <div class="preview-container" id="matricPreview">
                                <div class="preview-file">
                                    <div class="file-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name">{{ basename($uploadedFiles['matric_card']['path']) }}</div>
                                        <div class="file-size">Uploaded</div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ $uploadedFiles['matric_card']['url'] }}" target="_blank" class="file-action-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="file-action-btn" onclick="confirmDelete('matric_card')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="upload-row">
                            <button type="button" class="upload-btn" onclick="document.getElementById('matric_file').click()">
                                <i class="fas fa-plus"></i> Add File
                            </button>
                            <div class="file-info">
                                @if(isset($uploadedFiles['matric_card']) && $uploadedFiles['matric_card'])
                                    File uploaded successfully
                                @else
                                    No file uploaded yet
                                @endif
                            </div>
                            <input type="file" name="matric_card" id="matric_file" class="file-input" accept="image/*,.pdf" onchange="previewFile(this, 'matricPreview', 'Matric Card uploaded')">
                        </div>
                    </div>

                    <div class="actions">
                       
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Save Documents
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Confirm delete function
        function confirmDelete(documentType) {
            if (confirm('Are you sure you want to delete this document?')) {
                window.location.href = '/documents/delete/' + documentType;
            }
        }

        // File preview functionality
        function previewFile(input, previewId, successMessage) {
            const file = input.files[0];
            
            if (!file) return;
            
            // Validate file size (5MB = 5 * 1024 * 1024 bytes)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            if (file.size > maxSize) {
                alert('File size exceeds 5MB limit. Please choose a smaller file.');
                input.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Please upload JPG, PNG, or PDF files only.');
                input.value = '';
                return;
            }
            
            // Format file size
            const fileSize = formatFileSize(file.size);
            
            // Get file icon based on type
            let icon = 'fa-file';
            if (file.type.includes('image')) {
                icon = 'fa-file-image';
            } else if (file.type.includes('pdf')) {
                icon = 'fa-file-pdf';
            }
            
            // Create or update preview container
            let previewContainer = document.getElementById(previewId);
            if (!previewContainer) {
                previewContainer = document.createElement('div');
                previewContainer.id = previewId;
                previewContainer.className = 'preview-container';
                input.parentNode.insertBefore(previewContainer, input.nextSibling);
            }
            
            previewContainer.innerHTML = `
                <div class="preview-file">
                    <div class="file-icon">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${fileSize} • Ready to upload</div>
                    </div>
                    <div class="file-actions">
                        <button type="button" class="file-action-btn" onclick="removeFile('${input.id}', '${previewId}')" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Update file info text
            const fileInfo = input.previousElementSibling;
            if (fileInfo && fileInfo.classList.contains('file-info')) {
                fileInfo.textContent = successMessage || 'File selected';
            }
            
            // Show success message
            showMessage('File selected successfully. Click "Save Documents" to upload.', 'success');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function removeFile(inputId, previewId) {
            const fileInput = document.getElementById(inputId);
            const previewContainer = document.getElementById(previewId);
            
            fileInput.value = '';
            
            if (previewContainer) {
                previewContainer.remove();
            }
            
            // Reset file info text
            const fileInfo = fileInput.previousElementSibling;
            if (fileInfo && fileInfo.classList.contains('file-info')) {
                fileInfo.textContent = 'No file uploaded yet';
            }
        }

        function showMessage(message, type) {
            // Remove existing message
            const existingMsg = document.getElementById('tempMessage');
            if (existingMsg) existingMsg.remove();
            
            // Create new message
            const messageDiv = document.createElement('div');
            messageDiv.id = 'tempMessage';
            messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
            messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
            
            // Insert after form messages
            const form = document.getElementById('uploadForm');
            form.parentNode.insertBefore(messageDiv, form);
            
            // Remove after 5 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }

        // Form submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            submitBtn.disabled = true;
            
            // Allow form to submit normally
        });

        // Hide messages after 5 seconds
        setTimeout(() => {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const tempMessage = document.getElementById('tempMessage');
            
            if (successMessage) successMessage.style.display = 'none';
            if (errorMessage) errorMessage.style.display = 'none';
            if (tempMessage) tempMessage.style.display = 'none';
        }, 5000);
    </script>

</body>
</html>
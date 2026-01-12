<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Invite Staff Member</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin-header.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .main-content { margin-left: 250px; padding: 40px; }
        .form-card { background: #fff; border-radius: 15px; padding: 35px; box-shadow: 0 2px 15px rgba(0,0,0,0.06); max-width: 550px; margin: 0 auto; }
        .form-header { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .invitation-info { background: #f8f9fa; padding: 18px; border-radius: 8px; margin-bottom: 25px; }
        .form-label { font-weight: 600; color: #333; font-size: 0.95rem; }
        .required::after { content: " *"; color: #dc3545; }
        .btn-custom { background-color: #bc3737; color: white; border-radius: 20px; padding: 8px 25px; border: none; font-weight: 500; font-size: 0.9rem; }
        .btn-custom:hover { background-color: #a52e2e; color: white; }
        .form-text { font-size: 0.85rem; }
        h2 { font-size: 1.8rem; }
        h4 { font-size: 1.4rem; }
        h6 { font-size: 1rem; }
        .text-muted { font-size: 0.9rem; }
        .step-number { background: #bc3737; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; margin-right: 10px; flex-shrink: 0; }
    </style>
</head>
<body>

    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
        <div id="profile">
            <div id="profile-container">
                <img id="pfp" src="{{ asset('img/racc_icon.png') }}">
                <div id="profile-dropdown">
                    @auth
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

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('admin.bookings') }}" class="nav-item ">Bookings</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item active">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Invite Staff Member</h2>
                <p class="text-muted">Send an invitation email for new staff registration</p>
            </div>
            <a href="{{ route('admin.staff') }}" class="btn btn-light" style="border-radius: 20px; padding: 6px 18px; font-size: 0.9rem;">
                ← Back
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

       @if(session('success') && session('registration_link'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
        <strong>Success!</strong> {{ session('success') }}
        <div class="registration-link mt-2">
            <strong>Registration Link (Share with staff):</strong><br>
            <co<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Invite Staff Member</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin-header.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .main-content { margin-left: 250px; padding: 40px; }
        .form-card { background: #fff; border-radius: 15px; padding: 35px; box-shadow: 0 2px 15px rgba(0,0,0,0.06); max-width: 550px; margin: 0 auto; }
        .form-header { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }
        .invitation-info { background: #f8f9fa; padding: 18px; border-radius: 8px; margin-bottom: 25px; }
        .form-label { font-weight: 600; color: #333; font-size: 0.95rem; }
        .required::after { content: " *"; color: #dc3545; }
        .btn-custom { background-color: #bc3737; color: white; border-radius: 20px; padding: 8px 25px; border: none; font-weight: 500; font-size: 0.9rem; }
        .btn-custom:hover { background-color: #a52e2e; color: white; }
        .form-text { font-size: 0.85rem; }
        h2 { font-size: 1.8rem; }
        h4 { font-size: 1.4rem; }
        h6 { font-size: 1rem; }
        .text-muted { font-size: 0.9rem; }
        .step-number { background: #bc3737; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; margin-right: 10px; flex-shrink: 0; }
        .registration-link { background: #e8f5e9; padding: 12px; border-radius: 6px; margin-top: 15px; word-break: break-all; font-size: 0.85rem; border: 1px solid #c8e6c9; }
        .alert-cancelled { background-color: #fff3cd; border-color: #ffeaa7; color: #856404; }
    </style>
</head>
<body>

    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
        <div id="profile">
            <div id="profile-container">
                <img id="pfp" src="{{ asset('img/racc_icon.png') }}">
                <div id="profile-dropdown">
                    @auth
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

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('admin.bookings') }}" class="nav-item ">Bookings</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item active">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Invite Staff Member</h2>
                <p class="text-muted">Send an invitation email for new staff registration</p>
            </div>
            <a href="{{ route('admin.staff') }}" class="btn btn-light" style="border-radius: 20px; padding: 6px 18px; font-size: 0.9rem;">
                ← Back
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success') && session('registration_link'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
                <strong>Success!</strong> {{ session('success') }}
                <div class="registration-link mt-2">
                    <strong>Registration Link (Share with staff):</strong><br>
                    <code>{{ session('registration_link') }}</code>
                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ session('registration_link') }}')" style="font-size: 0.8rem;">Copy Link</button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('cancelled'))
            <div class="alert alert-warning alert-dismissible fade show mb-4 alert-cancelled" role="alert" style="font-size: 0.9rem;">
                <strong>Invitation Cancelled!</strong> {{ session('cancelled') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.staff.store') }}" method="POST" id="invitationForm">
                @csrf
                
                <div class="form-header">
                    <h4>Staff Invitation</h4>
                </div>

                <!-- Invitation Information -->
                <div class="invitation-info">
                    <h6 class="mb-3" style="color: #333; font-weight: 600;">Invitation Process</h6>
                    <div style="font-size: 0.9rem; color: #555;">
                        <div class="d-flex align-items-start mb-2">
                            <div class="step-number">1</div>
                            <div>Enter the staff member's email address and select their role</div>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <div class="step-number">2</div>
                            <div>An invitation email will be sent automatically to the recipient</div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="step-number">3</div>
                            <div>The staff member clicks the link in the email to complete their registration</div>
                        </div>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="mb-3">
                    <label class="form-label required mb-1">Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ old('email') }}" 
                           placeholder="staff@example.com" 
                           required
                           style="font-size: 0.9rem; padding: 8px 12px;"
                           id="emailInput">
                    <div class="form-text mt-1">Invitation email will be sent to this address</div>
                    <div id="emailStatus" class="form-text mt-1" style="display: none;"></div>
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="form-label mb-1">Assign Role</label>
                    <select name="userType" class="form-select" style="font-size: 0.9rem; padding: 8px 12px;">
                        <option value="staff">Staff Member</option>
                        <option value="admin">Administrator</option>
                    </select>
                    <div class="form-text mt-1">Select the role for this staff member</div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.staff') }}" class="btn btn-outline-secondary" style="border-radius: 20px; padding: 8px 20px; font-size: 0.9rem;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-custom" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i>Send Invitation Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation and email checking
        document.getElementById('invitationForm').addEventListener('submit', function(e) {
            const emailInput = document.getElementById('emailInput');
            const submitBtn = document.getElementById('submitBtn');
            
            if (!emailInput.value.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address');
                emailInput.focus();
                return;
            }
            
            // Disable button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        });
        
        // Email validation on blur
        document.getElementById('emailInput').addEventListener('blur', function() {
            const email = this.value.trim();
            const emailStatus = document.getElementById('emailStatus');
            
            if (email && email.includes('@')) {
                // Check if email already has invitation
                checkExistingInvitation(email);
            }
        });
        
        function checkExistingInvitation(email) {
            const emailStatus = document.getElementById('emailStatus');
            emailStatus.style.display = 'block';
            emailStatus.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Checking...';
            
            fetch(`/api/check-invitation/${encodeURIComponent(email)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        if (data.status === 'pending') {
                            emailStatus.innerHTML = `<span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Pending invitation exists</span>`;
                        } else if (data.status === 'cancelled') {
                            emailStatus.innerHTML = `<span class="text-danger"><i class="fas fa-ban me-1"></i>Cancelled invitation will be replaced</span>`;
                        } else if (data.status === 'accepted') {
                            emailStatus.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i>User already registered</span>`;
                        }
                    } else {
                        emailStatus.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Email available</span>';
                    }
                })
                .catch(error => {
                    emailStatus.innerHTML = '';
                    emailStatus.style.display = 'none';
                });
        }
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Registration link copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>de>{{ session('registration_link') }}</code>
            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ session('registration_link') }}')" style="font-size: 0.8rem;">Copy Link</button>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@elseif(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="font-size: 0.9rem;">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                
                <div class="form-header">
                    <h4>Staff Invitation</h4>
                </div>

                <!-- Invitation Information -->
                <div class="invitation-info">
                    <h6 class="mb-3" style="color: #333; font-weight: 600;">Invitation Process</h6>
                    <div style="font-size: 0.9rem; color: #555;">
                        <div class="d-flex align-items-start mb-2">
                            <div class="step-number">1</div>
                            <div>Enter the staff member's email address and select their role</div>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <div class="step-number">2</div>
                            <div>An invitation email will be sent automatically to the recipient</div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="step-number">3</div>
                            <div>The staff member clicks the link in the email to complete their registration</div>
                        </div>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="mb-3">
                    <label class="form-label required mb-1">Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ old('email') }}" 
                           placeholder="staff@example.com" 
                           required
                           style="font-size: 0.9rem; padding: 8px 12px;">
                    <div class="form-text mt-1">Invitation email will be sent to this address</div>
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="form-label mb-1">Assign Role</label>
                    <select name="userType" class="form-select" style="font-size: 0.9rem; padding: 8px 12px;">
                        <option value="staff">Staff Member</option>
                        <option value="admin">Administrator</option>
                    </select>
                    <div class="form-text mt-1">Select the role for this staff member</div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.staff') }}" class="btn btn-outline-secondary" style="border-radius: 20px; padding: 8px 20px; font-size: 0.9rem;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-custom">
                        <i class="fas fa-paper-plane me-2"></i>Send Invitation Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const emailInput = document.querySelector('input[name="email"]');
            if (!emailInput.value.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address');
                emailInput.focus();
            }
        });
    </script>
</body>
</html>
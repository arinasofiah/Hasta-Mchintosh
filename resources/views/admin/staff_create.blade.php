<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Invite Staff Member</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        
        .main-content { margin-left: 250px; padding: 40px; }
        
        .form-card { 
            background: #fff; 
            border-radius: 15px; 
            padding: 40px; 
            box-shadow: 0 2px 20px rgba(0,0,0,0.08); 
            max-width: 600px; 
            margin: 0 auto;
        }
        
        .form-header { 
            border-bottom: 2px solid #eee; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        
        .invitation-info {
            background: #f0f9ff;
            border-left: 4px solid #bc3737;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .form-label { font-weight: 600; color: #333; }
        .required::after { content: " *"; color: #dc3545; }
        
        .btn-custom { 
            background-color: #bc3737; 
            color: white; 
            border-radius: 20px; 
            padding: 10px 30px; 
            border: none; 
            font-weight: 500;
        }
        .btn-custom:hover { background-color: #a52e2e; color: white; }
        
        .info-icon {
            color: #bc3737;
            font-size: 1.5rem;
            margin-right: 10px;
        }
        
        .registration-link {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            word-break: break-all;
        }
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
                <p class="text-muted">Send an invitation to register as staff member</p>
            </div>
            <a href="{{ route('admin.staff') }}" class="btn btn-light" style="border-radius: 20px;">
                ← Back to Staff List
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
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
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <div class="registration-link mt-2">
                    <strong>Registration Link:</strong><br>
                    <code>{{ session('registration_link') }}</code>
                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ session('registration_link') }}')">Copy Link</button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>Success!</strong> {{ session('success') }}
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
                    <div class="d-flex align-items-start">
                        <div class="info-icon">📧</div>
                        <div>
                            <h6>How it works:</h6>
                            <p class="mb-1">1. Enter the staff member's email address</p>
                            <p class="mb-1">2. You'll receive a registration link</p>
                            <p class="mb-0">3. Share the link with the staff member to complete registration</p>
                        </div>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="mb-4">
                    <label class="form-label required mb-2">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg" 
                           value="{{ old('email') }}" 
                           placeholder="Enter staff member's email address" 
                           required>
                    <div class="form-text">A registration link will be generated for this email</div>
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="form-label mb-2">Assign Role</label>
                    <select name="userType" class="form-select">
                        <option value="staff">Staff Member</option>
                        <option value="admin">Administrator</option>
                    </select>
                    <div class="form-text">Select the role for this staff member</div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.staff') }}" class="btn btn-outline-secondary" style="border-radius: 20px;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-custom">
                        <span class="me-2">📧</span> Generate Invitation Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Registration link copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</body>
</html>
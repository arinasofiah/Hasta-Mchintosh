<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Add New Staff</title>
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
            max-width: 800px; 
            margin: 0 auto;
        }
        
        .form-header { 
            border-bottom: 2px solid #eee; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        
        .form-section { 
            background: #f9f9f9; 
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 25px;
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
        <a href="#" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item active">Staff</a>
        <a href="#" class="nav-item">Promotions</a>
        <a href="#" class="nav-item">Settings</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Add New Staff</h2>
                <p class="text-muted">Fill in the details below to register a new staff member</p>
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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                
                <div class="form-header">
                    <h4>Staff Registration Form</h4>
                </div>

                <!-- Section 1: Personal Information -->
                <div class="form-section">
                    <h5 class="mb-3">Personal Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">IC Number</label>
                            <input type="text" name="icNumber" class="form-control" value="{{ old('icNumber') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Phone Number</label>
                            <input type="text" name="phoneNumber" class="form-control" value="{{ old('phoneNumber') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Account Credentials -->
                <div class="form-section">
                    <h5 class="mb-3">Account Credentials</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                            <div class="form-text">Minimum 8 characters</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Staff Details -->
                <div class="form-section">
                    <h5 class="mb-3">Staff Details</h5>
                    
                    <div class="mb-3">
                        <label class="form-label required">Position</label>
                        <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.staff') }}" class="btn btn-outline-secondary" style="border-radius: 20px;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-custom">
                        Register Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
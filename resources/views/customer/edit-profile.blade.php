<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Hasta Travel & Tour</title>
    
    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    
    <style>
        .edit-profile-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .edit-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .form-control:focus {
            border-color: #bc3737;
            outline: none;
            box-shadow: 0 0 0 2px rgba(188, 55, 55, 0.1);
        }
        
        .btn {
            padding: 10px 25px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #bc3737;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #a02e2e;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
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
            <button class="head_button">Details</button>
            <button class="head_button">About Us</button>
            <button class="head_button">Contact Us</button>
        </div>
        
        <div id="profile">
            <div id="profile-container">
                <img id="pfp" src="{{ asset('img/racc_icon.png') }}" alt="Profile">
                
                <div id="profile-dropdown">
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">👤 My Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">🚪 Logout</button>
                    </form>
                </div>
            </div>
            
            @auth
                <span id="username">{{ Auth::user()->name }}</span>
            @endauth
        </div>
    </div>

    <div class="edit-profile-container">
        <div class="edit-card">
            <h2 class="text-center mb-4" style="color: #bc3737;">Edit Profile</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf
                @method('PUT')
                
                {{-- User Information --}}
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                
                {{-- Customer Information --}}
                @if($customer)
                    <div class="form-group">
                        <label class="form-label">Matric Number</label>
                        <input type="text" name="matricNumber" class="form-control" value="{{ old('matricNumber', $customer->matricNumber) }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">College</label>
                        <input type="text" name="college" class="form-control" value="{{ old('college', $customer->college) }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Faculty</label>
                        <input type="text" name="faculty" class="form-control" value="{{ old('faculty', $customer->faculty) }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">License Number</label>
                        <input type="text" name="licenseNumber" class="form-control" value="{{ old('licenseNumber', $customer->licenseNumber) }}">
                    </div>
                @endif
                
                {{-- Password Update (Optional) --}}
                <div class="form-group">
                    <label class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="{{ route('customer.profile') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
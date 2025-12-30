<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hasta Travel & Tour</title>
    
    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    
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
                    <a href="{{ route('customer.profile') }}" class="dropdown-item"> My Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item"> Logout</button>
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
                    <a href="{{ route('customer.profile') }}" class="active">
        
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.bookings') }}">
            
                        My Bookings
                    </a>
                </li>
                <li>
                    <a href="#">
                        Loyalty Card
                    </a>
                </li>
                <li>
                    <a href="#">
            
                        Settings
                    </a>
                </li>
            </ul>
        </div>

    
        <div class="profile-page">
            <h2 class="profile-title">My Profile</h2>
          
            <div class="info-section">
                <div class="section-label">Personal Information</div>
                <div class="section-value">
                    <span>Name:</span>
                    <span>{{ $user->name }}</span>
                </div>
                <div class="section-value">
                    <span>Email:</span>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="section-value">
                    <span>IC Number:</span>
                    <span>{{ $user->icNumber }}</span>
                </div>
                <div class="section-value">
                    <span>Matric Number:</span>
                    <span>{{ $customer->matricNumber ?? 'Not provided' }}</span>
                </div>
            </div>
            
    
            <div class="info-section">
                <div class="section-label">Academic Information</div>
                <div class="section-value">
                    <span>College:</span>
                    <span>{{ $customer->college ?? 'Not specified' }}</span>
                </div>
                <div class="section-value">
                    <span>Faculty:</span>
                    <span>{{ $customer->faculty ?? 'Not specified' }}</span>
                </div>
            </div>
            
        
            <div class="info-section">
                <div class="section-label">License Information</div>
                <div class="section-value">
                    <span>License Number:</span>
                    <span>{{ $customer->licenseNumber ?? 'Not provided' }}</span>
                </div>
            </div>
            
           
            <div class="info-section">
                <div class="section-label">Account Information</div>
                <div class="section-value">
                    <span>Status:</span>
                    <span>
                        @if($customer && $customer->isBlacklisted)
                            <span class="status-blacklisted">Blacklisted</span>
                            @if($customer->blacklistReason)
                                <br><small style="color: #666;">Reason: {{ $customer->blacklistReason }}</small>
                            @endif
                        @else
                            <span class="status-active">Active</span>
                        @endif
                    </span>
                </div>
                
                <div class="section-value">
                    <span>Deposit Balance:</span>
                    <span class="balance">RM{{ number_format($customer->depoBalance ?? 0, 2) }}</span>
                </div>
                
            
            <div class="actions">
                <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                <a href="{{ route('customer.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    

</body>
</html>
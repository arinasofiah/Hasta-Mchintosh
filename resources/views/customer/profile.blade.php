<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hasta Travel & Tour</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
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
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
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
                    <a href="{{ route('customer.profile') }}" class="active">
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookingHistory') }}">
                        My Bookings
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.loyaltycard') }}">
                        Loyalty Card
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.documents') }}">
                        Upload Documents
                    </a>
                </li>
            </ul>
        </div>

        <div class="profile-page">
            <div class="profile-header">
                <h2 class="profile-title">My Profile</h2>
                
            </div>
          
            <div class="profile-content">
                {{-- Personal Information Card --}}
                <div class="info-card">
                    <div class="card-title">
                        <i class="fas fa-user-circle"></i>
                        Personal Information
                    </div>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">IC Number:</span>
                        <span class="info-value">{{ $user->icNumber ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $user->phoneNumber ?? '-' }}</span>
                    </div>
                </div>

                {{-- Academic Information Card --}}
                <div class="info-card">
                    <div class="card-title">
                        <i class="fas fa-graduation-cap"></i>
                        Academic Information
                    </div>
                    <div class="info-row">
                        <span class="info-label">Matric Number/Staff ID:</span>
                        <span class="info-value">{{ $customer->matricNumber ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">College:</span>
                        <span class="info-value">{{ $customer->college ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Faculty:</span>
                        <span class="info-value">{{ $customer->faculty ?? '-' }}</span>
                    </div>
                </div>

                {{-- Emergency Contact Card --}}
                <div class="info-card">
                    <div class="card-title">
                        <i class="fas fa-phone-alt"></i>
                        Emergency Contact
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contact Name:</span>
                        <span class="info-value">{{ $customer->emergency_contact_name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contact Phone:</span>
                        <span class="info-value">{{ $customer->emergency_contact_phone ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Relationship:</span>
                        <span class="info-value">{{ $customer->emergency_contact_relationship ?? '-' }}</span>
                    </div>
                </div>

                {{-- License Information Card --}}
                <div class="info-card">
                    <div class="card-title">
                        <i class="fas fa-car"></i>
                        License Information
                    </div>
                    <div class="info-row">
                        <span class="info-label">License Number:</span>
                        <span class="info-value">{{ $customer->licenseNumber ?? '-' }}</span>
                    </div>
                </div>

                {{-- Account Information Card (Full Width) --}}
                <div class="info-card account-card">
                    <div class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Account Information
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Status:</span>
                        <div class="info-value">
                            @if($customer && $customer->isBlacklisted)
                                <div class="status-badge status-blacklisted">Blacklisted</div>
                                @if($customer->blacklistReason)
                                    <div class="blacklist-reason">Reason: {{ $customer->blacklistReason }}</div>
                                @endif
                            @else
                                <div class="status-badge status-active">Active</div>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Member Since:</span>
                        <span class="info-value">{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="actions">
                <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <a href="{{ route('customer.profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effect to cards
            const cards = document.querySelectorAll('.info-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                    this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });
            
            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.98)';
                });
                
                btn.addEventListener('mouseup', function() {
                    this.style.transform = '';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
        });
    </script>
</body>
</html>
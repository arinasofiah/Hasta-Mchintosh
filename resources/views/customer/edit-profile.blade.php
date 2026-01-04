<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Hasta Travel & Tour</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                    <a href="{{ route('customer.profile') }}">
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
                    <a href="{{ route('customer.profile.edit') }}" class="active">
                        Edit Profile
                    </a>
                </li>
            </ul>
        </div>

        <div class="edit-profile-container">
            <h2 class="profile-title">Edit Profile</h2>
            
            @if(session('success'))
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf
                @method('PUT')
                
                {{-- User Information Section --}}
                <div class="info-section">
                    <div class="section-label">Personal Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" name="phone" class="form-control" 
                               value="{{ old('phone', $user->phoneNumber ?? $user->telephone->phoneNumber ?? '') }}" 
                               required>
                        <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Format: 10-11 digits (e.g., 0123456789)</small>
                    </div>
                </div>
                
                {{-- Customer Information --}}
                @if($customer)
                <div class="info-section">
                    <div class="section-label">Customer Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Matric Number</label>
                        <input type="text" name="matricNumber" class="form-control" value="{{ old('matricNumber', $customer->matricNumber) }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">College</label>
                        <select name="college" class="form-control">
                            <option value="">Select College</option>
                            <option value="KTR" {{ old('college', $customer->college) == 'KTR' ? 'selected' : '' }}>KOLEJ TUN RAZAK (KTR)</option>
                            <option value="KTF" {{ old('college', $customer->college) == 'KTF' ? 'selected' : '' }}>KOLEJ TUN FATIMAH (KTF)</option>
                            <option value="KRP" {{ old('college', $customer->college) == 'KRP' ? 'selected' : '' }}>KOLEJ RAHMAN PUTRA (KRP)</option>
                            <option value="KTDI" {{ old('college', $customer->college) == 'KTDI' ? 'selected' : '' }}>KOLEJ TUN DR. ISMAIL (KTDI)</option>
                            <option value="KTC" {{ old('college', $customer->college) == 'KTC' ? 'selected' : '' }}>KOLEJ TUANKU CANSELOR (KTC)</option>
                            <option value="KTHO" {{ old('college', $customer->college) == 'KTHO' ? 'selected' : '' }}>KOLEJ TUN HUSSEIN ONN (KTHO)</option>
                            <option value="KDSE" {{ old('college', $customer->college) == 'KDSE' ? 'selected' : '' }}>MeKOLEJ DATIN SRI ENDON (KDSE)rbau</option>
                            <option value="K9/K10" {{ old('college', $customer->college) == 'K9/K10' ? 'selected' : '' }}>KOLEJ 9/10</option>
                            <option value="KP" {{ old('college', $customer->college) == 'KP' ? 'selected' : '' }}>KOLEJ PERDANA (KP)</option>
                            <option value="KDOJ" {{ old('college', $customer->college) == 'KDOJ' ? 'selected' : '' }}>KOLEJ DATO’ ONN JAAFAR (KDOJ)</option>
                            <option value="KLG" {{ old('college', $customer->college) == 'KLG' ? 'selected' : '' }}>KLG</option>
                            <option value="UTMI" {{ old('college', $customer->college) == 'UTMI' ? 'selected' : '' }}>UTM International</option>
                            <option value="Outside UTM" {{ old('college', $customer->college) == 'Outside UTM' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Faculty</label>
                        <select name="faculty" class="form-control">
                            <option value="">Select Faculty</option>
                            <option value="FKM" {{ old('faculty', $customer->faculty) == 'FKM' ? 'selected' : '' }}>MECHANICAL ENGINEERING</option>
                            <option value="FS" {{ old('faculty', $customer->faculty) == 'FS' ? 'selected' : '' }}>SCIENCE </option>
                            <option value="FM" {{ old('faculty', $customer->faculty) == 'FM' ? 'selected' : '' }}>MANAGEMENT </option>
                            <option value="FKA" {{ old('faculty', $customer->faculty) == 'FKA' ? 'selected' : '' }}>CIVIL ENGINEERING</option>
                            <option value="FC" {{ old('faculty', $customer->faculty) == 'FC' ? 'selected' : '' }}>COMPUTING </option>
                            <option value="FKE" {{ old('faculty', $customer->faculty) == 'FKE' ? 'selected' : '' }}>ELECTRICAL ENGINEERING</option>
                            <option value="FSSH" {{ old('faculty', $customer->faculty) == 'FSSH' ? 'selected' : '' }}>SOCIAL SCIENCES AND HUMANITIES</option>
                            <option value="FKT" {{ old('faculty', $customer->faculty) == 'FKT' ? 'selected' : '' }}>CHEMICAL AND ENERGY ENGINEERING</option>
                            <option value="FABU" {{ old('faculty', $customer->faculty) == 'FABU' ? 'selected' : '' }}>BUILT ENVIRONMENT AND SURVEYING</option>
                            <option value="FEST" {{ old('faculty', $customer->faculty) == 'FEST' ? 'selected' : '' }}>EDUCATIONAL SCIENCES AND TECHNOLOGY</option>

                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">License Number *</label>
                        <input type="text" name="licenseNumber" class="form-control" value="{{ old('licenseNumber', $customer->licenseNumber) }}">
                    </div>
                </div>
                @endif
                
                {{-- Emergency Contact Section --}}
                <div class="info-section">
                    <div class="section-label">Emergency Contact Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" 
                               value="{{ old('emergency_contact_name', $customer->emergency_contact_name ?? '') }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Emergency Contact Phone</label>
                        <input type="tel" name="emergency_contact_phone" class="form-control" 
                               value="{{ old('emergency_contact_phone', $customer->emergency_contact_phone ?? '') }}">
                        <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Format: 10-11 digits (e.g., 0123456789)</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Relationship</label>
                        <select name="emergency_contact_relationship" class="form-control">
                            <option value="">Select Relationship</option>
                            <option value="Parent" {{ old('emergency_contact_relationship', $customer->emergency_contact_relationship ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                            <option value="Spouse" {{ old('emergency_contact_relationship', $customer->emergency_contact_relationship ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                            <option value="Sibling" {{ old('emergency_contact_relationship', $customer->emergency_contact_relationship ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                            <option value="Relative" {{ old('emergency_contact_relationship', $customer->emergency_contact_relationship ?? '') == 'Relative' ? 'selected' : '' }}>Relative</option>
                            <option value="Friend" {{ old('emergency_contact_relationship', $customer->emergency_contact_relationship ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
                            <option value="Other" {{ old('emergency_contact_relationship', $customer->emergency_contact_relationship ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                
                {{-- Password Update Section --}}
                <div class="info-section">
                    <div class="section-label">Password Update (Optional)</div>
                    
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control">
                        <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Leave empty if you don't want to change password</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="{{ route('customer.profile') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
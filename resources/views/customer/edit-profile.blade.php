<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Hasta Travel & Tour</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/edit-profile.css') }}" rel="stylesheet">

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
        

        <div class="edit-profile-container">
            <div class="profile-header">
                <h2 class="profile-title">Edit Profile</h2>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('customer.profile.update') }}" id="profileForm">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    {{-- Personal Information Card --}}
                    <div class="form-card">
                        <div class="card-header">
                            <i class="fas fa-user-circle"></i>
                            Personal Information
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Full Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   placeholder="Enter your full name">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', $user->email) }}" 
                                   required 
                                   placeholder="Enter your email address">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" 
                                   value="{{ old('phone', $user->phoneNumber ?? $user->telephone->phoneNumber ?? '') }}" 
                                   required 
                                   placeholder="e.g., 0123456789"
                                   pattern="[0-9]{10,11}">
                            <span class="form-hint">Format: 10-11 digits (e.g., 0123456789)</span>
                        </div>
                    </div>

                    {{-- Customer Information Card --}}
                    @if($customer)
                    <div class="form-card">
                        <div class="card-header">
                            <i class="fas fa-graduation-cap"></i>
                            Academic Information
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Matric Number</label>
                            <input type="text" name="matricNumber" class="form-control" 
                                   value="{{ old('matricNumber', $customer->matricNumber) }}" 
                                   placeholder="Enter your matric number">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">College</label>
                            <select name="college" class="form-control select">
                                <option value="">Select College</option>
                                <option value="KTR" {{ old('college', $customer->college) == 'KTR' ? 'selected' : '' }}>KOLEJ TUN RAZAK (KTR)</option>
                                <option value="KTF" {{ old('college', $customer->college) == 'KTF' ? 'selected' : '' }}>KOLEJ TUN FATIMAH (KTF)</option>
                                <option value="KRP" {{ old('college', $customer->college) == 'KRP' ? 'selected' : '' }}>KOLEJ RAHMAN PUTRA (KRP)</option>
                                <option value="KTDI" {{ old('college', $customer->college) == 'KTDI' ? 'selected' : '' }}>KOLEJ TUN DR. ISMAIL (KTDI)</option>
                                <option value="KTC" {{ old('college', $customer->college) == 'KTC' ? 'selected' : '' }}>KOLEJ TUANKU CANSELOR (KTC)</option>
                                <option value="KTHO" {{ old('college', $customer->college) == 'KTHO' ? 'selected' : '' }}>KOLEJ TUN HUSSEIN ONN (KTHO)</option>
                                <option value="KDSE" {{ old('college', $customer->college) == 'KDSE' ? 'selected' : '' }}>KOLEJ DATIN SRI ENDON (KDSE)</option>
                                <option value="K9/K10" {{ old('college', $customer->college) == 'K9/K10' ? 'selected' : '' }}>KOLEJ 9/10</option>
                                <option value="KP" {{ old('college', $customer->college) == 'KP' ? 'selected' : '' }}>KOLEJ PERDANA (KP)</option>
                                <option value="KDOJ" {{ old('college', $customer->college) == 'KDOJ' ? 'selected' : '' }}>KOLEJ DATO' ONN JAAFAR (KDOJ)</option>
                                <option value="KLG" {{ old('college', $customer->college) == 'KLG' ? 'selected' : '' }}>KLG</option>
                                <option value="UTMI" {{ old('college', $customer->college) == 'UTMI' ? 'selected' : '' }}>UTM International</option>
                                <option value="Outside UTM" {{ old('college', $customer->college) == 'Outside UTM' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Faculty</label>
                            <select name="faculty" class="form-control select">
                                <option value="">Select Faculty</option>
                                <option value="FKM" {{ old('faculty', $customer->faculty) == 'FKM' ? 'selected' : '' }}>MECHANICAL ENGINEERING (FKM)</option>
                                <option value="FS" {{ old('faculty', $customer->faculty) == 'FS' ? 'selected' : '' }}>SCIENCE (FS)</option>
                                <option value="FM" {{ old('faculty', $customer->faculty) == 'FM' ? 'selected' : '' }}>MANAGEMENT (FM)</option>
                                <option value="FKA" {{ old('faculty', $customer->faculty) == 'FKA' ? 'selected' : '' }}>CIVIL ENGINEERING (FKA)</option>
                                <option value="FC" {{ old('faculty', $customer->faculty) == 'FC' ? 'selected' : '' }}>COMPUTING (FC)</option>
                                <option value="FKE" {{ old('faculty', $customer->faculty) == 'FKE' ? 'selected' : '' }}>ELECTRICAL ENGINEERING (FKE)</option>
                                <option value="FSSH" {{ old('faculty', $customer->faculty) == 'FSSH' ? 'selected' : '' }}>SOCIAL SCIENCES AND HUMANITIES (FSSH)</option>
                                <option value="FKT" {{ old('faculty', $customer->faculty) == 'FKT' ? 'selected' : '' }}>CHEMICAL AND ENERGY ENGINEERING (FKT)</option>
                                <option value="FABU" {{ old('faculty', $customer->faculty) == 'FABU' ? 'selected' : '' }}>BUILT ENVIRONMENT AND SURVEYING (FABU)</option>
                                <option value="FEST" {{ old('faculty', $customer->faculty) == 'FEST' ? 'selected' : '' }}>EDUCATIONAL SCIENCES AND TECHNOLOGY (FEST)</option>
                            </select>
                        </div>
                    </div>

                    {{-- License & Emergency Card --}}
                    <div class="form-card">
                        <div class="card-header">
                            <i class="fas fa-car"></i>
                            License Information
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">License Number</label>
                            <input type="text" name="licenseNumber" class="form-control" 
                                   value="{{ old('licenseNumber', $customer->licenseNumber) }}" 
                                   placeholder="Enter your driving license number">
                        </div>
                    </div>

                    {{-- Emergency Contact Card --}}
                    <div class="form-card">
                        <div class="card-header">
                            <i class="fas fa-phone-alt"></i>
                            Emergency Contact
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control" 
                                   value="{{ old('emergency_contact_name', $customer->emergency_contact_name ?? '') }}" 
                                   placeholder="Enter emergency contact name">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" name="emergency_contact_phone" class="form-control" 
                                   value="{{ old('emergency_contact_phone', $customer->emergency_contact_phone ?? '') }}" 
                                   placeholder="e.g., 0123456789"
                                   pattern="[0-9]{10,11}">
                            <span class="form-hint">Format: 10-11 digits (e.g., 0123456789)</span>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Relationship</label>
                            <select name="emergency_contact_relationship" class="form-control select">
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
                    @endif

                    {{-- Password Update Card (Full Width) --}}
                    <div class="form-card full-width">
                        <div class="card-header">
                            <i class="fas fa-lock"></i>
                            Password Update (Optional)
                        </div>
                        
                        <div class="form-grid" style="grid-template-columns: repeat(2, 1fr); gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Enter new password"
                                       id="newPassword">
                                <span class="form-hint">Leave empty if you don't want to change password</span>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" 
                                       placeholder="Confirm new password"
                                       id="confirmPassword">
                                <span class="form-hint" id="passwordMatch"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="actions">
                    <a href="{{ route('customer.profile') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password validation
            const newPassword = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            const passwordMatch = document.getElementById('passwordMatch');
            
            function validatePassword() {
                if (newPassword.value && confirmPassword.value) {
                    if (newPassword.value !== confirmPassword.value) {
                        passwordMatch.textContent = "Passwords don't match";
                        passwordMatch.style.color = '#e74c3c';
                        return false;
                    } else {
                        passwordMatch.textContent = "Passwords match";
                        passwordMatch.style.color = '#27ae60';
                        return true;
                    }
                }
                passwordMatch.textContent = "";
                return true;
            }
            
            if (newPassword && confirmPassword) {
                newPassword.addEventListener('input', validatePassword);
                confirmPassword.addEventListener('input', validatePassword);
            }
            
            // Form submission with loading state
            const form = document.getElementById('profileForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function(e) {
                if (!validatePassword()) {
                    e.preventDefault();
                    alert('Please make sure passwords match.');
                    return;
                }
                
                // Show loading state
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                submitBtn.disabled = true;
                
                // Re-enable after 5 seconds in case of error
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
            
            // Add hover effects to form cards
            const formCards = document.querySelectorAll('.form-card');
            formCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                    this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
</body>
</html>
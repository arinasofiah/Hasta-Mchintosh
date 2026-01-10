<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel - Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: linear-gradient(to right, #e87a73 40%, #fdebe7 60%);
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .left {
            position: relative;
            flex: 0 0 35%;
            padding: 30px;
            color: white;
            background: linear-gradient(135deg, #FF6F6F 0%, #E63946 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .left-content {
            z-index: 2;
            position: relative;
        }

        .left h1 {
            font-size: 28px;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .left p {
            font-size: 14px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
            padding: 20px;
            overflow-y: auto;
        }

        .card {
            width: 440px;
            max-width: 100%;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .step-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .step-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
        }

        .step-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .step-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ddd;
            transition: all 0.3s;
        }

        .step-dot.active {
            background: #e63946;
            transform: scale(1.1);
        }

        /* Form Styles */
        label {
            font-size: 13px;
            font-weight: 600;
            margin-top: 16px;
            margin-bottom: 6px;
            display: block;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 11px 14px;
            margin-bottom: 4px;
            border-radius: 8px;
            border: 1.5px solid #e0e0e0;
            font-size: 14px;
            transition: border 0.3s;
            background: #fff;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #e63946;
            box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.1);
        }

        input.error, select.error {
            border-color: #e63946 !important;
            background-color: #fff5f5;
        }

        input[readonly] {
            background: #f8f9fa;
            border-color: #ddd;
        }

        .hint {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .error-message {
            color: #e63946;
            font-size: 12px;
            margin-top: 4px;
            margin-bottom: 8px;
            display: block;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-next {
            background: #e63946;
            color: white;
        }

        .btn-next:hover {
            background: #d32f2f;
            transform: translateY(-1px);
        }

        .btn-prev {
            background: #6c757d;
            color: white;
        }

        .btn-prev:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .btn-submit {
            background: #28a745;
            color: white;
            width: 100%;
        }

        .btn-submit:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Form Step Styles */
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .optional-tag {
            color: #666;
            font-size: 12px;
            font-weight: normal;
        }

        .form-header {
            text-align: center;
            margin-bottom: 8px;
        }

        .form-header h2 {
            font-size: 20px;
            color: #e63946;
            margin-bottom: 4px;
        }

        .form-header p {
            color: #666;
            font-size: 13px;
        }

        .server-error {
            color: #e63946;
            background-color: #fff5f5;
            border: 1px solid #ffcccc;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .left, .right {
                flex: none;
                width: 100%;
            }
            
            .left {
                padding: 20px;
                text-align: center;
                flex: 0 0 auto;
                min-height: 180px;
            }
            
            .left h1 {
                font-size: 24px;
            }
            
            .card {
                padding: 20px;
                box-shadow: none;
                width: 100%;
            }
            
            .step-title {
                font-size: 18px;
            }
            
            .step-subtitle {
                font-size: 13px;
            }
            
            input, select {
                padding: 10px 12px;
                font-size: 13px;
            }
            
            label {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Left Side (Welcome - Smaller) -->
    <div class="left">
        <div class="left-content">
            <h1>Join Hasta Travel</h1>
            <p>Create your account in 3 simple steps</p>
        </div>
    </div>

    <!-- Right Side (Registration Form - Bigger) -->
    <div class="right">
        <div class="card">
            <div class="form-header">
                <h2>Create Account</h2>
                <p>Fill in your details to get started</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step-dot active" data-step="1"></div>
                <div class="step-dot" data-step="2"></div>
                <div class="step-dot" data-step="3"></div>
            </div>

            <form method="POST" action="/register" id="registrationForm">
                @csrf
                
                <!-- Server-side errors -->
                @if($errors->has('server_error'))
                    <div class="server-error">
                        {{ $errors->first('server_error') }}
                    </div>
                @endif
                
                <!-- Step 1: Personal Information -->
                <div class="form-step active" id="step1">
                    <div class="step-header">
                        <h2 class="step-title">Step 1: Personal Information</h2>
                    </div>

                    <!-- Full Name -->
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="name" value="{{ old('name') }}" required 
                        
                           class="{{ $errors->has('name') ? 'error' : '' }}">
                    @if($errors->has('name'))
                        <div class="error-message">{{ $errors->first('name') }}</div>
                    @endif

                    <!-- Email -->
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                           placeholder="eg. email@example.com"
                           class="{{ $errors->has('email') ? 'error' : '' }}">
                    @if($errors->has('email'))
                        <div class="error-message">{{ $errors->first('email') }}</div>
                    @endif

                    <!-- IC Number -->
                    <label for="icNumber">IC / Passport Number</label>
                    <input type="text" id="icNumber" name="icNumber" value="{{ old('icNumber') }}" required 
                           placeholder="e.g. 010203-14-5678"
                           class="{{ $errors->has('icNumber') ? 'error' : '' }}">
                    @if($errors->has('icNumber'))
                        <div class="error-message">{{ $errors->first('icNumber') }}</div>
                    @endif

                    <!-- Phone Number -->
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required 
                           placeholder="e.g. 0123456789"
                           class="{{ $errors->has('phone') ? 'error' : '' }}">
                    @if($errors->has('phone'))
                        <div class="error-message">{{ $errors->first('phone') }}</div>
                    @endif

                    <div class="buttons">
                        <button type="button" class="btn btn-next" onclick="nextStep(2)">Next →</button>
                    </div>
                </div>

                <!-- Step 2: Additional Information -->
                <div class="form-step" id="step2">
                    <div class="step-header">
                        <h2 class="step-title">Step 2: Additional Information</h2>
                    </div>

                    <!-- Matric Number / Staff ID -->
                    <label for="matricNumber">Matric Number / Staff ID* </label>
                    <input type="text" id="matricNumber" name="matricNumber" value="{{ old('matricNumber') }}" 
                           placeholder="e.g. A21CS0001 or S12345"
                           class="{{ $errors->has('matricNumber') ? 'error' : '' }}">
                    @if($errors->has('matricNumber'))
                        <div class="error-message">{{ $errors->first('matricNumber') }}</div>
                    @endif

                    <!-- College (Optional) -->
                    <label for="college">College </label>
                    <select id="college" name="college" class="{{ $errors->has('college') ? 'error' : '' }}">
                        <option value="">Select College (if applicable)</option>
                        <option value="KTR" {{ old('college') == 'KTR' ? 'selected' : '' }}>KOLEJ TUN RAZAK (KTR)</option>
                        <option value="KTF" {{ old('college') == 'KTF' ? 'selected' : '' }}>KOLEJ TUN FATIMAH (KTF)</option>
                        <option value="KRP" {{ old('college') == 'KRP' ? 'selected' : '' }}>KOLEJ RAHMAN PUTRA (KRP)</option>
                        <option value="KTDI" {{ old('college') == 'KTDI' ? 'selected' : '' }}>KOLEJ TUN DR. ISMAIL (KTDI)</option>
                        <option value="KTC" {{ old('college') == 'KTC' ? 'selected' : '' }}>KOLEJ TUANKU CANSELOR (KTC)</option>
                        <option value="KTHO" {{ old('college') == 'KTHO' ? 'selected' : '' }}>KOLEJ TUN HUSSEIN ONN (KTHO)</option>
                        <option value="KDSE" {{ old('college') == 'KDSE' ? 'selected' : '' }}>KOLEJ DATIN SRI ENDON (KDSE)</option>
                        <option value="K9/K10" {{ old('college') == 'K9/K10' ? 'selected' : '' }}>KOLEJ 9/10</option>
                        <option value="KP" {{ old('college') == 'KP' ? 'selected' : '' }}>KOLEJ PERDANA (KP)</option>
                        <option value="KDOJ" {{ old('college') == 'KDOJ' ? 'selected' : '' }}>KOLEJ DATO' ONN JAAFAR (KDOJ)</option>
                        <option value="KLG" {{ old('college') == 'KLG' ? 'selected' : '' }}>KLG</option>
                        <option value="UTMI" {{ old('college') == 'UTMI' ? 'selected' : '' }}>UTM International</option>
                        <option value="Outside UTM" {{ old('college') == 'Outside UTM' ? 'selected' : '' }}>None</option>
                    </select>
                    @if($errors->has('college'))
                        <div class="error-message">{{ $errors->first('college') }}</div>
                    @endif

                    <!-- Faculty (Optional) -->
                    <label for="faculty">Faculty </label>
                    <select id="faculty" name="faculty" class="{{ $errors->has('faculty') ? 'error' : '' }}">
                        <option value="">Select Faculty (if applicable)</option>
                        <option value="FKM" {{ old('faculty') == 'FKM' ? 'selected' : '' }}>MECHANICAL ENGINEERING</option>
                        <option value="FS" {{ old('faculty') == 'FS' ? 'selected' : '' }}>SCIENCE</option>
                        <option value="FM" {{ old('faculty') == 'FM' ? 'selected' : '' }}>MANAGEMENT</option>
                        <option value="FKA" {{ old('faculty') == 'FKA' ? 'selected' : '' }}>CIVIL ENGINEERING</option>
                        <option value="FC" {{ old('faculty') == 'FC' ? 'selected' : '' }}>COMPUTING</option>
                        <option value="FKE" {{ old('faculty') == 'FKE' ? 'selected' : '' }}>ELECTRICAL ENGINEERING</option>
                        <option value="FSSH" {{ old('faculty') == 'FSSH' ? 'selected' : '' }}>SOCIAL SCIENCES AND HUMANITIES</option>
                        <option value="FKT" {{ old('faculty') == 'FKT' ? 'selected' : '' }}>CHEMICAL AND ENERGY ENGINEERING</option>
                        <option value="FABU" {{ old('faculty') == 'FABU' ? 'selected' : '' }}>BUILT ENVIRONMENT AND SURVEYING</option>
                        <option value="FEST" {{ old('faculty') == 'FEST' ? 'selected' : '' }}>EDUCATIONAL SCIENCES AND TECHNOLOGY</option>
                    </select>
                    @if($errors->has('faculty'))
                        <div class="error-message">{{ $errors->first('faculty') }}</div>
                    @endif

                    <!-- Driving License (Optional) -->
                    <label for="licenseNumber">Driving License Number* </label>
                    <input type="text" id="licenseNumber" name="licenseNumber" value="{{ old('licenseNumber') }}" 
                           placeholder="e.g. D12345678"
                           class="{{ $errors->has('licenseNumber') ? 'error' : '' }}">
                    @if($errors->has('licenseNumber'))
                        <div class="error-message">{{ $errors->first('licenseNumber') }}</div>
                    @endif

                    <div class="buttons">
                        <button type="button" class="btn btn-prev" onclick="prevStep(1)">← Back</button>
                        <button type="button" class="btn btn-next" onclick="nextStep(3)">Next →</button>
                    </div>
                </div>

                <!-- Step 3: Security & Emergency Info -->
                <div class="form-step" id="step3">
                    <div class="step-header">
                        <h2 class="step-title">Step 3: Security & Emergency Info</h2>
                    </div>

                    <!-- Emergency Contact -->
                    <label for="emergencyName">Emergency Contact Name</label>
                    <input type="text" id="emergencyName" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required 
                    
                           class="{{ $errors->has('emergency_contact_name') ? 'error' : '' }}">
                    @if($errors->has('emergency_contact_name'))
                        <div class="error-message">{{ $errors->first('emergency_contact_name') }}</div>
                    @endif

                    <label for="emergencyPhone">Emergency Contact Phone</label>
                    <input type="tel" id="emergencyPhone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required 
                           placeholder="e.g. 0123456789"
                           class="{{ $errors->has('emergency_contact_phone') ? 'error' : '' }}">
                    @if($errors->has('emergency_contact_phone'))
                        <div class="error-message">{{ $errors->first('emergency_contact_phone') }}</div>
                    @endif

                    <label for="emergencyRelationship">Emergency Contact Relationship</label>
                    <select id="emergencyRelationship" name="emergency_contact_relationship" required
                            class="{{ $errors->has('emergency_contact_relationship') ? 'error' : '' }}">
                        <option value="">Select relationship</option>
                        <option value="parent" {{ old('emergency_contact_relationship') == 'parent' ? 'selected' : '' }}>Parent</option>
                        <option value="sibling" {{ old('emergency_contact_relationship') == 'sibling' ? 'selected' : '' }}>Sibling</option>
                        <option value="spouse" {{ old('emergency_contact_relationship') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                        <option value="friend" {{ old('emergency_contact_relationship') == 'friend' ? 'selected' : '' }}>Friend</option>
                        <option value="relative" {{ old('emergency_contact_relationship') == 'relative' ? 'selected' : '' }}>Relative</option>
                        <option value="other" {{ old('emergency_contact_relationship') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @if($errors->has('emergency_contact_relationship'))
                        <div class="error-message">{{ $errors->first('emergency_contact_relationship') }}</div>
                    @endif

                    <!-- Password -->
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Create a strong password"
                           class="{{ $errors->has('password') ? 'error' : '' }}">
                    @if($errors->has('password'))
                        <div class="error-message">{{ $errors->first('password') }}</div>
                    @endif
                    <small class="hint">Minimum 8 characters with letters and numbers</small>

                    <label for="passwordConfirm">Confirm Password</label>
                    <input type="password" id="passwordConfirm" name="password_confirmation" required 
                           placeholder="Enter password again"
                           class="{{ $errors->has('password_confirmation') ? 'error' : '' }}">
                    @if($errors->has('password_confirmation'))
                        <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                    @endif

                    <div class="buttons">
                        <button type="button" class="btn btn-prev" onclick="prevStep(2)">← Back</button>
                        <button type="submit" class="btn btn-submit" id="submitBtn">Create Account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Multi-step form functionality
    let currentStep = 1;
    const totalSteps = 3;

    // Auto-scroll to first error field when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Check if there are any server-side errors
        const errorFields = document.querySelectorAll('.error');
        if (errorFields.length > 0) {
            // Find which step has errors
            for (let i = 1; i <= totalSteps; i++) {
                const stepElement = document.getElementById(`step${i}`);
                const stepErrors = stepElement.querySelectorAll('.error');
                if (stepErrors.length > 0) {
                    // Navigate to the step with errors
                    document.querySelectorAll('.form-step').forEach(step => {
                        step.classList.remove('active');
                    });
                    stepElement.classList.add('active');
                    currentStep = i;
                    updateStepIndicator();
                    
                    // Scroll to first error
                    stepErrors[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    break;
                }
            }
        }
    });

    function updateStepIndicator() {
        document.querySelectorAll('.step-dot').forEach((dot, index) => {
            if (index + 1 <= currentStep) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    function nextStep(step) {
        // Validate current step before proceeding
        if (!validateStep(currentStep)) {
            return;
        }

        // Hide current step with animation
        const currentStepElement = document.getElementById(`step${currentStep}`);
        currentStepElement.style.opacity = '0';
        currentStepElement.style.transform = 'translateY(-8px)';
        
        setTimeout(() => {
            currentStepElement.classList.remove('active');
            
            // Show next step
            const nextStepElement = document.getElementById(`step${step}`);
            nextStepElement.classList.add('active');
            
            // Update current step
            currentStep = step;
            updateStepIndicator();
            
            // Scroll to top of form
            document.querySelector('.card').scrollTop = 0;
        }, 300);
    }

    function prevStep(step) {
        // Hide current step with animation
        const currentStepElement = document.getElementById(`step${currentStep}`);
        currentStepElement.style.opacity = '0';
        currentStepElement.style.transform = 'translateY(8px)';
        
        setTimeout(() => {
            currentStepElement.classList.remove('active');
            
            // Show previous step
            const prevStepElement = document.getElementById(`step${step}`);
            prevStepElement.classList.add('active');
            
            // Update current step
            currentStep = step;
            updateStepIndicator();
            
            // Scroll to top of form
            document.querySelector('.card').scrollTop = 0;
        }, 300);
    }

    function validateStep(step) {
        let isValid = true;
        const stepElement = document.getElementById(`step${step}`);
        
        // Clear previous client-side errors
        stepElement.querySelectorAll('.error-message.client-side').forEach(error => {
            error.remove();
        });
        stepElement.querySelectorAll('input.error, select.error').forEach(input => {
            input.classList.remove('error');
        });
        
        // Get all required inputs in current step
        const requiredInputs = stepElement.querySelectorAll('input[required], select[required]');
        
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
                
                // Create error message
                const errorMsg = document.createElement('div');
                errorMsg.className = 'error-message client-side';
                errorMsg.textContent = 'This field is required';
                input.parentNode.insertBefore(errorMsg, input.nextSibling);
            }
        });

        // Special validation for email
        if (step === 1) {
            const emailInput = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailInput.value && !emailRegex.test(emailInput.value)) {
                isValid = false;
                emailInput.classList.add('error');
                let errorMsg = emailInput.nextElementSibling;
                if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message client-side';
                    errorMsg.textContent = 'Please enter a valid email address';
                    emailInput.parentNode.insertBefore(errorMsg, emailInput.nextSibling);
                }
            }
        }

        // Password validation for step 3
        if (step === 3) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('passwordConfirm');
            
            // Password length validation
            if (password.value && password.value.length < 8) {
                isValid = false;
                password.classList.add('error');
                let errorMsg = password.nextElementSibling;
                if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message client-side';
                    errorMsg.textContent = 'Password must be at least 8 characters';
                    password.parentNode.insertBefore(errorMsg, password.nextSibling);
                }
            }
            
            // Password match validation
            if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
                isValid = false;
                confirmPassword.classList.add('error');
                let errorMsg = confirmPassword.nextElementSibling;
                if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message client-side';
                    errorMsg.textContent = 'Passwords do not match';
                    confirmPassword.parentNode.insertBefore(errorMsg, confirmPassword.nextSibling);
                }
            }
        }

        if (!isValid) {
            // Scroll to first error
            const firstError = stepElement.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return isValid;
    }

    // Form submission
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        // Clear all client-side errors first
        document.querySelectorAll('.error-message.client-side').forEach(error => error.remove());
        document.querySelectorAll('input.error, select.error').forEach(input => input.classList.remove('error'));
        
        // Validate all steps before submission
        for (let i = 1; i <= totalSteps; i++) {
            if (!validateStep(i)) {
                e.preventDefault();
                // Go to step with error
                document.querySelectorAll('.form-step').forEach(step => {
                    step.classList.remove('active');
                    step.style.opacity = '1';
                    step.style.transform = 'translateY(0)';
                });
                document.getElementById(`step${i}`).classList.add('active');
                currentStep = i;
                updateStepIndicator();
                
                // Scroll to first error
                const firstError = document.getElementById(`step${i}`).querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
        }
        
        // Show loading state on submit button
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = 'Creating Account...';
        submitBtn.disabled = true;
    });

    // Input validation on blur
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('blur', function() {
            // Only validate if it's in the current step
            if (this.closest('.form-step.active')) {
                validateStep(currentStep);
            }
        });
        
        // Clear client-side error on input
        input.addEventListener('input', function() {
            this.classList.remove('error');
            const errorMsg = this.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message.client-side')) {
                errorMsg.remove();
            }
        });
    });

    // Enable submit button when all required fields are filled
    function checkFormCompletion() {
        let allFilled = true;
        document.querySelectorAll('input[required], select[required]').forEach(input => {
            if (!input.value.trim()) {
                allFilled = false;
            }
        });
        
        // Check password match if passwords are filled
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('passwordConfirm');
        if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
            allFilled = false;
        }
        
        document.getElementById('submitBtn').disabled = !allFilled;
    }

    // Check form completion on input
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', checkFormCompletion);
    });

    // Initialize
    updateStepIndicator();
    checkFormCompletion();
</script>

</body>
</html>
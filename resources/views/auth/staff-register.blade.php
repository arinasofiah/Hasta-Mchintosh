<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Staff Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        .registration-card { 
            max-width: 600px; 
            width: 100%;
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            padding: 40px; 
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }
        .invitation-header {
            background: #f0f9ff;
            border-left: 4px solid #bc3737;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .btn-custom { 
            background-color: #bc3737; 
            color: white; 
            border-radius: 20px; 
            padding: 10px 30px; 
            border: none; 
            font-weight: 500;
            width: 100%;
        }
        .btn-custom:hover { 
            background-color: #a52e2e; 
            color: white;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="registration-card">
            <div class="logo">
                <img src="{{ asset('img/hasta_logo.jpg') }}" alt="Hasta Logo">
            </div>
            
            <div class="invitation-header">
                <h4>Complete Your Staff Registration</h4>
                <p class="mb-1"><strong>Invited Email:</strong> {{ $user->email }}</p>
                <p class="mb-0"><strong>Role:</strong> {{ ucfirst($user->userType) }}</p>
                @if($user->invitation_expires_at)
                    <p class="mb-0 text-muted">
                        <small>Invitation expires: {{ $user->invitation_expires_at->format('M d, Y \a\t g:i A') }}</small>
                    </p>
                @endif
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('staff.completeRegistration', $user->invitation_token) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label required">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required">IC Number</label>
                    <input type="text" name="icNumber" class="form-control" value="{{ old('icNumber') }}" required>
                    <div class="form-text">Must be unique</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required">Phone Number</label>
                    <input type="text" name="phoneNumber" class="form-control" value="{{ old('phoneNumber') }}" required>
                    <div class="form-text">Will be used for contact</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required">Position</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
                    <div class="form-text">e.g., Driver, Manager, Coordinator</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                    <div class="form-text">Minimum 8 characters</div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label required">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-custom">
                    Complete Registration
                </button>
                
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        Already have an account? Login here
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
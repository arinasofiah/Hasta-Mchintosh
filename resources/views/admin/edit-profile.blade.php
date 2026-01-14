<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Hasta Travel & Tour</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-header.css') }}" rel="stylesheet">

    <style>
        body {font-family:'Poppins',sans-serif;background:#f6f7fb}
        .edit-card {
            max-width:700px;
            margin:40px auto;
            background:#fff;
            padding:30px;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08)
        }
        .form-label {font-weight:600}
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
                 @if(Auth::user()->userType === 'staff')
                          <a href="{{ route('admin.profile') }}" class="dropdown-item">My Profile</a>
                        @endif
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

<div class="edit-card">
    <h3 class="mb-4">
    </i> Edit Profile
    </h3>

    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">IC Number</label>
            <input type="text" name="icNumber" class="form-control"
                   value="{{ old('icNumber', $user->icNumber) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phoneNumber" class="form-control"
                   value="{{ old('phoneNumber', $user->phoneNumber) }}">
        </div>

        <hr>

        <div class="mb-3">
            <label class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control">
            <small class="text-muted">Leave blank to keep current password</small>
        </div>

        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.profile') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta - Commission</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    
    <style>
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            position: relative; 
        }

        .avatar-circle {
            width: 120px;
            height: 120px;
            background-color: #87CEEB;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-circle img {
            width: 100%;
            height: auto;
        }

        .info-text h3 { font-weight: 700; margin-bottom: 10px; font-size: 24px; }
        .info-text p { margin-bottom: 5px; font-size: 18px; color: #333; }
        .info-label { font-weight: 500; color: #555; }

        .edit-btn {
            position: absolute;
            bottom: 40px;
            left: 130px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .edit-btn:hover { background-color: #f0f0f0; }

        .commission-bar {
            background: white;
            border-radius: 15px;
            padding: 30px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .commission-amount {
            font-size: 22px;
            font-weight: 600;
        }

        .redeem-btn {
            background-color: #90EE90;
            color: #2E8B57;
            border: none;
            padding: 10px 40px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: 0.3s;
        }
        .redeem-btn:hover {
            background-color: #7CFC00;
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
    <nav class="d-flex flex-column">
        <a href="{{ route('staff.dashboard') }}" class="nav-item">Handle Booking</a>
        <a href="{{ route('staff.commission') }}" class="nav-item active">Commissions</a>
    </nav>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-5" style="color: #000;">Commission</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="profile-card">
        <div class="avatar-circle">
            <img src="{{ asset('img/avatar_placeholder.png') }}" alt="Avatar" style="opacity: 0.8;"> 
        </div>

        <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editBankModal">
            ✏️
        </button>

        <div class="info-text">
            <h3>Name: {{ strtoupper($user->name) }}</h3>
            <p><span class="info-label">Position:</span> {{ $staff->position ?? 'Staff' }}</p>
            <p><span class="info-label">Bank:</span> {{ $staff->bank_name ?? 'Not Set' }}</p>
            <p><span class="info-label">Account Number:</span> {{ $staff->bank_account_number ?? 'Not Set' }}</p>
        </div>
    </div>

    <div class="commission-bar">
        <div class="commission-amount">
            Current Commission: RM{{ $staff->commissionCount ?? 0 }}
        </div>
        
        <form action="{{ route('staff.commission.redeem') }}" method="POST">
            @csrf
            <button type="submit" class="redeem-btn">Redeem</button>
        </form>
    </div>

</div>

<div class="modal fade" id="editBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('staff.commission.update') }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Bank Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" placeholder="e.g. RHB Bank" value="{{ $staff->bank_name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Account Number</label>
                    <input type="text" name="bank_account_number" class="form-control" placeholder="e.g. 1234567890" value="{{ $staff->bank_account_number }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background-color: #bc3737; border:none;">Save</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
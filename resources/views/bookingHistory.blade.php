<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking History - Hasta Travel & Tour</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .content-with-sidebar {
            display: flex;
            min-height: calc(100vh - 180px);
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            gap: 30px;
        }

        .sidebar {
            width: 250px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px 0;
            flex-shrink: 0;
            height: fit-content;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            font-size: 15px;
        }

        .sidebar-menu a:hover {
            background-color: #f8f9fa;
            color: #bc3737;
            border-left-color: #bc3737;
        }

        .sidebar-menu a.active {
            background-color: #f8f9fa;
            color: #bc3737;
            font-weight: 600;
            border-left-color: #bc3737;
        }

        .sidebar-icon {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* ===== Booking History Page Styles ===== */
        .booking-history-page {
            flex: 1;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .profile-title {
            color: #bc3737;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
            font-size: 28px;
            font-weight: 700;
        }

        .booking-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .booking-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid;
        }

        .section-title.active {
            color: #3498db;
            border-bottom-color: #3498db;
        }

        .section-title.past {
            color: #7f8c8d;
            border-bottom-color: #7f8c8d;
        }

        .section-title.pending {
            color: #f39c12;
            border-bottom-color: #f39c12;
        }

        .booking-card {
            display: flex;
            align-items: center;
            padding: 20px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #eee;
            transition: all 0.3s;
        }

        .booking-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .car-image {
            width: 140px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 25px;
            background: linear-gradient(45deg, #f5f5f5, #e9e9e9);
            padding: 10px;
        }

        .booking-details {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .car-info h3 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }

        .car-info p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            min-width: 100px;
            text-align: center;
        }

        .dates {
            font-size: 14px;
            color: #666;
            min-width: 160px;
            text-align: center;
            background: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .status {
            font-size: 14px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 4px;
            text-align: center;
            min-width: 100px;
        }

        .status.active {
            background-color: #d6eaf8;
            color: #3498db;
        }

        .status.completed {
            background-color: #d4efdf;
            color: #27ae60;
        }

        .status.cancelled {
            background-color: #fdecea;
            color: #e74c3c;
        }

        .status.pending {
            background-color: #fef5e7;
            color: #f39c12;
        }

        .status.confirmed {
            background-color: #e8f4fc;
            color: #2980b9;
        }

        .payment-status {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-block;
            margin-top: 5px;
        }

        .payment-status.paid {
            background-color: #d4efdf;
            color: #27ae60;
        }

        .payment-status.partial {
            background-color: #fef5e7;
            color: #f39c12;
        }

        .payment-status.unpaid {
            background-color: #fdecea;
            color: #e74c3c;
        }

        .payment-info {
            font-size: 14px;
            margin-top: 8px;
        }

        .payment-info .paid {
            color: #27ae60;
        }

        .payment-info .remaining {
            color: #e74c3c;
            font-weight: bold;
        }

        .action-btn {
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
            background-color: white;
            color: #bc3737;
            border: 2px solid #bc3737;
            margin-right: 10px;
        }

        .action-btn:hover {
            background-color: #bc3737;
            color: white;
            border-color: #bc3737;
        }

        .action-btn-secondary {
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
            background-color: white;
            color: #666;
            border: 2px solid #666;
            margin-right: 10px;
        }

        .action-btn-secondary:hover {
            background-color: #666;
            color: white;
            border-color: #666;
        }

        .action-btn-danger {
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
            background-color: white;
            color: #e74c3c;
            border: 2px solid #e74c3c;
        }

        .action-btn-danger:hover {
            background-color: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }

        .action-btn-success {
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
            background-color: white;
            color: #27ae60;
            border: 2px solid #27ae60;
            margin-right: 10px;
        }

        .action-btn-success:hover {
            background-color: #27ae60;
            color: white;
            border-color: #27ae60;
        }

        .action-btn-link {
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 14px;
            background-color: white;
            color: #bc3737;
            border: 2px solid #bc3737;
            display: inline-block;
            text-align: center;
        }

        .action-btn-link:hover {
            background-color: #bc3737;
            color: white;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-size: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px dashed #ddd;
        }

        /* Past bookings section - combined */
        .past-bookings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .past-bookings-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .past-bookings-section h4 {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        /* Date Links */
        .date-link {
            display: inline-block;
        }

        .date-link-text {
            color: #bc3737;
            text-decoration: none;
            font-weight: 500;
            padding: 3px 6px;
            border-radius: 3px;
            transition: all 0.3s;
            border-bottom: 1px dotted #bc3737;
        }

        .date-link-text:hover {
            background-color: #bc3737;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid transparent;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
            padding: 20px;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .modal-header {
            background-color: #bc3737;
            color: white;
            padding: 20px 30px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 22px;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 30px;
        }

        .detail-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .detail-section h3 {
            font-size: 16px;
            color: #bc3737;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
            font-size: 14px;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .detail-value.remaining-balance {
            color: #e74c3c;
            font-weight: bold;
        }

        .detail-value.paid-amount {
            color: #27ae60;
        }

        .car-detail-image {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            display: block;
            border-radius: 6px;
        }

        .total-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #bc3737;
            color: white;
            border: 2px solid #bc3737;
        }

        .btn-primary:hover {
            background-color: #a02e2e;
            border-color: #a02e2e;
        }

        .btn-secondary {
            background-color: white;
            color: #333;
            border: 2px solid #ddd;
        }

        .btn-secondary:hover {
            background-color: #f8f9fa;
            border-color: #bc3737;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            border: 2px solid #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .btn-success {
            background-color: #27ae60;
            color: white;
            border: 2px solid #27ae60;
        }

        .btn-success:hover {
            background-color: #219653;
            border-color: #219653;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #333;
            font-size: 14px;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
        }

        .form-group textarea:focus {
            border-color: #bc3737;
            outline: none;
            box-shadow: 0 0 0 2px rgba(188, 55, 55, 0.1);
        }

        .warning-message {
            background-color: #fdecea;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #721c24;
            font-size: 14px;
        }

        .info-message {
            background-color: #e8f4fc;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #2c3e50;
            font-size: 14px;
        }

        .payment-actions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: center;
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 768px) {
            .content-with-sidebar {
                flex-direction: column;
                padding: 15px;
                gap: 20px;
                margin: 15px auto;
            }
            
            .sidebar {
                width: 100%;
                padding: 15px 0;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .sidebar-menu li {
                flex: 1;
                min-width: 120px;
            }
            
            .sidebar-menu a {
                flex-direction: column;
                text-align: center;
                padding: 12px 15px;
                border-left: none;
                border-bottom: 3px solid transparent;
            }
            
            .sidebar-menu a.active {
                border-left: none;
                border-bottom: 3px solid #bc3737;
            }
            
            .sidebar-menu a:hover {
                border-left: none;
                border-bottom: 3px solid #bc3737;
            }
            
            .sidebar-icon {
                margin-right: 0;
                margin-bottom: 5px;
            }
            
            .booking-history-page {
                padding: 20px;
            }
            
            .booking-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }
            
            .car-image {
                width: 100%;
                height: 120px;
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .booking-details {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
                width: 100%;
            }
            
            .price, .dates, .status {
                width: 100%;
                text-align: left;
                padding: 8px;
            }
            
            .action-btn, .action-btn-secondary, .action-btn-danger {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
            
            .modal-content {
                margin: 0;
            }
            
            .modal {
                padding: 10px;
            }
            
            .modal-body {
                padding: 20px;
            }
            
            .modal-footer {
                padding: 15px 20px;
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
            
            .past-bookings-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .profile-title {
                font-size: 22px;
            }
            
            .section-title {
                font-size: 18px;
            }
            
            .booking-details {
                gap: 10px;
            }
            
            .dates {
                font-size: 12px;
            }
        }
    </style>
</head>
<body class="has-scrollable-content">
  
<body class="has-scrollable-content">
    
    {{-- Header --}}
    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}" alt="Hasta Logo">
        
        <div id="menu">
            <button class="head_button" onclick="window.location.href='{{ route('customer.dashboard') }}'">Home</button>
            <button class="head_button" onclick="window.location.href='{{ route('customer.dashboard') }}'">Vehicles</button>
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
                    <a href="{{ route('customer.profile') }}">
                        <span class="sidebar-icon"><i class="fas fa-user"></i></span>
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookingHistory') }}" class="active">
                        <span class="sidebar-icon"><i class="fas fa-history"></i></span>
                        My Bookings
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.loyaltycard') }}">
                        <span class="sidebar-icon"><i class="fas fa-id-card"></i></span>
                        Loyalty Card
                    </a>
                </li>
               <li>
                    <a href="{{ route('customer.documents') }}">
                        <span class="sidebar-icon"><i class="fas fa-file-upload"></i></span>
                        Upload Documents
                    </a>
                </li>
            </ul>
        </div>

        {{-- Main Content Area --}}
        <div class="booking-history-page">
            <h1 class="profile-title">My Booking History</h1>

            <!-- Active/Ongoing Bookings Section -->
            <div class="booking-section">
                <div class="section-title active">Active Bookings</div>
                
                @forelse($active as $booking)
                    @php
                        $vehicle = $booking->vehicle ?? null;
                        $canPayBalance = ($booking->pay_amount_type == 'deposit' || (isset($booking->depositAmount) && $booking->depositAmount > 0)) 
                                        && $booking->remainingBalance > 0 
                                        && $booking->bookingStatus === 'approved';
                        $routeExists = Route::has('payment.remaining');
                    @endphp
                    
                    <div class="booking-card">
                        @if($vehicle && $vehicle->vehiclePhoto)
                            <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" 
                                 alt="{{ $vehicle->model }}"
                                 class="car-image"
                                 onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
                        @else
                            <img src="{{ asset('img/vehicles/default.jpg') }}" 
                                 alt="Vehicle"
                                 class="car-image">
                        @endif

                        <div class="booking-details">
                            <div class="car-info">
                                <h3>{{ $vehicle->model ?? 'Car Model' }}</h3>
                                <p>{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</p>
                                
                                <!-- Payment Information -->
                                <div class="payment-info">
                                    @if(($booking->pay_amount_type == 'deposit' || (isset($booking->depositAmount) && $booking->depositAmount > 0)) && $booking->remainingBalance > 0)
                                        <div class="paid">Paid: RM{{ number_format($booking->totalPaid, 2) }}</div>
                                        <div class="remaining">Balance: RM{{ number_format($booking->remainingBalance, 2) }}</div>
                                    @elseif($booking->isFullyPaid)
                                        <div class="paid">Fully Paid</div>
                                    @endif
                                </div>
                            </div>

                            <div class="price">
                                RM{{ number_format($booking->totalPrice ?? 0, 2) }}
                            </div>

                            <div class="dates">
                                {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                {{ date('d M Y', strtotime($booking->endDate)) }}
                                
                                @if($booking->bookingStatus === 'approved')
                                    <div class="action-buttons">
                                        {{-- Pickup Button --}}
                                        <a href="{{ route('pickup.form', ['bookingID' => $booking->bookingID]) }}" 
                                           class="btn btn-primary btn-sm"
                                           title="Process vehicle pickup">
                                            <i class="fas fa-car"></i> Pickup
                                        </a>
                                        
                                        {{-- Return Button (show only if pickup likely completed) --}}
                                        @if(strtotime($booking->startDate) <= time())
                                            <a href="{{ route('return.form', ['bookingID' => $booking->bookingID]) }}" 
                                               class="btn btn-warning btn-sm"
                                               title="Process vehicle return">
                                                <i class="fas fa-undo"></i> Return
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="status active">Active</div>

                            <div class="action-buttons-container">
                                <button class="action-btn" 
                                        onclick="showDetailsModal({{ $booking->bookingID }})">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                                
                                {{-- Pay Remaining Balance Button --}}
                                @if($canPayBalance)
                                    @if($routeExists)
                                        <a href="{{ route('payment.remaining', $booking->bookingID) }}" 
                                           class="action-btn-success">
                                            <i class="fas fa-credit-card"></i> Pay Balance (RM{{ number_format($booking->remainingBalance, 2) }})
                                        </a>
                                    @else
                                        <button class="action-btn-success" 
                                                onclick="alert('Payment feature coming soon.\\n\\nPlease contact admin to pay remaining balance:\\nRM{{ number_format($booking->remainingBalance, 2) }}');">
                                            <i class="fas fa-credit-card"></i> Pay Balance (RM{{ number_format($booking->remainingBalance, 2) }})
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-check"></i><br>
                        No active bookings currently.
                    </div>
                @endforelse
            </div>

            <!-- Pending Bookings Section -->
            <div class="booking-section">
                <div class="section-title pending">Pending Bookings</div>
                
                @forelse($pending as $booking)
                    @php
                        $vehicle = $booking->vehicle ?? null;
                    @endphp
                    <div class="booking-card">
                        @if($vehicle && $vehicle->vehiclePhoto)
                            <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" 
                                 alt="{{ $vehicle->model }}"
                                 class="car-image"
                                 onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
                        @else
                            <img src="{{ asset('img/vehicles/default.jpg') }}" 
                                 alt="Vehicle"
                                 class="car-image">
                        @endif

                        <div class="booking-details">
                            <div class="car-info">
                                <h3>{{ $vehicle->model ?? 'Car Model' }}</h3>
                                <p>{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</p>
                            </div>

                            <div class="price">RM{{ number_format($booking->totalPrice ?? 0, 2) }}</div>

                            <div class="dates">
                                {{ date('d M Y', strtotime($booking->startDate)) }} -
                                {{ date('d M Y', strtotime($booking->endDate)) }}
                            </div>

                            <div class="status pending">Pending</div>

                            <div class="action-buttons-container">
                                <button class="action-btn-secondary" 
                                        onclick="showDetailsModal({{ $booking->bookingID }})">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                                <button class="action-btn-danger" 
                                        onclick="showCancelModal({{ $booking->bookingID }})">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-clock"></i><br>
                        No pending bookings.
                    </div>
                @endforelse
            </div>

            <!-- Past Bookings Section -->
            <div class="booking-section">
                <div class="section-title past">Past Bookings</div>
                
                <div class="past-bookings-grid">
                    <!-- Completed Bookings Column -->
                    <div class="past-bookings-section">
                        <h4><i class="fas fa-check-circle"></i> Completed</h4>
                        @forelse($completed as $booking)
                            @php
                                $vehicle = $booking->vehicle ?? null;
                            @endphp
                            <div class="booking-card past-booking">
                                <div class="past-booking-content">
                                    @if($vehicle && $vehicle->vehiclePhoto)
                                        <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" 
                                             alt="{{ $vehicle->model }}"
                                             class="past-car-image"
                                             onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
                                    @else
                                        <img src="{{ asset('img/vehicles/default.jpg') }}" 
                                             alt="Vehicle"
                                             class="past-car-image">
                                    @endif
                                    
                                    <div class="past-booking-info">
                                        <div class="past-booking-title">{{ $vehicle->model ?? 'Car Model' }}</div>
                                        <div class="past-booking-type">{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</div>
                                        <div class="past-booking-dates">
                                            {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                            {{ date('d M Y', strtotime($booking->endDate)) }}
                                        </div>
                                    </div>
                                    
                                    <button class="action-btn view-btn" 
                                            onclick="showDetailsModal({{ $booking->bookingID }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="empty-past-state">
                                <i class="fas fa-check-circle"></i><br>
                                No completed bookings
                            </div>
                        @endforelse
                    </div>

                    <!-- Cancelled Bookings Column -->
                    <div class="past-bookings-section">
                        <h4><i class="fas fa-times-circle"></i> Cancelled</h4>
                        @forelse($cancelled as $booking)
                            @php
                                $vehicle = $booking->vehicles ?? null;
                            @endphp
                            <div class="booking-card past-booking">
                                <div class="past-booking-content">
                                    @if($vehicle && $vehicles->vehiclePhoto)
                                        <img src="{{ Storage::url($vehicles->vehiclePhoto) }}" 
                                             alt="{{ $vehicles->model }}"
                                             class="past-car-image"
                                             onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
                                    @else
                                        <img src="{{ asset('img/vehicles/default.jpg') }}" 
                                             alt="Vehicle"
                                             class="past-car-image">
                                    @endif
                                    
                                    <div class="past-booking-info">
                                        <div class="past-booking-title">{{ $vehicles->model ?? 'Car Model' }}</div>
                                        <div class="past-booking-type">{{ $vehicles->vehicleType ?? 'Vehicle Type' }}</div>
                                        <div class="past-booking-dates">
                                            {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                            {{ date('d M Y', strtotime($booking->endDate)) }}
                                        </div>
                                    </div>
                                    
                                    <div class="past-booking-actions">
                                        <button class="action-btn view-btn" 
                                                onclick="showDetailsModal({{ $booking->bookingID }})">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        @if($vehicle)
                                            <a href="{{ route('customer.booking.form', ['vehicleId' => $vehicle->vehicleID]) }}" 
                                               class="action-btn-link rebook-btn">
                                                <i class="fas fa-redo"></i> Rebook
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-past-state">
                                <i class="fas fa-times-circle"></i><br>
                                No cancelled bookings
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-info-circle"></i> Booking Details</h2>
                <button class="close-btn" onclick="closeModal('detailsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div id="detail-image-container">
                    <img id="detail-car-image" src="" alt="Vehicle" class="car-detail-image"
                         onerror="this.onerror=null; this.src='{{ asset('img/vehicles/default.jpg') }}'">
                </div>
                
                <div class="detail-section">
                    <h3><i class="fas fa-car"></i> Vehicle Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Model</span>
                        <span class="detail-value" id="detail-model">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type</span>
                        <span class="detail-value" id="detail-type">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Plate Number</span>
                        <span class="detail-value" id="detail-plate">-</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-calendar-alt"></i> Booking Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Booking ID</span>
                        <span class="detail-value" id="detail-id">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Start Date</span>
                        <span class="detail-value" id="detail-start">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">End Date</span>
                        <span class="detail-value" id="detail-end">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value" id="detail-status">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value" id="detail-duration">-</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Total Cost</span>
                        <span class="detail-value" id="detail-total-cost">RM 0.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount Paid</span>
                        <span class="detail-value paid-amount" id="detail-paid">RM 0.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Remaining Balance</span>
                        <span class="detail-value remaining-balance" id="detail-remaining">RM 0.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Type</span>
                        <span class="detail-value" id="detail-payment-type">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Deposit Amount</span>
                        <span class="detail-value" id="detail-deposit">RM 0.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bank Name</span>
                        <span class="detail-value" id="detail-bank-name">-</span>
                    </div>
                    
                    <div class="payment-actions">
                        <a href="#" id="modal-pay-balance-btn" 
                           class="btn btn-success" 
                           style="display: none;">
                            <i class="fas fa-credit-card me-2"></i> Pay Remaining Balance
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('detailsModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-times-circle"></i> Cancel Booking</h2>
                <button class="close-btn" onclick="closeModal('cancelModal')">&times;</button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="cancelBookingId" name="booking_id">
                    
                    <div class="info-message">
                        <strong><i class="fas fa-info-circle"></i> Current Booking Details:</strong>
                        <div id="cancel-booking-details">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="warning-message">
                        <strong><i class="fas fa-exclamation-triangle"></i> Cancellation Policy:</strong><br>
                        • Refund only for cancellation made in 24 hours before pickup<br>
                        • No refund if cancelled less than 24 hours before pickup<br>
                        • Refund will make in 2 weeks of working days
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('cancelModal')">Close</button>
                    <button type="button" class="btn btn-danger" onclick="submitCancelForm()">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Merge all booking collections safely
    const bookingsData = [
        ...@json($active ?? []),
        ...@json($pending ?? []),
        ...@json($completed ?? []),
        ...@json($cancelled ?? [])
    ];

    function findBookingById(id) {
        return bookingsData.find(b => b.bookingID == id);
    }

    function showDetailsModal(bookingId) {
        const booking = findBookingById(bookingId);
        if (!booking) {
            alert('Booking not found');
            return;
        }

        // Set vehicle image dynamically
        const carImage = document.getElementById('detail-car-image');
        const vehicle = booking.vehicle || {};
        if (vehicle.vehiclePhoto) {
            carImage.src = "{{ Storage::url('') }}" + vehicle.vehiclePhoto;
        } else {
            carImage.src = "{{ asset('img/vehicles/default.jpg') }}";
        }

        // Set other booking details
        document.getElementById('detail-model').textContent = vehicle.model ?? '-';
        document.getElementById('detail-type').textContent = vehicle.vehicleType ?? '-';
        document.getElementById('detail-plate').textContent = vehicle.plateNumber ?? '-';
        document.getElementById('detail-id').textContent = booking.bookingID ?? '-';
        document.getElementById('detail-start').textContent = formatDate(booking.startDate);
        document.getElementById('detail-end').textContent = formatDate(booking.endDate);
        document.getElementById('detail-status').textContent = booking.bookingStatus ?? '-';
        document.getElementById('detail-duration').textContent =
            booking.bookingDuration ? booking.bookingDuration + ' days' : 'N/A';

        // Set payment details using the calculated fields from controller
        const totalCost = booking.totalCost || (booking.totalPrice + 50); // Rental + deposit
        const totalPaid = booking.totalPaid || 0;
        const remainingBalance = booking.remainingBalance || Math.max(0, totalCost - totalPaid);
        const depositAmount = booking.depositAmount || 0;
        
        document.getElementById('detail-total-cost').textContent = 'RM' + totalCost.toFixed(2);
        document.getElementById('detail-paid').textContent = 'RM' + totalPaid.toFixed(2);
        document.getElementById('detail-remaining').textContent = 'RM' + remainingBalance.toFixed(2);
        
        // Set payment type - using pay_amount_type column
        const paymentType = booking.pay_amount_type === 'deposit' ? 'Deposit Only' : 
                           booking.pay_amount_type === 'full' ? 'Full Payment' : '-';
        document.getElementById('detail-payment-type').textContent = paymentType;
        
        document.getElementById('detail-deposit').textContent = 'RM' + depositAmount.toFixed(2);
        document.getElementById('detail-bank-name').textContent = booking.penamaBank ?? '-';

        // Show/Hide Pay Balance button
        const payBalanceBtn = document.getElementById('modal-pay-balance-btn');
        const canPayBalance = (booking.pay_amount_type === 'deposit' || (booking.depositAmount && booking.depositAmount > 0)) 
                              && remainingBalance > 0 
                              && booking.bookingStatus === 'approved';
        
        if (canPayBalance) {
            payBalanceBtn.style.display = 'inline-block';
            payBalanceBtn.onclick = function(e) {
                e.preventDefault();
                @if(Route::has('payment.remaining'))
                    window.location.href = "{{ route('payment.remaining', ['bookingID' => $booking->bookingID]) }}";
                @else
                    alert('Payment feature coming soon.\n\nPlease contact admin to pay remaining balance:\nRM' + remainingBalance.toFixed(2));
                @endif
            };
        } else {
            payBalanceBtn.style.display = 'none';
        }

        // Store booking ID for receipt download
        document.getElementById('cancelBookingId').value = booking.bookingID;
        document.getElementById('detailsModal').classList.add('active');
    }

    function showCancelModal(bookingId) {
        const booking = findBookingById(bookingId);
        if (!booking) {
            alert('Booking not found');
            return;
        }

        const vehicle = booking.vehicle || {};

        // Set form action dynamically
        const cancelForm = document.getElementById('cancelForm');
        cancelForm.action = `/customer/booking/${bookingId}/cancel`;
        
        // Set booking ID in form
        document.getElementById('cancelBookingId').value = bookingId;
        
        // Populate booking details
        document.getElementById('cancel-booking-details').innerHTML = `
            <strong>Vehicle:</strong> ${vehicle.model ?? '-'}<br>
            <strong>Dates:</strong> ${formatDate(booking.startDate)} - ${formatDate(booking.endDate)}<br>
            <strong>Total:</strong> RM${Number(booking.totalPrice ?? 0).toFixed(2)}<br>
            <strong>Status:</strong> ${booking.bookingStatus ?? 'Pending'}
        `;
        
        // Show modal
        document.getElementById('cancelModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function submitCancelForm() {
        const bookingId = document.getElementById('cancelBookingId').value;
        
        if (!confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
            return;
        }

        // Show loading state
        const cancelBtn = document.querySelector('#cancelModal .btn-danger');
        const originalText = cancelBtn.innerHTML;
        cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        cancelBtn.disabled = true;

        // Submit the form
        document.getElementById('cancelForm').submit();
    }

    function formatDate(date) {
        if (!date) return '-';
        try {
            return new Date(date).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        } catch (e) {
            return date;
        }
    }

    window.onclick = function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('active');
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Bookings data loaded:', bookingsData.length, 'bookings');
    });
    </script>

</body>
</html>
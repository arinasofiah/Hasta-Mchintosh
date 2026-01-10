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
    <link href="{{ asset('css/booking-history.css') }}" rel="stylesheet">
    
<<<<<<< Updated upstream
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
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
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
            padding: 8px 0;
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

        .action-buttons-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .past-booking {
            margin-bottom: 10px;
            padding: 15px;
        }

        .past-booking-content {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 15px;
        }

        .past-car-image {
            width: 80px;
            height: 60px;
            border-radius: 4px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .past-booking-info {
            flex: 1;
        }

        .past-booking-title {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        .past-booking-type {
            font-size: 12px;
            color: #666;
        }

        .past-booking-dates {
            font-size: 12px;
            color: #666;
        }

        .past-booking-actions {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .view-btn {
            padding: 5px 10px !important;
            font-size: 12px !important;
        }

        .rebook-btn {
            padding: 5px 10px !important;
            font-size: 12px !important;
        }

        .empty-past-state {
            text-align: center;
            color: #666;
            font-size: 14px;
            padding: 20px;
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
=======
>>>>>>> Stashed changes
</head>
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
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookingHistory') }}" class="active">
                        My Bookings
                    </a>
                </li>
               
               <li>
                    <a href="{{ route('customer.documents') }}">
                        Upload Documents
                    </a>
                </li>
            </ul>
        </div>

        {{-- Main Content Area --}}
        <div class="booking-history-page">
            <h1 class="profile-title">My Booking History</h1>

            <!-- Debug Section -->
            <div style="background: #f0f0f0; padding: 10px; margin-bottom: 20px; border-radius: 4px; font-size: 12px; display: none;">
                <strong>Debug Info:</strong><br>
                
                @php
                    $testBooking = $active->first() ?? $pending->first() ?? $completed->first() ?? $cancelled->first();
                @endphp
                
                @if($testBooking)
                    First Booking ID: {{ $testBooking->bookingID }}<br>
                    Vehicle Model: {{ $testBooking->vehicle->model ?? 'NO VEHICLE MODEL' }}<br>
                    Vehicle Type: {{ $testBooking->vehicle->vehicleType ?? 'NO VEHICLE TYPE' }}<br>
                    Vehicle Photo: {{ $testBooking->vehicle->vehiclePhoto ?? 'NO PHOTO' }}<br>
                    <br>
                    Counts: Active={{ count($active) }}, Pending={{ count($pending) }}, 
                    Completed={{ count($completed) }}, Cancelled={{ count($cancelled) }}
                @else
                    No bookings found in any category
                @endif
            </div>

            <!-- Active/Ongoing Bookings Section -->
            <div class="booking-section">
                <div class="section-title active">Active Bookings</div>
                <div class="booking-cards-container" id="activeBookingsContainer">
                    @forelse($active as $booking)
                        @php
                            $vehicle = $booking->vehicle ?? null;
                            // Calculate like payment form
                            $rentalPrice = $booking->totalPrice ?? 0;
                            $depositAmount = 50; // Fixed deposit
                            $totalCost = $rentalPrice + $depositAmount;
                            $totalPaid = $booking->totalPaid ?? 0;
                            
                            // Calculate remaining balance based on payment type
                            if($booking->pay_amount_type == 'deposit') {
                                $remainingBalance = max(0, $rentalPrice - ($totalPaid - $depositAmount));
                            } else {
                                $remainingBalance = max(0, $totalCost - $totalPaid);
                            }
                            
                            $isFullyPaid = $remainingBalance <= 0;
                            $canPayBalance = $remainingBalance > 0 && $booking->bookingStatus === 'approved';
                        @endphp
                        
                        <div class="booking-card">
                            <div class="booking-details">
                                <div class="car-info">
                                    <h3>{{ $vehicle->model ?? 'Car Model' }}</h3>
                                    <p>{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</p>
                                    
                                    <!-- Payment Information -->
                                    <div class="payment-info">
                                        @if($remainingBalance > 0)
                                            <div class="paid">Paid: RM{{ number_format($totalPaid, 2) }}</div>
                                            <div class="remaining">Balance: RM{{ number_format($remainingBalance, 2) }}</div>
                                        @elseif($isFullyPaid)
                                            <div class="paid">Fully Paid</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="price">
                                    RM{{ number_format($rentalPrice, 2) }}
                                </div>

                                <div class="dates">
                                    {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                    {{ date('d M Y', strtotime($booking->endDate)) }}
                                    
                                    @if($booking->bookingStatus === 'approved')
                                        <div class="action-buttons" style="margin-top: 10px;">
                                            {{-- Pickup Button --}}
                                            <a href="{{ route('pickup.form', ['bookingID' => $booking->bookingID]) }}" 
                                               class="btn btn-primary btn-sm pickup-btn"
                                               title="Process vehicle pickup">
                                                <i class="fas fa-car"></i> Pickup
                                            </a>
                                            
                                            {{-- Return Button (show only if pickup likely completed) --}}
                                            @if(strtotime($booking->startDate) <= time())
                                                <a href="{{ route('return.form', ['bookingID' => $booking->bookingID]) }}" 
                                                   class="btn btn-warning btn-sm return-btn"
                                                   title="Process vehicle return">
                                                    <i class="fas fa-undo"></i> Return
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="status active">Active</div>

                                <div class="action-buttons-main">
                                    <button class="action-btn" 
                                            onclick="showDetailsModal({{ $booking->bookingID }})">
                                        <i class="fas fa-eye"></i> Details
                                    </button>
                                    
                                    @if($canPayBalance)
                                        <a href="{{ route('payment.remaining', ['bookingID' => $booking->bookingID]) }}" 
                                           class="action-btn-success" 
                                           style="text-decoration: none;">
                                            <i class="fas fa-credit-card"></i> Pay Balance (RM{{ number_format($remainingBalance, 2) }})
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-check" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                            No active bookings currently.
                        </div>
                    @endforelse
                </div>
                
                @if(count($active) > 3)
                <div class="show-more-container">
                    <button class="show-more-btn" onclick="toggleShowAll('activeBookingsContainer', this)">
                        <i class="fas fa-chevron-down"></i>
                        Show All ({{ count($active) }})
                    </button>
                </div>
                @endif
            </div>

            <!-- Pending Bookings Section -->
            <div class="booking-section">
                <div class="section-title pending">Pending Bookings</div>
                <div class="booking-cards-container" id="pendingBookingsContainer">
                    @forelse($pending as $booking)
                        @php
                            $vehicle = $booking->vehicle ?? null;
                            $rentalPrice = $booking->totalPrice ?? 0;
                            $depositAmount = 50;
                            $totalCost = $rentalPrice + $depositAmount;
                            $totalPaid = $booking->totalPaid ?? 0;
                            
                            if($booking->pay_amount_type == 'deposit') {
                                $remainingBalance = max(0, $rentalPrice - ($totalPaid - $depositAmount));
                            } else {
                                $remainingBalance = max(0, $totalCost - $totalPaid);
                            }
                        @endphp
                        <div class="booking-card">
                            <div class="booking-details">
                                <div class="car-info">
                                    <h3>{{ $vehicle->model ?? 'Car Model' }}</h3>
                                    <p>{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</p>
                                </div>

                                <div class="price">RM{{ number_format($rentalPrice, 2) }}</div>

                                <div class="dates">
                                    {{ date('d M Y', strtotime($booking->startDate)) }} -
                                    {{ date('d M Y', strtotime($booking->endDate)) }}
                                </div>

                                <div class="status pending">Pending</div>

                                <div class="action-buttons-main">
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
                            <i class="fas fa-clock" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                            No pending bookings.
                        </div>
                    @endforelse
                </div>
                
                @if(count($pending) > 3)
                <div class="show-more-container">
                    <button class="show-more-btn" onclick="toggleShowAll('pendingBookingsContainer', this)">
                        <i class="fas fa-chevron-down"></i>
                        Show All ({{ count($pending) }})
                    </button>
                </div>
                @endif
            </div>

            <!-- Past Bookings Section -->
            <div class="booking-section">
                <div class="section-title past">Past Bookings</div>
                
                <div class="past-bookings-grid">
                    <!-- Completed Bookings Column -->
                    <div class="past-bookings-section">
                        <h4><i class="fas fa-check-circle"></i> Completed</h4>
                        <div class="past-bookings-scroll-container" id="completedScroll">
                            @forelse($completed as $booking)
                                @php
                                    $vehicle = $booking->vehicle ?? null;
                                    $rentalPrice = $booking->totalPrice ?? 0;
                                @endphp
                                <div class="past-booking-item">
                                    <div class="past-booking-content">
                                        <div class="past-booking-info">
                                            <div class="past-booking-title">{{ $vehicle->model ?? 'Car Model' }}</div>
                                            <div class="past-booking-type">{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</div>
                                            <div class="past-booking-dates">
                                                {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                                {{ date('d M Y', strtotime($booking->endDate)) }}
                                            </div>
                                        </div>
                                        <div class="past-booking-actions">
                                            <button class="view-btn action-btn" 
                                                    onclick="showDetailsModal({{ $booking->bookingID }})">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-past-state">
                                    <i class="fas fa-check-circle"></i>
                                    No completed bookings
                                </div>
                            @endforelse
                        </div>
                       
                    </div>

                    <!-- Cancelled Bookings Column -->
                    <div class="past-bookings-section">
                        <h4><i class="fas fa-times-circle"></i> Cancelled</h4>
                        <div class="past-bookings-scroll-container" id="cancelledScroll">
                            @forelse($cancelled as $booking)
                                @php
                                    $vehicle = $booking->vehicle ?? null;
                                    $rentalPrice = $booking->totalPrice ?? 0;
                                @endphp
                                <div class="past-booking-item">
                                    <div class="past-booking-content">
                                        <div class="past-booking-info">
                                            <div class="past-booking-title">{{ $vehicle->model ?? 'Car Model' }}</div>
                                            <div class="past-booking-type">{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</div>
                                            <div class="past-booking-dates">
                                                {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                                {{ date('d M Y', strtotime($booking->endDate)) }}
                                            </div>
                                        </div>
                                        <div class="past-booking-actions">
                                            <button class="view-btn action-btn" 
                                                    onclick="showDetailsModal({{ $booking->bookingID }})"
                                                    style="margin-bottom: 5px;">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            @if($booking->vehicleID)
                                                <a href="{{ route('customer.booking.form', ['vehicleId' => $booking->vehicleID]) }}" 
                                                   class="rebook-btn action-btn-link">
                                                    <i class="fas fa-redo"></i> Rebook
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-past-state">
                                    <i class="fas fa-times-circle"></i>
                                    No cancelled bookings
                                </div>
                            @endforelse
                        </div>
                       
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
                        <span class="detail-label">Pick Up Date</span>
                        <span class="detail-value" id="detail-start">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Return Date</span>
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

                <!-- Total Vehicle Cost -->
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

<<<<<<< Updated upstream
   <script>
// Collect all booking data with proper vehicle information
let bookingsData = [];
=======
    <script>
        // booking-history.js
>>>>>>> Stashed changes

// Collect all booking data with proper vehicle information
let bookingsData = [];

<<<<<<< Updated upstream
// Add pending bookings
@if(isset($pending) && $pending->isNotEmpty())
    @foreach($pending as $booking)
=======
// Add active bookings
@if(isset($active) && $active->isNotEmpty())
    @foreach($active as $booking)
>>>>>>> Stashed changes
        @php
            $vehicleData = [
                'model' => $booking->vehicle->model ?? null,
                'vehicleType' => $booking->vehicle->vehicleType ?? null,
                'vehiclePhoto' => $booking->vehicle->vehiclePhoto ?? null,
                'plateNumber' => $booking->vehicle->plateNumber ?? null,
            ];
<<<<<<< Updated upstream
        @endphp
        bookingsData.push({
            bookingID: {{ $booking->bookingID }},
            startDate: '{{ $booking->startDate }}',
            endDate: '{{ $booking->endDate }}',
            bookingStatus: '{{ $booking->bookingStatus }}',
            totalPrice: {{ $booking->totalPrice ?? 0 }},
            totalPaid: {{ $booking->totalPaid ?? 0 }},
            totalCost: {{ $booking->totalCost ?? ($booking->totalPrice + 50) }},
            remainingBalance: {{ $booking->remainingBalance ?? 0 }},
            depositAmount: {{ $booking->depositAmount ?? 0 }},
            pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
            penamaBank: '{{ $booking->penamaBank ?? '' }}',
            bookingDuration: {{ $booking->bookingDuration ?? 0 }},
            vehicle: {!! json_encode($vehicleData) !!}
        });
    @endforeach
@endif

// Add completed bookings
@if(isset($completed) && $completed->isNotEmpty())
    @foreach($completed as $booking)
        @php
            $vehicleData = [
                'model' => $booking->vehicle->model ?? null,
                'vehicleType' => $booking->vehicle->vehicleType ?? null,
                'vehiclePhoto' => $booking->vehicle->vehiclePhoto ?? null,
                'plateNumber' => $booking->vehicle->plateNumber ?? null,
            ];
        @endphp
        bookingsData.push({
            bookingID: {{ $booking->bookingID }},
            startDate: '{{ $booking->startDate }}',
            endDate: '{{ $booking->endDate }}',
            bookingStatus: '{{ $booking->bookingStatus }}',
            totalPrice: {{ $booking->totalPrice ?? 0 }},
            totalPaid: {{ $booking->totalPaid ?? 0 }},
            totalCost: {{ $booking->totalCost ?? ($booking->totalPrice + 50) }},
            remainingBalance: {{ $booking->remainingBalance ?? 0 }},
            depositAmount: {{ $booking->depositAmount ?? 0 }},
            pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
            penamaBank: '{{ $booking->penamaBank ?? '' }}',
            bookingDuration: {{ $booking->bookingDuration ?? 0 }},
            vehicle: {!! json_encode($vehicleData) !!}
        });
    @endforeach
@endif

    // Add cancelled bookings
    @if(isset($cancelled) && $cancelled->isNotEmpty())
        @foreach($cancelled as $booking)
            @php
                $vehicleData = [
                    'model' => $booking->vehicle->model ?? null,
                    'vehicleType' => $booking->vehicle->vehicleType ?? null,
                    'vehiclePhoto' => $booking->vehicle->vehiclePhoto ?? null,
                    'plateNumber' => $booking->vehicle->plateNumber ?? null,
                ];
                
                // Calculate like payment form
                $rentalPrice = $booking->totalPrice ?? 0;
                $depositAmount = 50;
                $totalCost = $rentalPrice + $depositAmount;
                $totalPaid = $booking->totalPaid ?? 0;
                
                if($booking->pay_amount_type == 'deposit') {
                    $remainingBalance = max(0, $rentalPrice - ($totalPaid - $depositAmount));
                } else {
                    $remainingBalance = max(0, $totalCost - $totalPaid);
                }
            @endphp
            bookingsData.push({
                bookingID: {{ $booking->bookingID }},
                startDate: '{{ $booking->startDate }}',
                endDate: '{{ $booking->endDate }}',
                bookingStatus: '{{ $booking->bookingStatus }}',
                totalPrice: {{ $rentalPrice }},
                totalPaid: {{ $totalPaid }},
                totalCost: {{ $totalCost }},
                remainingBalance: {{ $remainingBalance }},
                depositAmount: {{ $depositAmount }},
                pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
                penamaBank: '{{ $booking->penamaBank ?? '' }}',
                bookingDuration: {{ $booking->bookingDuration ?? 0 }},
                vehicle: {!! json_encode($vehicleData) !!}
            });
        @endforeach
    @endif

    console.log('Bookings data loaded:', bookingsData);

    function findBookingById(id) {
        return bookingsData.find(b => b.bookingID == id);
    }

    function showDetailsModal(bookingId) {
        console.log('showDetailsModal called with bookingId:', bookingId);
        
        const booking = findBookingById(bookingId);
        if (!booking) {
            alert('Booking not found with ID: ' + bookingId);
            console.error('Booking not found. Available IDs:', bookingsData.map(b => b.bookingID));
            return;
        }

        console.log('Booking data for modal:', booking);

        // Set vehicle image dynamically
        const carImage = document.getElementById('detail-car-image');
        if (booking.vehicle && booking.vehicle.vehiclePhoto) {
            carImage.src = "{{ Storage::url('') }}" + booking.vehicle.vehiclePhoto;
        } else {
            carImage.src = "{{ asset('img/vehicles/default.jpg') }}";
        }

    // Set other booking details
    document.getElementById('detail-model').textContent = booking.vehicle?.model || '-';
    document.getElementById('detail-type').textContent = booking.vehicle?.vehicleType || '-';
    document.getElementById('detail-plate').textContent = booking.vehicle?.plateNumber || '-';
    document.getElementById('detail-id').textContent = booking.bookingID || '-';
    document.getElementById('detail-start').textContent = formatDate(booking.startDate);
    document.getElementById('detail-end').textContent = formatDate(booking.endDate);
    document.getElementById('detail-status').textContent = booking.bookingStatus || '-';
    document.getElementById('detail-duration').textContent = 
        booking.bookingDuration ? booking.bookingDuration + ' days' : 'N/A';

    // Set payment details
    const totalCost = booking.totalCost || (booking.totalPrice + 50);
    const totalPaid = booking.totalPaid || 0;
    const remainingBalance = booking.remainingBalance || Math.max(0, totalCost - totalPaid);
    const depositAmount = booking.depositAmount || 0;
    
    document.getElementById('detail-total-cost').textContent = 'RM' + totalCost.toFixed(2);
    document.getElementById('detail-paid').textContent = 'RM' + totalPaid.toFixed(2);
    document.getElementById('detail-remaining').textContent = 'RM' + remainingBalance.toFixed(2);
    
    // Set payment type
    const paymentType = booking.pay_amount_type === 'deposit' ? 'Deposit Only' : 
                       booking.pay_amount_type === 'full' ? 'Full Payment' : '-';
    document.getElementById('detail-payment-type').textContent = paymentType;
    
    document.getElementById('detail-deposit').textContent = 'RM' + depositAmount.toFixed(2);
    document.getElementById('detail-bank-name').textContent = booking.penamaBank || '-';

        // Show/Hide Pay Balance button
        const payBalanceBtn = document.getElementById('modal-pay-balance-btn');
        const canPayBalance = booking.remainingBalance > 0 && booking.bookingStatus === 'approved';
        
        if (canPayBalance) {
            payBalanceBtn.style.display = 'inline-block';
            payBalanceBtn.href = "/payment/remaining/" + booking.bookingID;
        } else {
            payBalanceBtn.style.display = 'none';
        }

        document.getElementById('detailsModal').classList.add('active');
    }

    function showCancelModal(bookingId) {
        console.log('showCancelModal called with bookingId:', bookingId);
        
        const booking = findBookingById(bookingId);
        if (!booking) {
            alert('Booking not found');
            return;
        }

        // Set form action dynamically
        const cancelForm = document.getElementById('cancelForm');
        cancelForm.action = `/customer/booking/${bookingId}/cancel`;
        
        // Set booking ID in form
        document.getElementById('cancelBookingId').value = bookingId;
        
        // Populate booking details
        document.getElementById('cancel-booking-details').innerHTML = `
            <strong>Vehicle:</strong> ${booking.vehicle?.model || '-'}<br>
            <strong>Dates:</strong> ${formatDate(booking.startDate)} - ${formatDate(booking.endDate)}<br>
            <strong>Total:</strong> RM${(booking.totalPrice || 0).toFixed(2)}<br>
            <strong>Status:</strong> ${booking.bookingStatus || 'Pending'}
        `;
        
        // Show modal
        document.getElementById('cancelModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function submitCancelForm() {
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

    // Add click handlers directly to buttons
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, adding click handlers');
        
        // Add click handlers for all Details buttons
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.includes('Details') || button.textContent.includes('View')) {
                const onclick = button.getAttribute('onclick');
                if (onclick && onclick.includes('showDetailsModal')) {
                    const match = onclick.match(/showDetailsModal\((\d+)\)/);
                    if (match) {
                        const bookingId = match[1];
                        button.onclick = function(e) {
                            e.preventDefault();
                            showDetailsModal(bookingId);
                        };
                    }
                }
=======
            
            // Get bank name - check multiple fields
            $bankName = 'Not Available';
            if (!empty($booking->bank_name)) {
                $bankName = $booking->bank_name;
            } elseif (!empty($booking->penamaBank)) {
                $bankName = $booking->penamaBank;
>>>>>>> Stashed changes
            }
            
            // Get bank owner name
            $bankOwner = $booking->bank_owner_name ?? 'Not Available';
            
            // Calculate duration from dates
            $duration = 1;
            if ($booking->startDate && $booking->endDate) {
                try {
                    $start = new DateTime($booking->startDate);
                    $end = new DateTime($booking->endDate);
                    $diff = $start->diff($end);
                    $duration = max(1, $diff->days);
                } catch (Exception $e) {
                    $duration = $booking->bookingDuration ?? 1;
                }
            } else {
                $duration = $booking->bookingDuration ?? 1;
            }
            
            // Calculate like payment form
            $rentalPrice = $booking->totalPrice ?? 0;
            $depositAmount = 50; // Fixed deposit
            $totalCost = $rentalPrice + $depositAmount;
            $totalPaid = $booking->totalPaid ?? 0;
            
            if(isset($booking->pay_amount_type) && $booking->pay_amount_type == 'deposit') {
                $remainingBalance = max(0, $rentalPrice - ($totalPaid - $depositAmount));
            } else {
                $remainingBalance = max(0, $totalCost - $totalPaid);
            }
        @endphp
        bookingsData.push({
            bookingID: {{ $booking->bookingID }},
            startDate: '{{ $booking->startDate }}',
            endDate: '{{ $booking->endDate }}',
            bookingStatus: '{{ $booking->bookingStatus }}',
            totalPrice: {{ $rentalPrice }},
            totalPaid: {{ $totalPaid }},
            totalCost: {{ $totalCost }},
            remainingBalance: {{ $remainingBalance }},
            depositAmount: {{ $depositAmount }},
            pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
            penamaBank: '{{ $booking->penamaBank ?? '' }}',
            bank_name: '{{ $booking->bank_name ?? '' }}',
            bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
            duration: {{ $duration }},
            bookingDuration: {{ $booking->bookingDuration ?? 0 }},
            vehicle: {!! json_encode($vehicleData) !!}
        });
    @endforeach
@endif

// Add pending bookings
@if(isset($pending) && $pending->isNotEmpty())
    @foreach($pending as $booking)
        @php
            $vehicleData = [
                'model' => $booking->vehicle->model ?? null,
                'vehicleType' => $booking->vehicle->vehicleType ?? null,
                'vehiclePhoto' => $booking->vehicle->vehiclePhoto ?? null,
                'plateNumber' => $booking->vehicle->plateNumber ?? null,
            ];
            
            // Get bank name - check multiple fields
            $bankName = 'Not Available';
            if (!empty($booking->bank_name)) {
                $bankName = $booking->bank_name;
            } elseif (!empty($booking->penamaBank)) {
                $bankName = $booking->penamaBank;
            }
            
            // Get bank owner name
            $bankOwner = $booking->bank_owner_name ?? 'Not Available';
            
            // Calculate duration from dates
            $duration = 1;
            if ($booking->startDate && $booking->endDate) {
                try {
                    $start = new DateTime($booking->startDate);
                    $end = new DateTime($booking->endDate);
                    $diff = $start->diff($end);
                    $duration = max(1, $diff->days);
                } catch (Exception $e) {
                    $duration = $booking->bookingDuration ?? 1;
                }
            } else {
                $duration = $booking->bookingDuration ?? 1;
            }
            
            // Calculate like payment form
            $rentalPrice = $booking->totalPrice ?? 0;
            $depositAmount = 50;
            $totalCost = $rentalPrice + $depositAmount;
            $totalPaid = $booking->totalPaid ?? 0;
            
            if(isset($booking->pay_amount_type) && $booking->pay_amount_type == 'deposit') {
                $remainingBalance = max(0, $rentalPrice - ($totalPaid - $depositAmount));
            } else {
                $remainingBalance = max(0, $totalCost - $totalPaid);
            }
        @endphp
        bookingsData.push({
            bookingID: {{ $booking->bookingID }},
            startDate: '{{ $booking->startDate }}',
            endDate: '{{ $booking->endDate }}',
            bookingStatus: '{{ $booking->bookingStatus }}',
            totalPrice: {{ $rentalPrice }},
            totalPaid: {{ $totalPaid }},
            totalCost: {{ $totalCost }},
            remainingBalance: {{ $remainingBalance }},
            depositAmount: {{ $depositAmount }},
            pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
            penamaBank: '{{ $booking->penamaBank ?? '' }}',
            bank_name: '{{ $booking->bank_name ?? '' }}',
            bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
            duration: {{ $duration }},
            bookingDuration: {{ $booking->bookingDuration ?? 0 }},
            vehicle: {!! json_encode($vehicleData) !!}
        });
    @endforeach
@endif

// Add completed bookings
@if(isset($completed) && $completed->isNotEmpty())
    @foreach($completed as $booking)
        @php
            $vehicleData = [
                'model' => $booking->vehicle->model ?? null,
                'vehicleType' => $booking->vehicle->vehicleType ?? null,
                'vehiclePhoto' => $booking->vehicle->vehiclePhoto ?? null,
                'plateNumber' => $booking->vehicle->plateNumber ?? null,
            ];
            
            // Get bank name - check multiple fields
            $bankName = 'Not Available';
            if (!empty($booking->bank_name)) {
                $bankName = $booking->bank_name;
            } elseif (!empty($booking->penamaBank)) {
                $bankName = $booking->penamaBank;
            }
            
            // Get bank owner name
            $bankOwner = $booking->bank_owner_name ?? 'Not Available';
            
            // Calculate duration from dates
            $duration = 1;
            if ($booking->startDate && $booking->endDate) {
                try {
                    $start = new DateTime($booking->startDate);
                    $end = new DateTime($booking->endDate);
                    $diff = $start->diff($end);
                    $duration = max(1, $diff->days);
                } catch (Exception $e) {
                    $duration = $booking->bookingDuration ?? 1;
                }
            } else {
                $duration = $booking->bookingDuration ?? 1;
            }
            
            // Calculate like payment form
            $rentalPrice = $booking->totalPrice ?? 0;
            $depositAmount = 50;
            $totalCost = $rentalPrice + $depositAmount;
            $totalPaid = $booking->totalPaid ?? 0;
            
            if(isset($booking->pay_amount_type) && $booking->pay_amount_type == 'deposit') {
                $remainingBalance = max(0, $rentalPrice - ($totalPaid - $depositAmount));
            } else {
                $remainingBalance = max(0, $totalCost - $totalPaid);
            }
        @endphp
        bookingsData.push({
            bookingID: {{ $booking->bookingID }},
            startDate: '{{ $booking->startDate }}',
            endDate: '{{ $booking->endDate }}',
            bookingStatus: '{{ $booking->bookingStatus }}',
            totalPrice: {{ $rentalPrice }},
            totalPaid: {{ $totalPaid }},
            totalCost: {{ $totalCost }},
            remainingBalance: {{ $remainingBalance }},
            depositAmount: {{ $depositAmount }},
            pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
            penamaBank: '{{ $booking->penamaBank ?? '' }}',
            bank_name: '{{ $booking->bank_name ?? '' }}',
            bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
            duration: {{ $duration }},
            bookingDuration: {{ $booking->bookingDuration ?? 0 }},
            vehicle: {!! json_encode($vehicleData) !!}
        });
    @endforeach
@endif

// Add cancelled bookings
@if(isset($cancelled) && $cancelled->isNotEmpty())
    @foreach($cancelled as $booking)
        @php
            $vehicleData = [
                'model' => $booking->vehicle->model ?? null,
                'vehicleType' => $booking->vehicle->vehicleType ?? null,
                'vehiclePhoto' => $booking->vehicle->vehiclePhoto ?? null,
                'plateNumber' => $booking->vehicle->plateNumber ?? null,
            ];
            
            // Get bank name - check multiple fields
            $bankName = 'Not Available';
            if (!empty($booking->bank_name)) {
                $bankName = $booking->bank_name;
            } elseif (!empty($booking->penamaBank)) {
                $bankName = $booking->penamaBank;
            }
            
            // Get bank owner name
            $bankOwner = $booking->bank_owner_name ?? 'Not Available';
            
            // Calculate duration from dates
            $duration = 1;
            if ($booking->startDate && $booking->endDate) {
                try {
                    $start = new DateTime($booking->startDate);
                    $end = new DateTime($booking->endDate);
                    $diff = $start->diff($end);
                    $duration = max(1, $diff->days);
                } catch (Exception $e) {
                    $duration = $booking->bookingDuration ?? 1;
                }
            } else {
                $duration = $booking->bookingDuration ?? 1;
            }
            
            // Calculate like payment form
            $rentalPrice = $booking->totalPrice ?? 0;
            $depositAmount = 50;
            $totalCost = $rentalPrice + $depositAmount;
            $totalPaid = $booking->totalPaid ?? 0;
            
            if(isset($booking->pay_amount_type) && $booking->pay_amount_type == 'deposit') {
                $remainingBalance = max(0, $rentalPrice - ($totalPaid - $depositAmount));
            } else {
                $remainingBalance = max(0, $totalCost - $totalPaid);
            }
        @endphp
        bookingsData.push({
            bookingID: {{ $booking->bookingID }},
            startDate: '{{ $booking->startDate }}',
            endDate: '{{ $booking->endDate }}',
            bookingStatus: '{{ $booking->bookingStatus }}',
            totalPrice: {{ $rentalPrice }},
            totalPaid: {{ $totalPaid }},
            totalCost: {{ $totalCost }},
            remainingBalance: {{ $remainingBalance }},
            depositAmount: {{ $depositAmount }},
            pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
            penamaBank: '{{ $booking->penamaBank ?? '' }}',
            bank_name: '{{ $booking->bank_name ?? '' }}',
            bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
            duration: {{ $duration }},
            bookingDuration: {{ $booking->bookingDuration ?? 0 }},
            vehicle: {!! json_encode($vehicleData) !!}
        });
    @endforeach
@endif

console.log('Bookings data loaded:', bookingsData);

function findBookingById(id) {
    return bookingsData.find(b => b.bookingID == id);
}

function showDetailsModal(bookingId) {
    console.log('showDetailsModal called with bookingId:', bookingId);
    
    const booking = findBookingById(bookingId);
    if (!booking) {
        alert('Booking not found with ID: ' + bookingId);
        console.error('Booking not found. Available IDs:', bookingsData.map(b => b.bookingID));
        return;
    }

    console.log('Booking data for modal:', booking);

    // Set vehicle image dynamically
    const carImage = document.getElementById('detail-car-image');
    if (booking.vehicle && booking.vehicle.vehiclePhoto) {
        carImage.src = "{{ Storage::url('') }}" + booking.vehicle.vehiclePhoto;
    } else {
        carImage.src = "{{ asset('img/vehicles/default.jpg') }}";
    }

    // Set vehicle details
    document.getElementById('detail-model').textContent = booking.vehicle?.model || '-';
    document.getElementById('detail-type').textContent = booking.vehicle?.vehicleType || '-';
    document.getElementById('detail-plate').textContent = booking.vehicle?.plateNumber || '-';
    document.getElementById('detail-id').textContent = booking.bookingID || '-';
    document.getElementById('detail-start').textContent = formatDate(booking.startDate);
    document.getElementById('detail-end').textContent = formatDate(booking.endDate);
    document.getElementById('detail-status').textContent = booking.bookingStatus || '-';
    
    // Use duration field (calculated from PHP)
    document.getElementById('detail-duration').textContent = 
        (booking.duration || 1) + ' day(s)';
    
    document.getElementById('detail-rental-price').textContent = 'RM' + (booking.totalPrice || 0).toFixed(2);

    // Set payment details - MATCHES PAYMENT FORM LOGIC
    document.getElementById('detail-total-cost').textContent = 'RM' + (booking.totalCost || 0).toFixed(2);
    document.getElementById('detail-paid').textContent = 'RM' + (booking.totalPaid || 0).toFixed(2);
    document.getElementById('detail-remaining').textContent = 'RM' + (booking.remainingBalance || 0).toFixed(2);
    
    // Set payment type
    const paymentType = booking.pay_amount_type === 'deposit' ? 'Deposit Only' : 
                       booking.pay_amount_type === 'full' ? 'Full Payment' : '-';
    document.getElementById('detail-payment-type').textContent = paymentType;
    
    // Get bank name from multiple possible fields
    let displayBankName = 'Not Available';
    if (booking.bank_name && booking.bank_name.trim() !== '') {
        displayBankName = booking.bank_name;
    } else if (booking.penamaBank && booking.penamaBank.trim() !== '') {
        displayBankName = booking.penamaBank;
    }
    document.getElementById('detail-bank-name').textContent = displayBankName;

    // Show/Hide Pay Balance button
    const payBalanceBtn = document.getElementById('modal-pay-balance-btn');
    const canPayBalance = booking.remainingBalance > 0 && booking.bookingStatus === 'approved';
    
    if (canPayBalance) {
        payBalanceBtn.style.display = 'inline-block';
        payBalanceBtn.href = "/payment/remaining/" + booking.bookingID;
    } else {
        payBalanceBtn.style.display = 'none';
    }

    document.getElementById('detailsModal').classList.add('active');
}

function showCancelModal(bookingId) {
    console.log('showCancelModal called with bookingId:', bookingId);
    
    const booking = findBookingById(bookingId);
    if (!booking) {
        alert('Booking not found');
        return;
    }

    // Set form action dynamically
    const cancelForm = document.getElementById('cancelForm');
    cancelForm.action = `/customer/booking/${bookingId}/cancel`;
    
    // Set booking ID in form
    document.getElementById('cancelBookingId').value = bookingId;
    
    // Populate booking details
    document.getElementById('cancel-booking-details').innerHTML = `
        <strong>Vehicle:</strong> ${booking.vehicle?.model || '-'}<br>
        <strong>Dates:</strong> ${formatDate(booking.startDate)} - ${formatDate(booking.endDate)}<br>
        <strong>Total:</strong> RM${(booking.totalPrice || 0).toFixed(2)}<br>
        <strong>Status:</strong> ${booking.bookingStatus || 'Pending'}
    `;
    
    // Show modal
    document.getElementById('cancelModal').classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function submitCancelForm() {
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

// Scroll functionality for past bookings
function scrollPastBookings(containerId, direction) {
    const container = document.getElementById(containerId);
    const scrollAmount = 100; // Adjust scroll amount as needed
    
    if (direction === 'up') {
        container.scrollBy({ top: -scrollAmount, behavior: 'smooth' });
    } else {
        container.scrollBy({ top: scrollAmount, behavior: 'smooth' });
    }
}

// Show more/less toggle for active and pending bookings
function toggleShowAll(containerId, button) {
    const container = document.getElementById(containerId);
    const icon = button.querySelector('i');
    const isShowingAll = container.classList.contains('show-all');
    
    if (isShowingAll) {
        container.classList.remove('show-all');
        icon.className = 'fas fa-chevron-down';
        button.innerHTML = `<i class="fas fa-chevron-down"></i> Show All (${container.querySelectorAll('.booking-card').length})`;
    } else {
        container.classList.add('show-all');
        icon.className = 'fas fa-chevron-up';
        button.innerHTML = `<i class="fas fa-chevron-up"></i> Show Less`;
    }
}

// Update navigation button states based on scroll position
function updateNavButtons(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const navButtons = container.parentElement.querySelectorAll('.nav-btn');
    if (navButtons.length === 0) return;
    
    const upBtn = navButtons[0];
    const downBtn = navButtons[1];
    
    // Disable up button if at top
    upBtn.disabled = container.scrollTop <= 0;
    
    // Disable down button if at bottom
    downBtn.disabled = container.scrollTop + container.clientHeight >= container.scrollHeight - 1;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, adding click handlers');
    
    // Add scroll event listeners for past bookings
    ['completedScroll', 'cancelledScroll'].forEach(containerId => {
        const container = document.getElementById(containerId);
        if (container) {
            container.addEventListener('scroll', () => updateNavButtons(containerId));
            updateNavButtons(containerId); // Initial state
        }
    });
    
    // Add click handlers for all Details buttons
    document.querySelectorAll('button').forEach(button => {
        if (button.textContent.includes('Details') || button.textContent.includes('View')) {
            const onclick = button.getAttribute('onclick');
            if (onclick && onclick.includes('showDetailsModal')) {
                const match = onclick.match(/showDetailsModal\((\d+)\)/);
                if (match) {
                    const bookingId = match[1];
                    button.onclick = function(e) {
                        e.preventDefault();
                        showDetailsModal(bookingId);
                    };
                }
            }
        }
    });
    
    // Add click handlers for all Cancel buttons
    document.querySelectorAll('button').forEach(button => {
        if (button.textContent.includes('Cancel')) {
            const onclick = button.getAttribute('onclick');
            if (onclick && onclick.includes('showCancelModal')) {
                const match = onclick.match(/showCancelModal\((\d+)\)/);
                if (match) {
                    const bookingId = match[1];
                    button.onclick = function(e) {
                        e.preventDefault();
                        showCancelModal(bookingId);
                    };
                }
            }
        }
    });
});

window.onclick = function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
};
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking History - Hasta Travel & Tour</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
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
        }

        .action-btn:hover {
            background-color: #bc3737;
            color: white;
            border-color: #bc3737;
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
            
            .action-btn {
                width: 100%;
                text-align: center;
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
                        <span class="sidebar-icon"></span>
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookingHistory') }}" class="active">
                        <span class="sidebar-icon"></span>
                        My Bookings
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.loyaltycard') }}">
                        <span class="sidebar-icon"></span>
                        Loyalty Card
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.profile.edit') }}">
                        <span class="sidebar-icon"></span>
                        Edit Profile
                    </a>
                </li>
            </ul>
        </div>

        {{-- Main Content Area --}}
        <div class="booking-history-page">
            <h1 class="profile-title">My Booking History</h1>

            <!-- Active/Ongoing Bookings Section (SHOW FIRST) -->
            <div class="booking-section">
                <div class="section-title active">Active Bookings</div>
                
                @forelse($active as $booking)
                    <div class="booking-card">
                        <img src="{{ asset('img/default-car.png') }}" 
                             alt="Vehicle Image" 
                             class="car-image">

                        <div class="booking-details">
                            <div class="car-info">
                                <h3>{{ $booking->model ?? 'Car Model' }}</h3>
                                <p>{{ $booking->vehicleType ?? 'Vehicle Type' }}</p>
                            </div>

                            <div class="price">RM{{ number_format($booking->totalPrice ?? 0, 2) }}</div>

                            <div class="dates">
                                @if($booking->bookingStatus === 'approved')
                                    <div class="date-link">
                                        <a href="{{ route('customer.pickup.form', ['bookingId' => $booking->bookingID]) }}" 
                                           class="date-link-text"
                                           title="Click to go to pickup form">
                                            {{ date('d M Y', strtotime($booking->startDate)) }}
                                        </a>
                                    </div>
                                    <span style="margin: 0 5px;">-</span>
                                    <div class="date-link">
                                        <a href="{{ route('customer.return.form', ['bookingId' => $booking->bookingID]) }}" 
                                           class="date-link-text"
                                           title="Click to go to return form">
                                            {{ date('d M Y', strtotime($booking->endDate)) }}
                                        </a>
                                    </div>
                                @else
                                    {{ date('d M Y', strtotime($booking->startDate)) }} -
                                    {{ date('d M Y', strtotime($booking->endDate)) }}
                                @endif
                            </div>

                            <div class="status active">Active</div>

                            <button class="action-btn" 
                                    onclick="showDetailsModal({{ $booking->bookingID }})">
                                View Details
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        No active bookings currently.
                    </div>
                @endforelse
            </div>

            <!-- Pending Bookings Section -->
            <div class="booking-section">
                <div class="section-title pending">Pending Bookings</div>
                
                @forelse($pending as $booking)
                    <div class="booking-card">
                        <img src="{{ asset('img/default-car.png') }}" 
                             alt="Vehicle Image" 
                             class="car-image">

                        <div class="booking-details">
                            <div class="car-info">
                                <h3>{{ $booking->model ?? 'Car Model' }}</h3>
                                <p>{{ $booking->vehicleType ?? 'Vehicle Type' }}</p>
                            </div>

                            <div class="price">RM{{ number_format($booking->totalPrice ?? 0, 2) }}</div>

                            <div class="dates">
                                {{ date('d M Y', strtotime($booking->startDate)) }} -
                                {{ date('d M Y', strtotime($booking->endDate)) }}
                            </div>

                            <div class="status pending">Pending</div>

                            <button class="action-btn" 
                                    onclick="showCancelModal({{ $booking->bookingID }})">
                                Cancel Booking
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        No pending bookings.
                    </div>
                @endforelse
            </div>

            <!-- Past Bookings Section - Combined Completed and Cancelled -->
            <div class="booking-section">
                <div class="section-title past">Past Bookings</div>
                
                <div class="past-bookings-grid">
                    <!-- Completed Bookings Column -->
                    <div class="past-bookings-section">
                        <h4>Completed</h4>
                        @forelse($completed as $booking)
                            <div class="booking-card" style="margin-bottom: 10px; padding: 15px;">
                                <div style="display: flex; align-items: center; width: 100%;">
                                    <img src="{{ asset('img/default-car.png') }}" 
                                         alt="Vehicle Image" 
                                         style="width: 80px; height: 60px; border-radius: 4px; margin-right: 15px;">
                                    
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; font-size: 14px;">{{ $booking->model }}</div>
                                        <div style="font-size: 12px; color: #666;">{{ $booking->vehicleType }}</div>
                                        <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                            {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                            {{ date('d M Y', strtotime($booking->endDate)) }}
                                        </div>
                                    </div>
                                    
                                    <button class="action-btn" 
                                            onclick="showDetailsModal({{ $booking->bookingID }})"
                                            style="padding: 5px 10px; font-size: 12px;">
                                        View
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #666; font-size: 14px; padding: 20px;">
                                No completed bookings
                            </div>
                        @endforelse
                    </div>

                    <!-- Cancelled Bookings Column -->
                    <div class="past-bookings-section">
                        <h4>Cancelled</h4>
                        @forelse($cancelled as $booking)
                            <div class="booking-card" style="margin-bottom: 10px; padding: 15px;">
                                <div style="display: flex; align-items: center; width: 100%;">
                                    <img src="{{ asset('img/default-car.png') }}" 
                                         alt="Vehicle Image" 
                                         style="width: 80px; height: 60px; border-radius: 4px; margin-right: 15px;">
                                    
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; font-size: 14px;">{{ $booking->model }}</div>
                                        <div style="font-size: 12px; color: #666;">{{ $booking->vehicleType }}</div>
                                        <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                            {{ date('d M Y', strtotime($booking->startDate)) }} - 
                                            {{ date('d M Y', strtotime($booking->endDate)) }}
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('customer.booking.form', ['vehicleId' => $booking->vehicleID]) }}" 
                                       class="action-btn-link"
                                       style="padding: 5px 10px; font-size: 12px;">
                                        Rebook
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #666; font-size: 14px; padding: 20px;">
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
                <h2>Booking Details</h2>
                <button class="close-btn" onclick="closeModal('detailsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('img/default-car.png') }}" alt="Vehicle" class="car-detail-image">
                
                <div class="detail-section">
                    <h3>Vehicle Information</h3>
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
                    <h3>Booking Details</h3>
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
                    <h3>Payment Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Total Price</span>
                        <span class="detail-value" id="detail-total">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Deposit Amount</span>
                        <span class="detail-value" id="detail-deposit">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bank Number</span>
                        <span class="detail-value" id="detail-bank">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bank Name</span>
                        <span class="detail-value" id="detail-bank-name">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('detailsModal')">Close</button>
                <button class="btn btn-primary" onclick="downloadReceipt()">Download Receipt</button>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Cancel Booking</h2>
                <button class="close-btn" onclick="closeModal('cancelModal')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cancelBookingId">
                
                <div class="info-message">
                    <strong>Current Booking Details:</strong>
                    <div id="cancel-booking-details">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <div class="warning-message">
                    <strong>Cancellation Policy:</strong><br>
                    • Refund only for cancellation made in 24 hours before pickup<br>
                    • No refund if cancelled less than 24 hours before pickup<br>
                    • Refund will make in 2 weeks of working days
                </div>

                <div class="form-group">
                    <label for="cancellationReason">Reason for Cancellation (Optional)</label>
                    <textarea id="cancellationReason" placeholder="Please provide reason for cancellation..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('cancelModal')">Close</button>
                <button class="btn btn-danger" onclick="confirmCancel()">Confirm Cancellation</button>
            </div>
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
        if (!booking) return;

        document.getElementById('detail-model').textContent = booking.model ?? '-';
        document.getElementById('detail-type').textContent = booking.vehicleType ?? '-';
        document.getElementById('detail-plate').textContent = booking.plateNumber ?? '-';
        document.getElementById('detail-id').textContent = booking.bookingID ?? '-';
        document.getElementById('detail-start').textContent = formatDate(booking.startDate);
        document.getElementById('detail-end').textContent = formatDate(booking.endDate);
        document.getElementById('detail-status').textContent = booking.bookingStatus ?? '-';
        document.getElementById('detail-duration').textContent =
            booking.bookingDuration ? booking.bookingDuration + ' days' : 'N/A';

        document.getElementById('detail-total').textContent =
            'RM' + Number(booking.totalPrice ?? 0).toFixed(2);

        document.getElementById('detail-deposit').textContent =
            'RM' + Number(booking.depositAmount ?? 0).toFixed(2);

        document.getElementById('detail-bank').textContent = booking.bankNum ?? '-';
        document.getElementById('detail-bank-name').textContent = booking.penamaBank ?? '-';

        document.getElementById('cancelBookingId').value = booking.bookingID;
        document.getElementById('detailsModal').classList.add('active');
    }

    function showCancelModal(bookingId) {
        const booking = findBookingById(bookingId);
        if (!booking) return;

        document.getElementById('cancelBookingId').value = booking.bookingID;
        document.getElementById('cancel-booking-details').innerHTML = `
            <strong>Vehicle:</strong> ${booking.model ?? '-'}<br>
            <strong>Dates:</strong> ${formatDate(booking.startDate)} - ${formatDate(booking.endDate)}<br>
            <strong>Total:</strong> RM${Number(booking.totalPrice ?? 0).toFixed(2)}
        `;

        document.getElementById('cancelModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function confirmCancel() {
        const bookingId = document.getElementById('cancelBookingId').value;
        const reason = document.getElementById('cancellationReason').value;

        if (!confirm('Are you sure you want to cancel this booking?')) return;

        fetch(`/customer/booking/${bookingId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ reason })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Booking cancelled successfully');
            location.reload();
        });
    }

    function downloadReceipt() {
        const bookingId = document.getElementById('cancelBookingId').value;
        window.open(`/customer/booking/${bookingId}/receipt`, '_blank');
    }

    function formatDate(date) {
        if (!date) return '-';
        return new Date(date).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    window.onclick = function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('active');
        }
    };
</script>


</body>
</html>
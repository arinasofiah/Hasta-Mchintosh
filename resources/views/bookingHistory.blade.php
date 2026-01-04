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

        .section-title.completed {
            color: #27ae60;
            border-bottom-color: #27ae60;
        }

        .section-title.upcoming {
            color: #f39c12;
            border-bottom-color: #f39c12;
        }

        .section-title.cancelled {
            color: #e74c3c;
            border-bottom-color: #e74c3c;
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

        .status.completed {
            background-color: #d4efdf;
            color: #27ae60;
        }

        .status.upcoming {
            background-color: #fef5e7;
            color: #f39c12;
        }

        .status.cancelled {
            background-color: #fdecea;
            color: #e74c3c;
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

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .page-btn {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            background-color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .page-btn:hover {
            background-color: #f8f9fa;
            border-color: #bc3737;
        }

        .page-btn.active {
            background-color: #bc3737;
            color: white;
            border-color: #bc3737;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

        .form-group textarea,
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #bc3737;
            outline: none;
            box-shadow: 0 0 0 2px rgba(188, 55, 55, 0.1);
        }

        .date-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-message {
            background-color: #fef5e7;
            border-left: 4px solid #f39c12;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #856404;
            font-size: 14px;
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
            
            .date-inputs {
                grid-template-columns: 1fr;
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
            <a href="{{ route('welcome') }}">
                <button class="head_button">Home</button>
            </a>

            <a href="{{ route('fleet') }}">
                <button class="head_button">Vehicles</button>
            </a>
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

            <!-- Completed Bookings Section -->
            <div class="booking-section">
                <div class="section-title completed">Completed Bookings</div>
                
                @forelse($completed as $booking)
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
                                {{ date('d M Y', strtotime($booking->pickupDate)) }} -
                                {{ date('d M Y', strtotime($booking->returnDate)) }}
                            </div>

                            <div class="status completed">Completed</div>

                            <button class="action-btn" 
                                    onclick="showDetailsModal({{ $booking->id }})">
                                View Details
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        No completed bookings yet.
                    </div>
                @endforelse
            </div>

            <!-- Upcoming Bookings Section -->
            <div class="booking-section">
                <div class="section-title upcoming">Upcoming Bookings</div>
                
                @forelse($upcoming as $booking)
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
                                {{ date('d M Y', strtotime($booking->pickupDate)) }} -
                                {{ date('d M Y', strtotime($booking->returnDate)) }}
                            </div>

                            <div class="status upcoming">Upcoming</div>

                            <button class="action-btn" 
                                    onclick="showModifyModal({{ $booking->id }})">
                                Modify / Cancel
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        No upcoming bookings yet.
                    </div>
                @endforelse
            </div>

            <!-- Cancelled Bookings Section -->
            <div class="booking-section">
                <div class="section-title cancelled">Cancelled Bookings</div>
                
                @forelse($cancelled as $booking)
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
                                {{ date('d M Y', strtotime($booking->pickupDate)) }} -
                                {{ date('d M Y', strtotime($booking->returnDate)) }}
                            </div>

                            <div class="status cancelled">Cancelled</div>

                            <a href="{{ route('selectVehicle', ['vehicleId' => $booking->vehicleID]) }}" 
                               class="action-btn-link">
                                Rebook
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        No cancelled bookings.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($bookings->count() > 10)
                <div class="pagination">
                    <button class="page-btn" disabled>&lt;</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">&gt;</button>
                </div>
            @endif
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
                        <span class="detail-label">Pickup Date</span>
                        <span class="detail-value" id="detail-pickup">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Return Date</span>
                        <span class="detail-value" id="detail-return">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value" id="detail-status">-</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Payment Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Total Price</span>
                        <span class="detail-value" id="detail-total">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Status</span>
                        <span class="detail-value" id="detail-payment">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('detailsModal')">Close</button>
                <button class="btn btn-primary" onclick="downloadReceipt()">Download Receipt</button>
            </div>
        </div>
    </div>

    <!-- Modify/Cancel Modal -->
    <div id="modifyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modify or Cancel Booking</h2>
                <button class="close-btn" onclick="closeModal('modifyModal')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modifyBookingId">
                
                <div class="info-message">
                    <strong>Current Booking Details:</strong>
                    <div id="current-booking-details">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Modify Booking</h3>
                    
                    <div class="date-inputs">
                        <div class="form-group">
                            <label for="pickupDate">Pickup Date</label>
                            <input type="date" id="pickupDate">
                        </div>
                        <div class="form-group">
                            <label for="returnDate">Return Date</label>
                            <input type="date" id="returnDate">
                        </div>
                    </div>

                    <div class="date-inputs">
                        <div class="form-group">
                            <label for="pickupTime">Pickup Time</label>
                            <input type="time" id="pickupTime">
                        </div>
                        <div class="form-group">
                            <label for="returnTime">Return Time</label>
                            <input type="time" id="returnTime">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pickupLocation">Pickup Location</label>
                        <select id="pickupLocation">
                            <option>HASTA Headquarters, Kuala Lumpur</option>
                            <option>HASTA Petaling Jaya Branch</option>
                            <option>HASTA KLIA Branch</option>
                            <option>HASTA Johor Bahru Branch</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="returnLocation">Return Location</label>
                        <select id="returnLocation">
                            <option>HASTA Headquarters, Kuala Lumpur</option>
                            <option>HASTA Petaling Jaya Branch</option>
                            <option>HASTA KLIA Branch</option>
                            <option>HASTA Johor Bahru Branch</option>
                        </select>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Cancel Booking</h3>
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
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('modifyModal')">Close</button>
                <button class="btn btn-danger" onclick="confirmCancel()">Cancel Booking</button>
                <button class="btn btn-primary" onclick="saveModification()">Save Changes</button>
            </div>
        </div>
    </div>

    <script>
        // Sample booking data (replace with real data from server)
        const bookingsData = [
            @foreach($bookings as $booking)
            {
                id: {{ $booking->id }},
                model: "{{ $booking->model ?? 'Car Model' }}",
                vehicleType: "{{ $booking->vehicleType ?? 'Vehicle Type' }}",
                plateNumber: "{{ $booking->plateNumber ?? 'ABC1234' }}",
                pickupDate: "{{ $booking->pickupDate }}",
                returnDate: "{{ $booking->returnDate }}",
                totalPrice: {{ $booking->totalPrice ?? 0 }},
                status: "{{ $booking->status ?? 'pending' }}",
                paymentStatus: "{{ $booking->paymentStatus ?? 'paid' }}"
            },
            @endforeach
        ];

        // Modal functions
        function showDetailsModal(bookingId) {
            const booking = findBookingById(bookingId);
            if (booking) {
                document.getElementById('detail-model').textContent = booking.model;
                document.getElementById('detail-type').textContent = booking.vehicleType;
                document.getElementById('detail-plate').textContent = booking.plateNumber;
                document.getElementById('detail-id').textContent = booking.id;
                document.getElementById('detail-pickup').textContent = formatDate(booking.pickupDate);
                document.getElementById('detail-return').textContent = formatDate(booking.returnDate);
                document.getElementById('detail-status').textContent = booking.status.charAt(0).toUpperCase() + booking.status.slice(1);
                document.getElementById('detail-total').textContent = 'RM' + booking.totalPrice.toFixed(2);
                document.getElementById('detail-payment').textContent = booking.paymentStatus.charAt(0).toUpperCase() + booking.paymentStatus.slice(1);
            }
            document.getElementById('detailsModal').classList.add('active');
        }

        function showModifyModal(bookingId) {
            document.getElementById('modifyBookingId').value = bookingId;
            
            const booking = findBookingById(bookingId);
            if (booking) {
                document.getElementById('current-booking-details').innerHTML = `
                    <strong>Vehicle:</strong> ${booking.model}<br>
                    <strong>Dates:</strong> ${formatDate(booking.pickupDate)} - ${formatDate(booking.returnDate)}<br>
                    <strong>Total:</strong> RM${booking.totalPrice.toFixed(2)}
                `;
                
                // Pre-fill form fields
                document.getElementById('pickupDate').value = formatDateForInput(booking.pickupDate);
                document.getElementById('returnDate').value = formatDateForInput(booking.returnDate);
            }
            
            document.getElementById('modifyModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function confirmCancel() {
            const bookingId = document.getElementById('modifyBookingId').value;
            const reason = document.getElementById('cancellationReason').value;
            
            if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                // In real app, send cancellation request to server
                fetch(`/bookings/${bookingId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Booking cancelled successfully. Refund will be processed within 2 weeks of working days.');
                        closeModal('modifyModal');
                        location.reload(); // Refresh page to update booking list
                    } else {
                        alert('Failed to cancel booking: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        function saveModification() {
            const bookingId = document.getElementById('modifyBookingId').value;
            const pickupDate = document.getElementById('pickupDate').value;
            const returnDate = document.getElementById('returnDate').value;
            const pickupTime = document.getElementById('pickupTime').value;
            const returnTime = document.getElementById('returnTime').value;
            const pickupLocation = document.getElementById('pickupLocation').value;
            const returnLocation = document.getElementById('returnLocation').value;
            
            // In real app, send modification request to server
            fetch(`/bookings/${bookingId}/modify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pickupDate: pickupDate,
                    returnDate: returnDate,
                    pickupTime: pickupTime,
                    returnTime: returnTime,
                    pickupLocation: pickupLocation,
                    returnLocation: returnLocation
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking modified successfully! A confirmation email has been sent to you.');
                    closeModal('modifyModal');
                    location.reload(); // Refresh page to update booking list
                } else {
                    alert('Failed to modify booking: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        function downloadReceipt() {
            const bookingId = document.getElementById('modifyBookingId').value;
            // In real app, generate and download receipt
            window.open(`/bookings/${bookingId}/receipt`, '_blank');
        }

        // Utility functions
        function findBookingById(id) {
            return bookingsData.find(booking => booking.id == id);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }

        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileContainer = document.getElementById('profile-container');
            const profileDropdown = document.getElementById('profile-dropdown');
            
            if (profileContainer) {
                profileContainer.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
                });
            }
            
            // Close dropdown when clicking elsewhere
            document.addEventListener('click', function() {
                if (profileDropdown) {
                    profileDropdown.style.display = 'none';
                }
            });
        });
    </script>

    {{-- Footer --}}
 

</body>
</html>
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
 
    <style>
        
        .status.upcoming {
            background-color: #6c757d !important; /* Grey color for upcoming */
            color: white !important;
        }
        
        .upcoming-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f8f9fa;
            color: #495057;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #dee2e6;
            z-index: 1;
        }
        
        .time-until {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .booking-card {
            position: relative;
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
        
            <div class="booking-section">
                <div class="section-title active">Active & Upcoming Bookings</div>
                <div class="booking-cards-container" id="activeBookingsContainer">
                    @forelse($active as $booking)
                        @php
                            $vehicle = $booking->vehicle ?? null;
                            $rentalPrice = $booking->totalPrice ?? 0;
                            $totalCost = $booking->totalCost ?? ($rentalPrice + 50);
                            $totalPaid = $booking->totalPaid ?? 0;
                            $remainingBalance = $booking->remainingBalance ?? 0;
                            $isFullyPaid = $booking->isFullyPaid ?? false;
                            
                            $now = \Carbon\Carbon::now();
                            $startDate = \Carbon\Carbon::parse($booking->startDate);
                            $isUpcoming = $now->lt($startDate);
                            
                            $endDate = \Carbon\Carbon::parse($booking->endDate);
                            $isCurrent = $now->between($startDate, $endDate);
                            
                            $canPayBalance = $remainingBalance > 0 && 
                                           $booking->bookingStatus === 'approved' && 
                                           !$isUpcoming;
                        @endphp
                        
                        <div class="booking-card">
                            @if($isUpcoming)
                                <div class="upcoming-badge">
                                    <i class="fas fa-calendar-alt"></i> Upcoming
                                </div>
                            @endif
                            
                            <div class="booking-details">
                                <div class="car-info">
                                    <h3>{{ $vehicle->model ?? 'Car Model' }}</h3>
                                    <p>{{ $vehicle->vehicleType ?? 'Vehicle Type' }}</p>
                                    
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
                                    
                                    @if($isUpcoming)
                                        <div class="time-until">
                                            <i class="far fa-clock"></i> 
                                            Starts in {{ \Carbon\Carbon::parse($booking->startDate)->diffForHumans() }}
                                        </div>
                                    @endif
                                    
                                    @if($booking->bookingStatus === 'approved')
                                        <div class="action-buttons" style="margin-top: 10px;">
                                             <a href="{{ url('/pickup/' . $booking->bookingID) }}"
                                               class="btn btn-primary btn-sm pickup-btn"
                                               title="Process vehicle pickup">
                                                 <i class="fas fa-car"></i> Pickup
                                            </a>
                                            
                                            @if($booking->pickup?->pickupComplete ?? false)
                                                 <a href="{{ url('/pickup/' . $booking->bookingID) }}"
                                                class="btn btn-warning btn-sm return-btn"
                                                title="Process vehicle return">
                                                    <i class="fas fa-undo"></i> Return
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="status 
                                    @if($isUpcoming) upcoming
                                    @elseif($booking->bookingStatus === 'reserved') reserved
                                    @else active @endif">
                                    @if($isUpcoming)
                                        Upcoming
                                    @elseif($booking->bookingStatus === 'reserved')
                                        Reserved
                                    @else
                                        Active
                                    @endif
                                </div>
                                    
                                    @if($canPayBalance)
                                        <a href="{{ route('payment.remaining', ['bookingID' => $booking->bookingID]) }}" 
                                           class="action-btn-success" 
                                           style="text-decoration: none;">
                                            <i class="fas fa-credit-card"></i> Pay Balance (RM{{ number_format($remainingBalance, 2) }})
                                        </a>
                                    @endif
                                </div>
                            </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-check" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                            No active or upcoming bookings.
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
            </div> <div class="booking-section">
                <div class="section-title pending">Pending Bookings</div>
                <div class="booking-cards-container" id="pendingBookingsContainer">
                    @forelse($pending as $booking)
                        @php
                            $vehicle = $booking->vehicle ?? null;
                            $rentalPrice = $booking->totalPrice ?? 0;
                            $totalPaid = $booking->totalPaid ?? 0;
                            $remainingBalance = $booking->remainingBalance ?? 0;
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
            </div> <div class="booking-section">
                <div class="section-title past">Past Bookings</div>
                
                <div class="past-bookings-grid">
                    <div class="past-bookings-section">
                        <h4><i class="fas fa-check-circle"></i> Completed</h4>
                        <div class="past-bookings-scroll-container" id="completedScroll">
                            @forelse($completed as $booking)
                                @php
                                    $vehicle = $booking->vehicle ?? null;
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

                    <div class="past-bookings-section">
                        <h4><i class="fas fa-times-circle"></i> Cancelled</h4>
                        <div class="past-bookings-scroll-container" id="cancelledScroll">
                            @forelse($cancelled as $booking)
                                @php
                                    $vehicle = $booking->vehicle ?? null;
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
    let bookingsData = [];

    // Add all bookings to JavaScript array for modal access
    @if(isset($active) && $active->isNotEmpty())
        @foreach($active as $booking)
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
                totalCost: {{ $booking->totalCost ?? 0 }},
                remainingBalance: {{ $booking->remainingBalance ?? 0 }},
                depositAmount: 50,
                pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
                penamaBank: '{{ $booking->penamaBank ?? '' }}',
                bank_name: '{{ $booking->bank_name ?? '' }}',
                bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
                duration: {{ $booking->bookingDuration ?? 1 }},
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
            @endphp
            bookingsData.push({
                bookingID: {{ $booking->bookingID }},
                startDate: '{{ $booking->startDate }}',
                endDate: '{{ $booking->endDate }}',
                bookingStatus: '{{ $booking->bookingStatus }}',
                totalPrice: {{ $booking->totalPrice ?? 0 }},
                totalPaid: {{ $booking->totalPaid ?? 0 }},
                totalCost: {{ $booking->totalCost ?? 0 }},
                remainingBalance: {{ $booking->remainingBalance ?? 0 }},
                depositAmount: 50,
                pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
                penamaBank: '{{ $booking->penamaBank ?? '' }}',
                bank_name: '{{ $booking->bank_name ?? '' }}',
                bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
                duration: {{ $booking->bookingDuration ?? 1 }},
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
                totalCost: {{ $booking->totalCost ?? 0 }},
                remainingBalance: {{ $booking->remainingBalance ?? 0 }},
                depositAmount: 50,
                pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
                penamaBank: '{{ $booking->penamaBank ?? '' }}',
                bank_name: '{{ $booking->bank_name ?? '' }}',
                bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
                duration: {{ $booking->bookingDuration ?? 1 }},
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
            @endphp
            bookingsData.push({
                bookingID: {{ $booking->bookingID }},
                startDate: '{{ $booking->startDate }}',
                endDate: '{{ $booking->endDate }}',
                bookingStatus: '{{ $booking->bookingStatus }}',
                totalPrice: {{ $booking->totalPrice ?? 0 }},
                totalPaid: {{ $booking->totalPaid ?? 0 }},
                totalCost: {{ $booking->totalCost ?? 0 }},
                remainingBalance: {{ $booking->remainingBalance ?? 0 }},
                depositAmount: 50,
                pay_amount_type: '{{ $booking->pay_amount_type ?? '' }}',
                penamaBank: '{{ $booking->penamaBank ?? '' }}',
                bank_name: '{{ $booking->bank_name ?? '' }}',
                bank_owner_name: '{{ $booking->bank_owner_name ?? '' }}',
                duration: {{ $booking->bookingDuration ?? 1 }},
                vehicle: {!! json_encode($vehicleData) !!}
            });
        @endforeach
    @endif

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
        
        // Set status with proper display name
        let statusDisplay = booking.bookingStatus || '-';
        if (statusDisplay === 'reserved') {
            statusDisplay = 'Reserved';
        } else if (statusDisplay === 'approved') {
            statusDisplay = 'Approved';
        } else if (statusDisplay === 'pending') {
            statusDisplay = 'Pending';
        } else if (statusDisplay === 'completed') {
            statusDisplay = 'Completed';
        } else if (statusDisplay === 'cancelled') {
            statusDisplay = 'Cancelled';
        }
        document.getElementById('detail-status').textContent = statusDisplay;
        
        // Use duration field
        document.getElementById('detail-duration').textContent = 
            (booking.duration || 1) + ' day(s)';
        
        // Set payment details
        document.getElementById('detail-total-cost').textContent = 'RM' + (booking.totalCost || 0).toFixed(2);
        document.getElementById('detail-paid').textContent = 'RM' + (booking.totalPaid || 0).toFixed(2);
        document.getElementById('detail-remaining').textContent = 'RM' + (booking.remainingBalance || 0).toFixed(2);
        
        // Set payment type
        let paymentType = '-';
        if (booking.pay_amount_type === 'deposit') {
            paymentType = 'Deposit Only';
        } else if (booking.pay_amount_type === 'full') {
            paymentType = 'Full Payment';
        } else if (booking.pay_amount_type) {
            paymentType = booking.pay_amount_type;
        }
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
            payBalanceBtn.innerHTML = `<i class="fas fa-credit-card me-2"></i> Pay Remaining Balance (RM${(booking.remainingBalance || 0).toFixed(2)})`;
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

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
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
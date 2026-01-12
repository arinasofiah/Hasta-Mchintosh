<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - #{{ $booking->booking_code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="{{ asset('css/admin-header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }


        #logo { height: 40px; }
        #profile { display: flex; align-items: center; gap: 10px; }
        #pfp { width: 40px; height: 40px; border-radius: 50%; }

        .main-container {
            margin-top: 100px; 
            margin-bottom: 50px;
        }

        .btn-back {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background-color: #dee2e6;
            color: #212529;
            transform: translateX(-5px); 
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
            @auth <span id="username">{{ Auth::user()->name }}</span> @endauth
        </div>
    </div>

    <div class="container main-container">
        
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.bookings') }}" class="btn btn-back rounded-pill px-4 py-2">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to List
            </a>
            <h4 class="ms-3 mb-0 text-muted">| Reviewing Booking <span class="text-dark fw-bold">#{{ $booking->booking_code }}</span></h4>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h6 class="text-muted text-start mb-3 fw-bold small">VEHICLE</h6>
                        <img src="{{ asset('storage/' . ($booking->vehicle->image ?? 'default_car.png')) }}" 
                             class="img-fluid rounded mb-3" style="width:100%; object-fit: cover;" alt="Car">
                        <h5 class="fw-bold">{{ $booking->vehicle->model ?? 'Unknown Model' }}</h5>
                        <p class="text-muted m-0"><i class="fa-solid fa-car-side me-1"></i> {{ $booking->vehicle->plate_number ?? 'No Plate' }}</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted fw-bold mb-3 small">PAYMENT PROOF</h6>
                        @if($booking->payment_proof)
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $booking->payment_proof) }}" class="img-fluid rounded border w-100" alt="Receipt">
                                <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="btn btn-sm btn-dark position-absolute bottom-0 end-0 m-2">
                                    <i class="fa-solid fa-expand"></i>
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning m-0 small text-center">
                                <i class="fa-solid fa-triangle-exclamation"></i> No receipt uploaded.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold text-primary">Reservation Info</h5>
                        @php
                            $badgeColor = match($booking->bookingStatus) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'cancelled', 'rejected' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }} px-3 py-2 rounded-pill text-dark bg-opacity-25 border border-{{ $badgeColor }}">
                            {{ ucfirst($booking->bookingStatus) }}
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="text-muted small">Name</label>
                                <div class="fw-bold">{{ $booking->customer->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Email</label>
                                <div class="fw-bold">{{ $booking->customer->email ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Phone</label>
                                <div class="fw-bold">{{ $booking->customer->phone ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <hr class="opacity-25">

                        <div class="row align-items-center mb-4">
                            <div class="col-md-7">
                                <div class="bg-light p-3 rounded d-flex justify-content-between align-items-center">
                                    <div class="text-center">
                                        <small class="text-muted">Pick-up</small>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M') }}</div>
                                        <small>{{ \Carbon\Carbon::parse($booking->pickupDate)->format('H:i') }}</small>
                                    </div>
                                    <i class="fa-solid fa-arrow-right text-muted"></i>
                                    <div class="text-center">
                                        <small class="text-muted">Return</small>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M') }}</div>
                                        <small>{{ \Carbon\Carbon::parse($booking->returnDate)->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 text-end">
                                <small class="text-muted">Total Amount</small>
                                <div class="h2 fw-bold text-success mb-0">RM {{ number_format($booking->totalPrice, 2) }}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                             @if(in_array($booking->bookingStatus, ['pending', 'paid']))
                                <form action="{{ route('admin.bookings.reject', $booking->bookingID) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-outline-danger px-4" onclick="return confirm('Reject?')">Reject</button>
                                </form>
                                <form action="{{ route('admin.bookings.approve', $booking->bookingID) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success px-5 fw-bold" onclick="return confirm('Approve?')">Approve Booking</button>
                                </form>
                             @else
                                <button class="btn btn-secondary w-100" disabled>Status: {{ ucfirst($booking->bookingStatus) }}</button>
                             @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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
        .main-container { margin-top: 100px; margin-bottom: 50px; }
        .btn-back { background-color: #e9ecef; color: #495057; font-weight: 600; border: none; transition: all 0.3s; }
        .btn-back:hover { background-color: #dee2e6; color: #212529; transform: translateX(-5px); }
        
        .evidence-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .evidence-img:hover { transform: scale(1.05); }
        .signature-img {
            max-width: 200px;
            border: 1px dashed #cb3737;
            padding: 5px;
            background: #fff;
        }
        .nav-tabs .nav-link { color: #555; font-weight: 500; }
        .nav-tabs .nav-link.active { color: #CB3737; border-top: 3px solid #CB3737; font-weight: 700; }
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
                       <!-- <img src="{{ asset('storage/' . ($booking->vehicle->image ?? 'default_car.png')) }}" 
                             class="img-fluid rounded mb-3" style="width:100%; object-fit: cover;" alt="Car"> -->
                        <h5 class="fw-bold">{{ $booking->vehicle->model ?? 'Unknown Model' }}</h5>
                        <p class="text-muted m-0"><i class="fa-solid fa-car-side me-1"></i> {{ $booking->vehicle->plateNumber ?? 'No Plate' }}</p>
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
                    <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
                        <ul class="nav nav-tabs card-header-tabs" id="bookingTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">Overview</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="pickup-tab" data-bs-toggle="tab" data-bs-target="#pickup" type="button">
                                    Pickup Report 
                                    @if($booking->pickup && $booking->pickup->pickupComplete) <i class="fas fa-check-circle text-success"></i> @endif
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return" type="button">
                                    Return Report
                                    @if($booking->returnDetail && $booking->returnDetail->returnComplete) 
                                        <i class="fas fa-check-circle text-success ms-1"></i> 
                                    @endif
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="bookingTabsContent">
                            
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold text-primary m-0">Reservation Info</h5>
                                    @php
                                        $badgeColor = match($booking->bookingStatus) {
                                            'paid', 'completed' => 'success',
                                            'pending' => 'warning',
                                            'cancelled', 'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }} px-3 py-2 rounded-pill text-dark bg-opacity-25 border border-{{ $badgeColor }}">
                                        {{ ucfirst($booking->bookingStatus) }}
                                    </span>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="text-muted small">Name</label>
                                        <div class="fw-bold">{{ $booking->user->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Email</label>
                                        <div class="fw-bold">{{ $booking->user->email ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Phone</label>
                                        <div class="fw-bold">{{ $booking->user->phone ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <hr class="opacity-25">

                                <div class="row align-items-center mb-4">
                                    <div class="col-md-7">
                                        <div class="bg-light p-3 rounded d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <small class="text-muted">Pick-up</small>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->startDate)->format('d M') }}</div>
                                                <small>{{ \Carbon\Carbon::parse($booking->pickupDate)->format('H:i') }}</small>
                                            </div>
                                            <i class="fa-solid fa-arrow-right text-muted"></i>
                                            <div class="text-center">
                                                <small class="text-muted">Return</small>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->endDate)->format('d M') }}</div>
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

                            <div class="tab-pane fade" id="pickup" role="tabpanel">
                                @if($booking->pickup)
                                    <h5 class="fw-bold mb-3">📸 Vehicle Condition (At Pickup)</h5>
                                    <div class="row g-2 mb-4">
                                        <div class="col-6 col-md-3">
                                            <span class="small text-muted">Front</span>
                                            <img src="{{ asset($booking->pickup->photo_front) }}" class="evidence-img" onclick="window.open(this.src)">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <span class="small text-muted">Back</span>
                                            <img src="{{ asset($booking->pickup->photo_back) }}" class="evidence-img" onclick="window.open(this.src)">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <span class="small text-muted">Left</span>
                                            <img src="{{ asset($booking->pickup->photo_left) }}" class="evidence-img" onclick="window.open(this.src)">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <span class="small text-muted">Right</span>
                                            <img src="{{ asset($booking->pickup->photo_right) }}" class="evidence-img" onclick="window.open(this.src)">
                                        </div>
                                    </div>

                                    <h5 class="fw-bold mb-3">✍️ Customer Confirmation</h5>
                                    <div class="p-3 bg-light rounded border">
                                        <p class="mb-1"><small>Agreed to T&C and Condition:</small></p>
                                        @if($booking->pickup->signature_path)
                                            <img src="{{ $booking->pickup->signature_path }}" class="signature-img" alt="Signature">
                                        @elseif($booking->pickup->manual_signature_photo)
                                            <a href="{{ asset($booking->pickup->manual_signature_photo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-signature"></i> View Signed Document
                                            </a>
                                        @else
                                            <span class="text-danger">Not Signed</span>
                                        @endif
                                        <div class="mt-2 small text-muted">
                                            Picked up at: {{ $booking->pickup->updated_at->format('d M Y, h:i A') }} <br>
                                            Location: {{ $booking->pickup->pickupLocation }}
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">Customer has not picked up the vehicle yet.</div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="return" role="tabpanel">
                            @if($booking->returnDetail) 
                                <h5 class="fw-bold mb-3">📸 Vehicle Condition (At Return)</h5>
                                <div class="row g-2 mb-4">
                                    <div class="col-6 col-md-3">
                                        <span class="small text-muted">Front</span>
                                        <img src="{{ asset($booking->returnDetail->return_photo_front) }}" class="evidence-img" onclick="window.open(this.src)">
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="small text-muted">Back</span>
                                        <img src="{{ asset($booking->returnDetail->return_photo_back) }}" class="evidence-img" onclick="window.open(this.src)">
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="small text-muted">Dashboard</span>
                                        <img src="{{ asset($booking->returnDetail->return_photo_dashboard) }}" class="evidence-img" onclick="window.open(this.src)">
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="small text-muted">Keys</span>
                                        <img src="{{ asset($booking->returnDetail->return_photo_keys) }}" class="evidence-img" onclick="window.open(this.src)">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-light border-0 mb-3">
                                            <div class="card-body">
                                                <h6 class="fw-bold">⛽ Fuel & Feedback</h6>
                                                <p class="mb-1"><strong>Fuel Level:</strong> {{ $booking->returnDetail->fuelAmount }}%</p>
                                                <p class="mb-0"><strong>Feedback:</strong> "{{ $booking->returnDetail->feedback }}"</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-danger mb-3">
                                            <div class="card-header bg-danger text-white">
                                                Additional Fees
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span>Late Fee:</span>
                                                    <span class="fw-bold">RM {{ number_format($booking->returnDetail->late_fee, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span>Fuel Surcharge:</span>
                                                    <span class="fw-bold">RM {{ number_format($booking->returnDetail->fuel_fee, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between bg-light">
                                                    <strong>Total Extra:</strong>
                                                    <strong class="text-danger">RM {{ number_format($booking->returnDetail->total_fee, 2) }}</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($booking->returnDetail->trafficTicketPhoto)
                                <div class="mt-3">
                                    <h6 class="text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Traffic Ticket Reported</h6>
                                    <a href="{{ asset($booking->returnDetail->trafficTicketPhoto) }}" target="_blank" class="btn btn-sm btn-outline-danger">View Ticket Photo</a>
                                </div>
                                @endif

                            @else
                                <div class="alert alert-info">Vehicle has not been returned yet.</div>
                            @endif
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
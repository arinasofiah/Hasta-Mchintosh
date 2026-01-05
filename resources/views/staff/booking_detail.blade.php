<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta - Booking Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; padding: 20px; }
        .card { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; }
        .card-header { background-color: #E85D04; color: black; font-weight: 700; border-radius: 10px 10px 0 0 !important; padding: 15px; }
        .label { font-weight: 600; color: #555; }
        .value { font-weight: 400; color: #000; font-size: 16px; }
        .btn-approve { background-color: #2ECC71; color: white; font-weight: 600; padding: 10px 30px; border: none; border-radius: 5px; }
        .btn-approve:hover { background-color: #27ae60; color: white; }
        .back-link { text-decoration: none; color: #333; font-weight: 600; display: inline-flex; align-items: center; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container mt-4">
    <a href="{{ route('staff.dashboard') }}" class="back-link">
        <span style="font-size: 20px; margin-right: 8px;">&larr;</span> Back to List
    </a>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Booking Details #{{ $booking->bookingID }}</span>
                    <span class="badge bg-light text-dark">{{ $bookings->bookingStatus ?? 'Pending' }}</span>
                </div>
                <div class="card-body p-4">
                    
                    <h5 class="text-muted mb-3">Customer Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="label">Name</div>
                            <div class="value">{{ $booking->customer->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="label">Email</div>
                            <div class="value">{{ $booking->customer->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <hr>

                    <h5 class="text-muted mb-3 mt-3">Rental Details</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="label">Pick-up Date</div>
                            <div class="value">{{ $booking->pickupDate }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="label">Return Date</div>
                            <div class="value">{{ $booking->returnDate }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="label">Total Price</div>
                            <div class="value" style="font-size: 18px; color: #E85D04; font-weight: 700;">
                                RM {{ number_format($booking->totalPrice, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">
                        @if($bookings->bookingStatus == 'Pending')
                            
                            <form action="{{ route('staff.bookings.reject', $bookings->bookingID) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to REJECT this booking?')">
                                    Reject
                                </button>
                            </form>

                            <form action="{{ route('staff.bookings.approve', $bookings->bookingID) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-approve" onclick="return confirm('Are you sure you want to approve this booking?')">
                                    Approve Booking
                                </button>
                            </form>

                        @else
                            <button class="btn btn-secondary" disabled>
                                Action Taken: {{ $bookings->bookingStatus }}
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
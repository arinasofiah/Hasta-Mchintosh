<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta - Verify Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; padding: 20px; }
        .page-header { display: flex; align-items: center; margin-bottom: 30px; }
        .back-btn { font-size: 24px; color: #000; text-decoration: none; margin-right: 20px; font-weight: bold; }
        .logo-img { height: 40px; margin-right: 20px; }
        .page-title { font-weight: 700; font-size: 24px; margin: 0; }
        .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid #333; }
        .custom-table thead tr { background-color: #E85D04; color: black; }
        .custom-table th { padding: 15px; text-align: left; border-bottom: 1px solid #333; font-weight: 700; }
        .custom-table tbody tr { background-color: #eee; }
        .custom-table td { padding: 15px; border-bottom: 1px solid #ccc; border-right: 1px solid #ccc; font-size: 14px; }
        .custom-table td:last-child { border-right: none; }
        .view-link { color: #0066cc; font-weight: 700; text-decoration: none; }

        .btn-approve {
            background-color: #28a745; 
            color: white;
            border: none; 
            border-radius: 20px; 
            padding: 5px 20px;
            font-weight: 600;
            font-size: 13px;
            transition: 0.3s;
        }
        .btn-approve:hover {
            background-color: #218838;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <a href="{{ route('staff.dashboard') }}" class="back-btn"><</a>
        <img src="{{ asset('img/hasta_logo.jpg') }}" class="logo-img">
        <h1 class="page-title">Verify Payment</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Expected Amount</th>
                    <th class="text-center">Proof Of Payment</th>
                    <th class="text-center">Action</th> 
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->bookingID }}</td>
                    <td>RM {{ number_format($booking->totalPrice, 2) }}</td>
                    <td class="text-center">
                        @if($booking->payment && $booking->payment->receiptImage)
                            <a href="#" class="view-link" onclick="alert('Ideally this opens the receipt image modal!')">View</a>
                        @else
                            <span class="text-muted">No Proof</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <form action="{{ route('staff.payment.approve', $booking->bookingID) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-approve" onclick="return confirm('Confirm payment verified? This will verify the booking and calculate loyalty points.')">
                                Verify & Approve
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center p-4">No pending payments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-4">{{ $bookings->links('pagination::bootstrap-5') }}</div>
</div>
</body>
</html>
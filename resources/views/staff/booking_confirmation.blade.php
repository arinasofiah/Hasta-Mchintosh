<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta - Booking Confirmation</title>
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
        
        .status-badge { padding: 5px 20px; border-radius: 20px; color: black; font-weight: 600; display: inline-block; min-width: 100px; text-align: center; }
        .bg-green { background-color: #2ECC71; }
        .bg-red { background-color: #E74C3C; }
        .bg-yellow { background-color: #F1C40F; }
    </style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <a href="{{ route('staff.dashboard') }}" class="back-btn"><</a>
        <img src="{{ asset('img/hasta_logo.jpg') }}" class="logo-img">
        <h1 class="page-title">Booking Confirmation</h1>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Name</th>
                    <th class="text-center">Full Detail</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->bookingID }}</td>
                    <td>{{ $booking->customer->name ?? 'Unknown' }}</td>
                    <td class="text-center">
                        <a href="{{ route('staff.bookings.show', $booking->bookingID) }}" class="view-link">View</a>
                    </td>
                    <td class="text-center">
                        <span class="status-badge bg-yellow">Pending</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center p-4">No pending bookings.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-4">{{ $bookings->links('pagination::bootstrap-5') }}</div>
</div>
</body>
</html>
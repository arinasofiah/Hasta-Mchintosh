<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Reporting</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .main-content { margin-left: 250px; padding: 40px; }
        
        .report-card { background: #fff; border-radius: 15px; padding: 30px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .stats-row { border-bottom: 1px solid #f1f1f1; padding: 15px 0; }
        .stats-header { font-weight: 600; color: #888; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        
        .tab-menu { border-bottom: 1px solid #eee; margin-bottom: 20px; }
          .tab-link { padding: 10px 20px; text-decoration: none; color: #666; display: inline-block; }
        .tab-link.active { color: #000; border-bottom: 2px solid #000; font-weight: 600; }
    </style>
</head>
<body>

    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
        </div>

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item"> Dashboard</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item active">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item"> Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item"> Promotions</a>
        <a href="#" class="nav-item"> Settings</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Reporting</h2>
            
            <form action="{{ route('admin.reporting') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="view" value="{{ $view }}">
                
                @if($view === 'daily')
                    <select name="month" class="form-select border-0 bg-white shadow-sm" style="border-radius: 20px; width: 140px;">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                        @endfor
                    </select>
                @endif

                <select name="year" class="form-select border-0 bg-white shadow-sm" style="border-radius: 20px; width: 110px;">
                    @for($y = date('Y'); $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-success px-4" style="border-radius: 20px; background-color: #1a8f36; border:none;">Submit</button>
            </form>
        </div>

        <div class="tab-menu">
            <a href="?view=monthly&year={{ $year }}" class="tab-link {{ $view === 'monthly' ? 'active' : '' }}">Monthly</a>
            <a href="?view=daily&year={{ $year }}&month={{ $month }}" class="tab-link {{ $view === 'daily' ? 'active' : '' }}">Daily</a>
            <a href="#" class="tab-link">Booking Overview</a>
        </div>

        <div class="report-card">
            <h5 class="fw-bold mb-4">{{ ucfirst($view) }} Income Report</h5>
            <div style="height: 300px;">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <div class="report-card">
            <div class="row stats-header pb-2">
                <div class="col-4">{{ $view === 'daily' ? 'Day' : 'Month' }}</div>
                <div class="col-4 text-center">No of Sales</div>
                <div class="col-4 text-end">Total Amount</div>
            </div>
            
            @foreach($reportStats as $stat)
            <div class="row stats-row align-items-center">
                <div class="col-4 fw-bold">
                    {{ $view === 'daily' ? "Day $stat->day" : $stat->month_name }}
                </div>
                <div class="col-4 text-center text-muted">{{ $stat->sales_count }}</div>
                <div class="col-4 text-end fw-bold text-success">RM {{ number_format($view === 'daily' ? $stat->total : $stat->total, 2) }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: '{{ $view === "daily" ? "line" : "bar" }}',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Income (RM)',
                    data: @json($chartData),
                    backgroundColor: '{{ $view === "daily" ? "rgba(26, 143, 54, 0.1)" : "#1a8f36" }}',
                    borderColor: '#1a8f36',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f8f9fa' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>
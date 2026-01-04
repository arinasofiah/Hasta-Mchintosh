<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Reporting</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; z-index: 100; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .main-content { margin-left: 250px; padding: 40px; }
        
        /* Card Styling */
        .report-card { background: #fff; border-radius: 15px; padding: 30px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .stats-row { border-bottom: 1px solid #f1f1f1; padding: 15px 0; }
        .stats-header { font-weight: 600; color: #888; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        
        /* Tabs */
        .tab-menu { border-bottom: 1px solid #eee; margin-bottom: 25px; }
        .tab-link { padding: 10px 20px; text-decoration: none; color: #666; display: inline-block; transition: 0.3s; }
        .tab-link.active { color: #1a8f36; border-bottom: 3px solid #1a8f36; font-weight: 600; }
        
        /* Unified Filter Box */
        .filter-group-container { display: flex; align-items: center; background: #fff; padding: 5px 15px; border-radius: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .filter-select { border: none !important; background: transparent !important; font-size: 0.85rem; padding: 5px 10px; width: 125px; cursor: pointer; }
        .filter-select:focus { outline: none; box-shadow: none; }
        .filter-divider { width: 1px; height: 20px; background: #eee; margin: 0 5px; }

        .btn-export { border-radius: 20px; border: 1px solid #1a8f36; color: #1a8f36; background: #fff; transition: 0.3s; }
        .btn-export:hover { background: #1a8f36; color: #fff; }
    </style>
</head>
<body>

    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
    </div>

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item active">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item">Promotions</a>
        <a href="#" class="nav-item">Settings</a>
    </div>

    <div class="main-content">
        <div class="header-flex d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Reporting</h2>
            
            <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('admin.reporting') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
                    <input type="hidden" name="view" value="{{ $view }}">
                    
                    @if($view === 'overview')
                        <div class="filter-group-container me-2">
                            <select name="faculty" class="filter-select">
                                <option value="">All Faculty</option>
                                <option value="FKM" {{ request('faculty') == 'FKM' ? 'selected' : '' }}>MECHANICAL ENGINEERING</option>
                                <option value="FS" {{ request('faculty') == 'FS' ? 'selected' : '' }}>SCIENCE </option>
                                <option value="FM" {{ request('faculty') == 'FM' ? 'selected' : '' }}>MANAGEMENT </option>
                                <option value="FKA" {{ request('faculty') == 'FKA' ? 'selected' : '' }}>CIVIL ENGINEERING</option>
                                <option value="FC" {{ request('faculty') == 'FC' ? 'selected' : '' }}>COMPUTING </option>
                                <option value="FKE" {{ request('faculty') == 'FKE' ? 'selected' : '' }}>ELECTRICAL ENGINEERING</option>
                                <option value="FSSH" {{ request('faculty') == 'FSSH' ? 'selected' : '' }}>SOCIAL SCIENCES AND HUMANITIES</option>
                                <option value="FKT" {{ request('faculty') == 'FKT' ? 'selected' : '' }}>CHEMICAL AND ENERGY ENGINEERING</option>
                                <option value="FABU" {{ request('faculty') == 'FABU' ? 'selected' : '' }}>BUILT ENVIRONMENT AND SURVEYING</option>
                                <option value="FEST" {{ request('faculty') == 'FEST' ? 'selected' : '' }}>EDUCATIONAL SCIENCES AND TECHNOLOGY</option>
                            </select>
                            <div class="filter-divider"></div>
                            <select name="college" class="filter-select">
                                <option value="">All College</option>
                                <option value="KTR" {{ request('college') == 'KTR' ? 'selected' : '' }}>KOLEJ TUN RAZAK (KTR)</option>
                                <option value="KTF" {{ request('college') == 'KTF' ? 'selected' : '' }}>KOLEJ TUN FATIMAH (KTF)</option>
                                <option value="KRP" {{ request('college') == 'KRP' ? 'selected' : '' }}>KOLEJ RAHMAN PUTRA (KRP)</option>
                                <option value="KTDI" {{ request('college') == 'KTDI' ? 'selected' : '' }}>KOLEJ TUN DR. ISMAIL (KTDI)</option>
                                <option value="KTC" {{ request('college') == 'KTC' ? 'selected' : '' }}>KOLEJ TUANKU CANSELOR (KTC)</option>
                                <option value="KTHO" {{ request('college') == 'KTHO' ? 'selected' : '' }}>KOLEJ TUN HUSSEIN ONN (KTHO)</option>
                                <option value="KDSE" {{ request('college') == 'KDSE' ? 'selected' : '' }}>MeKOLEJ DATIN SRI ENDON (KDSE)rbau</option>
                                <option value="K9/K10" {{ request('college') == 'K9/K10' ? 'selected' : '' }}>KOLEJ 9/10</option>
                                <option value="KP" {{ request('college') == 'KP' ? 'selected' : '' }}>KOLEJ PERDANA (KP)</option>
                                <option value="KDOJ" {{ request('college') == 'KDOJ' ? 'selected' : '' }}>KOLEJ DATO’ ONN JAAFAR (KDOJ)</option>
                                <option value="KLG" {{ request('college') == 'KLG' ? 'selected' : '' }}>KLG</option>
                                <option value="UTMI" {{ request('college') == 'UTMI' ? 'selected' : '' }}>UTM International</option>
                                <option value="Outside UTM" {{ request('college') == 'Outside UTM' ? 'selected' : '' }}>None</option>
                            </select>
                            <div class="filter-divider"></div>
                            <select name="vehicleType" class="filter-select">
                                <option value="">All Types</option>
                                <option value="Sedan" {{ request('vehicleType') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                <option value="SUV" {{ request('vehicleType') == 'SUV' ? 'selected' : '' }}>SUV</option>
                            </select>
                        </div>
                    @endif

                    @if($view === 'daily')
                        <select name="month" class="form-select border-0 bg-white shadow-sm" style="border-radius: 20px; width: 110px;">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $month == $m ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$m,1)) }}</option>
                            @endfor
                        </select>
                    @endif

                    <select name="year" class="form-select border-0 bg-white shadow-sm" style="border-radius: 20px; width: 95px;">
                        <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>2025</option>
                        <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>2026</option>
                    </select>

                    <button type="submit" class="btn btn-success px-4" style="border-radius: 20px; background-color: #1a8f36; border:none;">Filter</button>
                </form>

                <a href="{{ route('admin.reporting.export', request()->all()) }}" class="btn btn-export shadow-sm px-4">
                    <i class="fa-solid fa-file-excel me-2"></i>Excel
                </a>
            </div>
        </div>

        <div class="tab-menu">
            <a href="?view=monthly&year={{ $year }}" class="tab-link {{ $view === 'monthly' ? 'active' : '' }}">Monthly</a>
            <a href="?view=daily&year={{ $year }}&month={{ $month }}" class="tab-link {{ $view === 'daily' ? 'active' : '' }}">Daily</a>
            <a href="?view=overview&year={{ $year }}" class="tab-link {{ $view === 'overview' ? 'active' : '' }}">Booking Overview</a>
        </div>

        @if($view === 'overview')
            <div class="row">
                <div class="col-md-6">
                    <div class="report-card">
                        <h5 class="fw-bold mb-4 text-secondary">Booking Status Distribution</h5>
                        <div style="height: 300px;"><canvas id="statusChart"></canvas></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <h5 class="fw-bold mb-4 text-secondary">Reward Program Usage</h5>
                        <div style="height: 300px;"><canvas id="rewardChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="report-card">
                <h5 class="fw-bold mb-4">Recent Bookings Activity</h5>
                <div class="row stats-header pb-2 px-2">
                    <div class="col-3">Booking ID</div>
                    <div class="col-3">Duration</div>
                    <div class="col-3 text-center">Status</div>
                    <div class="col-3 text-end">Total Price</div>
                </div>
                @foreach($recentBookings as $booking)
                <div class="row stats-row align-items-center px-2">
                    <div class="col-3 fw-bold">#{{ $booking->bookingID }}</div>
                    <div class="col-3 text-muted">{{ $booking->bookingDuration }} Days</div>
                    <div class="col-3 text-center">
                        <span class="badge rounded-pill {{ $booking->bookingStatus == 'approved' ? 'bg-success' : ($booking->bookingStatus == 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ ucfirst($booking->bookingStatus) }}
                        </span>
                    </div>
                    <div class="col-3 text-end fw-bold text-success">RM {{ number_format($booking->totalPrice, 2) }}</div>
                </div>
                @endforeach
            </div>

        @else
            <div class="report-card">
                <h5 class="fw-bold mb-4">{{ ucfirst($view) }} Income Chart</h5>
                <div style="height: 350px;"><canvas id="incomeChart"></canvas></div>
            </div>

            <div class="report-card">
                <div class="row stats-header pb-2 px-2">
                    <div class="col-4">{{ $view === 'daily' ? 'Day' : 'Month' }}</div>
                    <div class="col-4 text-center">No of Sales</div>
                    <div class="col-4 text-end">Total Amount</div>
                </div>
                @foreach($reportStats as $stat)
                <div class="row stats-row align-items-center px-2">
                    <div class="col-4 fw-bold">
                        {{ $view === 'daily' ? "Day $stat->day" : $stat->month_name }}
                    </div>
                    <div class="col-4 text-center text-muted">{{ $stat->sales_count }}</div>
                    <div class="col-4 text-end fw-bold text-success">RM {{ number_format($stat->total, 2) }}</div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if($view === 'overview')
            // Overview Charts
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($statusCounts->pluck('bookingStatus')),
                    datasets: [{
                        data: @json($statusCounts->pluck('count')),
                        backgroundColor: ['#ffc107', '#1a8f36', '#dc3545', '#6c757d'],
                        borderWidth: 0
                    }]
                },
                options: { cutout: '75%', plugins: { legend: { position: 'bottom' } } }
            });

            new Chart(document.getElementById('rewardChart'), {
                type: 'pie',
                data: {
                    labels: ['Reward Applied', 'No Reward'],
                    datasets: [{
                        data: [
                            {{ $rewardStats->where('rewardApplied', 1)->first()->count ?? 0 }},
                            {{ $rewardStats->where('rewardApplied', 0)->first()->count ?? 0 }}
                        ],
                        backgroundColor: ['#1a8f36', '#e9ecef'],
                        borderWidth: 0
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });
        @else
            // Income Charts
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
        @endif
    </script>
</body>
</html>
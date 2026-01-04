<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Customer Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        
        .main-content { margin-left: 250px; padding: 40px; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Updated Customer Card to match Vehicle style */
        .customer-card {
            background: #fff; 
            border-radius: 15px; 
            padding: 20px; 
            margin-bottom: 20px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; /* Pushes content to left/right */
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); 
            position: relative;
        }
        .customer-info { flex-grow: 1; }

        /* Square-rounded buttons matching Vehicle Management */
        .action-btn { 
            background: #f8f9fa; 
            border: none; 
            width: 40px;
            height: 40px;
            border-radius: 10px; 
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s; 
            cursor: pointer; 
            color: #333;
            text-decoration: none;
        }
        .action-btn:hover { background: #e9ecef; color: #000; }
        
        .tab-menu { border-bottom: 1px solid #eee; margin-bottom: 20px; padding-bottom: 5px; }
        .tab-link { padding: 10px 20px; text-decoration: none; color: #888; display: inline-block; transition: 0.3s; }
        .tab-link.active { color: #1a8f36; border-bottom: 3px solid #1a8f36; font-weight: 600; }
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
            @auth
                <span id="username">{{ Auth::user()->name }}</span>
            @endauth
        </div>
    </div>

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item"> Dashboard</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item"> Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item active">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item"> Promotions</a>
        <a href="#" class="nav-item"> Settings</a>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="header-flex mb-3">
            <h2>Customers</h2>
            <div class="d-flex align-items-center gap-3">
                <form action="{{ route('admin.customers') }}" method="GET" class="d-flex gap-2">
                    <select name="filter" class="form-select border-0 bg-light" style="border-radius: 20px; width: 150px; cursor: pointer;">
                        <option value="">All Filter</option>
                        <optgroup label="Faculty">
                            <option value="FKM" {{ request('filter') == 'FKM' ? 'selected' : '' }}>MECHANICAL ENGINEERING</option>
                            <option value="FS" {{ request('filter') == 'FS' ? 'selected' : '' }}>SCIENCE </option>
                            <option value="FM" {{ request('filter') == 'FM' ? 'selected' : '' }}>MANAGEMENT </option>
                            <option value="FKA" {{ request('filter') == 'FKA' ? 'selected' : '' }}>CIVIL ENGINEERING</option>
                            <option value="FC" {{ request('filter') == 'FC' ? 'selected' : '' }}>COMPUTING </option>
                            <option value="FKE" {{ request('filter') == 'FKE' ? 'selected' : '' }}>ELECTRICAL ENGINEERING</option>
                            <option value="FSSH" {{ request('filter') == 'FSSH' ? 'selected' : '' }}>SOCIAL SCIENCES AND HUMANITIES</option>
                            <option value="FKT" {{ request('filter') == 'FKT' ? 'selected' : '' }}>CHEMICAL AND ENERGY ENGINEERING</option>
                            <option value="FABU" {{ request('filter') == 'FABU' ? 'selected' : '' }}>BUILT ENVIRONMENT AND SURVEYING</option>
                            <option value="FEST" {{ request('filter') == 'FEST' ? 'selected' : '' }}>EDUCATIONAL SCIENCES AND TECHNOLOGY</option>
                        </optgroup>
                        <optgroup label="College">
                                <option value="KTR" {{ request('filter') == 'KTR' ? 'selected' : '' }}>KOLEJ TUN RAZAK (KTR)</option>
                                <option value="KTF" {{ request('filter') == 'KTF' ? 'selected' : '' }}>KOLEJ TUN FATIMAH (KTF)</option>
                                <option value="KRP" {{ request('filter') == 'KRP' ? 'selected' : '' }}>KOLEJ RAHMAN PUTRA (KRP)</option>
                                <option value="KTDI" {{ request('filter') == 'KTDI' ? 'selected' : '' }}>KOLEJ TUN DR. ISMAIL (KTDI)</option>
                                <option value="KTC" {{ request('filter') == 'KTC' ? 'selected' : '' }}>KOLEJ TUANKU CANSELOR (KTC)</option>
                                <option value="KTHO" {{ request('filter') == 'KTHO' ? 'selected' : '' }}>KOLEJ TUN HUSSEIN ONN (KTHO)</option>
                                <option value="KDSE" {{ request('filter') == 'KDSE' ? 'selected' : '' }}>MeKOLEJ DATIN SRI ENDON (KDSE)rbau</option>
                                <option value="K9/K10" {{ request('filter') == 'K9/K10' ? 'selected' : '' }}>KOLEJ 9/10</option>
                                <option value="KP" {{ request('filter') == 'KP' ? 'selected' : '' }}>KOLEJ PERDANA (KP)</option>
                                <option value="KDOJ" {{ request('filter') == 'KDOJ' ? 'selected' : '' }}>KOLEJ DATO’ ONN JAAFAR (KDOJ)</option>
                                <option value="KLG" {{ request('filter') == 'KLG' ? 'selected' : '' }}>KLG</option>
                                <option value="UTMI" {{ request('filter') == 'UTMI' ? 'selected' : '' }}>UTM International</option>
                                <option value="Outside UTM" {{ request('filter') == 'Outside UTM' ? 'selected' : '' }}>None</option>
                        </optgroup>
                    </select>
                    <button type="submit" class="btn btn-success px-4" style="background-color: #1a8f36; border-radius: 20px; border: none;">Submit</button>
                </form>
            </div>
        </div>

        <div class="tab-menu mb-4">
            <a href="?status=active" class="tab-link {{ $status == 'active' ? 'active' : '' }}">Active</a>
            <a href="?status=blacklisted" class="tab-link {{ $status == 'blacklisted' ? 'active' : '' }}">Blacklisted</a>
            <span class="float-end text-muted mt-2">Total <b>{{ $totalCount }}</b></span>
        </div>

        @foreach($customers as $customer)
            <div class="customer-card">
                <div class="customer-info">
                    <h5 class="mb-0">{{ strtoupper($customer->name) }}</h5>
                    <div class="text-muted small mt-3">
                        <p class="mb-1">Email: {{ $customer->email }}</p>
                        <p class="mb-0">Phone: {{ $customer->phoneNumber ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    @if($customer->isBlacklisted)
                        <span class="badge" style="background-color: #fee2e2; color: #dc2626; padding: 8px 20px; border-radius: 20px; font-weight: 500;">Blacklisted</span>
                    @else
                        <span class="badge" style="background-color: #dcfce7; color: #1a8f36; padding: 8px 20px; border-radius: 20px; font-weight: 500;">Active</span>
                    @endif

                    <div class="d-flex gap-2">
                        <button class="action-btn" data-bs-toggle="modal" data-bs-target="#profileModal{{ $customer->userID }}">👤</button>
                        <button class="action-btn" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->userID }}">📝</button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="profileModal{{ $customer->userID }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 20px; border: none;">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">Customer Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="p-3 mb-4 bg-light rounded-4">
                                <h6 class="text-muted small fw-bold">Outstanding Payment</h6>
                                <h2 class="text-danger fw-bold mb-0">
                                    RM {{ number_format($customer->outstanding_payment ?? 0, 2) }}
                                </h2>
                            </div>
                            <div class="text-start">
                                <p class="mb-2"><strong>Email:</strong> {{ $customer->email }}</p>
                                <p class="mb-2"><strong>Matric Number:</strong> {{ $customer->matricNumber ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>License:</strong> {{ $customer->licenseNumber ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>Faculty:</strong> {{ strtoupper($customer->faculty ?? 'N/A') }}</p>
                                <p class="mb-0"><strong>College:</strong> {{ $customer->college ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Emergency Contact:</strong> {{ $customer->emergency_contact_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editModal{{ $customer->userID }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('admin.customers.update', $customer->userID) }}" method="POST" class="modal-content" style="border-radius: 20px; border: none;">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">Update Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Account Status</label>
                                <select name="isBlacklisted" class="form-select" style="border-radius: 12px; padding: 12px;">
                                    <option value="0" {{ !$customer->isBlacklisted ? 'selected' : '' }}>Active</option>
                                    <option value="1" {{ $customer->isBlacklisted ? 'selected' : '' }}>Blacklist User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reason for Blacklist</label>
                                <textarea name="blacklistReason" class="form-control" rows="3" style="border-radius: 12px;">{{ $customer->blacklistReason ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="submit" class="btn btn-success w-100" style="background-color: #1a8f36; border-radius: 12px; padding: 12px; font-weight: 600;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
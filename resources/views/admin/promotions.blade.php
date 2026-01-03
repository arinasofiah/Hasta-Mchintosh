<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Promotions</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .main-content { margin-left: 250px; padding: 40px; }

        .promo-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .promo-title { font-weight: 600; font-size: 1.1rem; margin-bottom: 5px; }
        .promo-desc { font-size: 0.9rem; color: #666; margin-bottom: 2px; }
        .promo-days { font-size: 0.8rem; color: #999; }
        
        .add-btn { background-color: #008000; color: white; border: none; padding: 8px 25px; border-radius: 20px; font-weight: 600; }
        .apply-btn { background-color: #90ee90; color: #2e7d32; border: none; padding: 8px 30px; border-radius: 20px; font-weight: 500; }
        
        .arrow-down {
            width: 0; height: 0; 
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #333;
            margin: 0 20px;
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
            @auth
                <span id="username">{{ Auth::user()->name }}</span>
            @endauth
        </div>
    </div>

    <div class="sidebar">
        <h5 class="mb-4">Menu</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item active">Promotions</a>
        <a href="#" class="nav-item">Settings</a>
    </div>

    <div class="main-content">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Promotion</h3>
            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addPromoModal">+ Add</button>
        </div>
        
        <hr class="text-muted mb-4">

        @forelse($promotions as $promo)
            <div class="promo-card">
                <div style="width: 300px;">
                    <div class="promo-title">{{ $promo->title }} <small class="text-muted">({{ $promo->code }})</small></div>
                    <div class="promo-desc">
                        @if($promo->discountType == 'percentage')
                            {{ intval($promo->discountValue) }}%
                        @else
                            RM{{ intval($promo->discountValue) }}
                        @endif
                        {{ $promo->description }}
                    </div>
                    <div class="promo-days">{{ $promo->applicableDays }} days duration</div>
                </div>

                <div class="d-flex align-items-center justify-content-center flex-grow-1">
                    <span class="fw-medium">{{ $promo->applicableModel }}</span>
                    <div class="arrow-down"></div>
                </div>

                <div>
                    <form action="{{ route('admin.promotions.destroy', $promo->promoID) }}" method="POST" onsubmit="return confirm('Delete this promo?')">
                        @csrf
                        @method('DELETE')
                        <button class="apply-btn" style="cursor: pointer;">Remove</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-center text-muted mt-5">No active promotions found.</p>
        @endforelse

    </div>

    <div class="modal fade" id="addPromoModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.promotions.store') }}" method="POST" class="modal-content" style="border-radius: 15px;">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add New Promotion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Promo Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. PROMO2024" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Voucher1" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Type</label>
                            <select name="discountType" class="form-select">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (RM)</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Value</label>
                            <input type="number" name="discountValue" class="form-control" placeholder="10" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. Free Burger">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (Days)</label>
                        <input type="number" name="applicableDays" class="form-control" placeholder="e.g. 2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Applicable Car Model</label>
                        <select name="applicableModel" class="form-select">
                            <option value="All">All</option>
                            @foreach($vehicleModels as $v)
                                <option value="{{ $v->model }}">{{ $v->model }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success w-100" style="border-radius: 20px; background-color: #008000;">Save Promotion</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
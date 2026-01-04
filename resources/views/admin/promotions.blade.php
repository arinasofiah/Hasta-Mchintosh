<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta - Campaign Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; padding: 20px; border-right: 1px solid #eee; }
        .nav-item { padding: 12px 15px; border-radius: 8px; color: #333; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; }
        .nav-item.active { background-color: #fff5f5; color: #bc3737; font-weight: 600; border-right: 4px solid #bc3737; }
        .main-content { margin-left: 250px; padding: 40px; }

        /* Tabs & Cards */
        .nav-tabs { border-bottom: 2px solid #eee; margin-bottom: 30px; }
        .nav-tabs .nav-link { border: none; color: #999; font-weight: 500; padding: 15px 25px; font-size: 1.1rem; }
        .nav-tabs .nav-link.active { color: #bc3737; border-bottom: 3px solid #bc3737; background: transparent; }
        
        .item-card {
            background: #fff; border-radius: 15px; padding: 25px; margin-bottom: 20px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        
        .badge-custom { padding: 5px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: 500; }
        .badge-promo { background-color: #e3f2fd; color: #1565c0; }
        .badge-voucher { background-color: #fff3e0; color: #ef6c00; }

        .add-btn { background-color: #bc3737; color: white; border: none; padding: 10px 25px; border-radius: 30px; font-weight: 600; }
        .action-btn { border: none; background: #f1f1f1; color: #333; padding: 8px 20px; border-radius: 20px; font-size: 0.9rem; }
        .btn-pay { background-color: #d4edda; color: #155724; }
        
        .table-custom th { font-weight: 500; color: #666; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        .table-custom td { padding: 20px 0; vertical-align: middle; border-bottom: 1px solid #f5f5f5; }
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
        <h5 class="mb-4 ps-2 fw-bold" style="color: #bc3737;">Admin Panel</h5>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('admin.reporting') }}" class="nav-item">Reporting</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item">Fleet</a> 
        <a href="{{ route('admin.customers') }}" class="nav-item">Customer</a>
        <a href="{{ route('admin.staff') }}" class="nav-item">Staff</a>
        <a href="{{ route('admin.promotions') }}" class="nav-item active">Campaigns</a>
    </div>

    <div class="main-content">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <h3 class="fw-bold mb-4">Campaign Manager</h3>

        <ul class="nav nav-tabs" id="campaignTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="promo-tab" data-bs-toggle="tab" data-bs-target="#promo-pane">Promotions</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="voucher-tab" data-bs-toggle="tab" data-bs-target="#voucher-pane">Vouchers</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission-pane">Commissions</button>
            </li>
        </ul>

        <div class="tab-content">
            
            <div class="tab-pane fade show active" id="promo-pane">
                <div class="d-flex justify-content-end mb-3">
                    <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addPromoModal">+ New Promo</button>
                </div>
                @forelse($promotions as $promo)
                    <div class="item-card">
                        <div>
                            <div class="fw-bold fs-5">{{ $promo->title }} <span class="badge badge-custom badge-promo">{{ $promo->code }}</span></div>
                            <div class="text-muted small">
                                {{ $promo->discountType == 'percentage' ? intval($promo->discountValue).'%' : 'RM'.$promo->discountValue }} OFF 
                                • {{ $promo->applicableDays }} Days
                            </div>
                        </div>
                        <form action="{{ route('admin.promotions.destroy', $promo->promoID) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="action-btn text-danger">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-muted mt-5">No active promotions.</p>
                @endforelse
            </div>

            <div class="tab-pane fade" id="voucher-pane">
                <div class="d-flex justify-content-end mb-3">
                    <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addVoucherModal">+ New Voucher</button>
                </div>
                @forelse($vouchers as $v)
                    <div class="item-card">
                        <div>
                            <div class="fw-bold fs-5">
                                Voucher RM{{ number_format($v->value, 0) }}
                                <span class="badge badge-custom badge-voucher">ID: {{ $v->voucherCode }}</span>
                            </div>
                            <div class="text-muted small">
                                Expires: {{ date('d M Y', $v->expiryTime) }}
                            </div>
                        </div>
                        <form action="{{ route('admin.vouchers.destroy', $v->voucherCode) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="action-btn text-danger">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-muted mt-5">No active vouchers.</p>
                @endforelse
            </div>

            <div class="tab-pane fade" id="commission-pane">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Bank Details</th>
                                <th>Commission Count</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffMembers as $s)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $s->name }}</div>
                                        <div class="small text-muted">{{ $s->email }}</div>
                                    </td>
                                    <td>
                                        @if($s->bank_name)
                                            <div class="small fw-bold">{{ $s->bank_name }}</div>
                                            <div class="small text-muted">{{ $s->bank_account_number }}</div>
                                        @else
                                            <span class="badge bg-light text-dark">Not Set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <h4 class="mb-0 text-success fw-bold">{{ $s->commissionCount }}</h4>
                                    </td>
                                    <td class="text-end">
                                        @if($s->commissionCount > 0)
                                            <form action="{{ route('admin.commission.reset', $s->userID) }}" method="POST" onsubmit="return confirm('Confirm payment? Commission count will reset to 0.')">
                                                @csrf
                                                <button class="action-btn btn-pay">Mark Paid</button>
                                            </form>
                                        @else
                                            <button class="action-btn" disabled style="opacity:0.5;">No Due</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No staff found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div> </div>

    <div class="modal fade" id="addPromoModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.promotions.store') }}" method="POST" class="modal-content" style="border-radius: 15px;">
                @csrf
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Add Promotion</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="text" name="code" class="form-control mb-3" placeholder="Promo Code (e.g. SAVE10)" required>
                    <input type="text" name="title" class="form-control mb-3" placeholder="Title" required>
                    <div class="row mb-3">
                        <div class="col-6"><select name="discountType" class="form-select"><option value="percentage">%</option><option value="fixed">RM</option></select></div>
                        <div class="col-6"><input type="number" name="discountValue" class="form-control" placeholder="Value" required></div>
                    </div>
                    <input type="number" name="applicableDays" class="form-control mb-3" placeholder="Duration (Days)" required>
                    <select name="applicableModel" class="form-select"><option value="All">All Models</option>@foreach($vehicleModels as $v)<option value="{{ $v->model }}">{{ $v->model }}</option>@endforeach</select>
                </div>
                <div class="modal-footer border-0"><button class="btn btn-success w-100" style="border-radius: 20px; background-color: #bc3737; border:none;">Save</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addVoucherModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.vouchers.store') }}" method="POST" class="modal-content" style="border-radius: 15px;">
                @csrf
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Add Voucher</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="alert alert-light border small text-muted">
                        <i class="bi bi-info-circle"></i> Voucher ID will be generated automatically.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Value (RM)</label>
                        <input type="number" name="value" class="form-control" placeholder="e.g. 50" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiryDate" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0"><button class="btn btn-success w-100" style="border-radius: 20px; background-color: #e67e22; border:none;">Create Voucher</button></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
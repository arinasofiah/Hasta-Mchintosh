<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hasta Travel & Tour</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-profile.css') }}" rel="stylesheet">
    <style>
        .commission-section {margin-top:30px;padding-top:30px;border-top:2px solid #eee}
        .commission-card {background:#fff;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.08);padding:25px;margin-bottom:25px;border:1px solid #eaeaea}
        .commission-header {display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:15px;border-bottom:1px solid #eee}
        .commission-title {color:#333;font-size:18px;font-weight:700;margin:0;display:flex;align-items:center;gap:10px}
        .commission-title i {color:#bc3737}
        .commission-summary {display:flex;justify-content:space-between;gap:15px;margin-bottom:25px;overflow-x:auto;padding-bottom:10px}
        .commission-box {flex:1;min-width:180px;background:linear-gradient(135deg,#fff5f5 0%,#fef0f0 100%);border:2px solid rgba(188,55,55,0.1);border-radius:10px;padding:20px;text-align:center}
        .commission-count {color:#bc3737;font-size:28px;font-weight:700;margin:10px 0}
        .commission-label {color:#666;font-size:14px;text-transform:uppercase;letter-spacing:0.5px}
        .commission-form {background:#fafafa;border-radius:10px;padding:25px;border:1px solid #eee;margin-top:20px}
        .form-group {margin-bottom:20px}
        .form-label {font-weight:600;color:#444;margin-bottom:8px;display:block}
        .form-control {width:100%;padding:12px 15px;border:1px solid #ddd;border-radius:8px;font-size:14px;transition:all 0.3s}
        .form-control:focus {outline:none;border-color:#bc3737;box-shadow:0 0 0 3px rgba(188,55,55,0.1)}
        textarea.form-control {min-height:100px;resize:vertical}
        .commission-history {margin-top:30px}
        .history-table {width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.05)}
        .history-table th {background:#f8f9fa;padding:15px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #eee}
        .history-table td {padding:15px;border-bottom:1px solid #f0f0f0}
        .history-table tr:hover {background-color:#f9f9f9}
        .commission-type-badge {display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;background:#e8f5e9;color:#2e7d32}
        .type-booking {background:#e3f2fd;color:#1565c0}
        .type-referral {background:#f3e5f5;color:#7b1fa2}
        .type-upsell {background:#fff3e0;color:#ef6c00}
        .type-special {background:#e8f5e9;color:#2e7d32}
        .type-corporate {background:#e0f2f1;color:#00695c}
        .type-group {background:#fce4ec;color:#c2185b}
        .type-other {background:#f5f5f5;color:#616161}
        .no-commission {text-align:center;padding:40px 20px;color:#999}
        .no-commission i {font-size:48px;margin-bottom:15px;color:#ddd}
        .activity-indicator {display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:8px}
        .indicator-recent {background:#4caf50;animation:pulse 2s infinite}
        .indicator-old {background:#9e9e9e}
        @keyframes pulse {0%{opacity:0.7}50%{opacity:1}100%{opacity:0.7}}
        
        /* Updated Profile Header Styles */
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .profile-title-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .profile-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .edit-profile-btn {
            background: linear-gradient(135deg, #bc3737 0%, #a52a2a 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(188, 55, 55, 0.2);
        }
        
        .edit-profile-btn:hover {
            background: linear-gradient(135deg, #a52a2a 0%, #8b0000 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(188, 55, 55, 0.3);
            color: white;
        }
        
        .edit-profile-btn:active {
            transform: translateY(0);
        }
        
        @media (max-width:768px){.commission-summary{flex-wrap:wrap}.commission-box{min-width:150px}.commission-count{font-size:24px}.history-table{font-size:14px}.history-table th,.history-table td{padding:10px}
            .profile-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        @media (max-width:576px){.history-table{display:block;overflow-x:auto}.commission-title{font-size:16px}.commission-form{padding:15px}.commission-summary{gap:10px}.commission-box{min-width:140px}
            .profile-title-wrapper {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .edit-profile-btn {
                width: 100%;
                justify-content: center;
            }
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
                 @if(Auth::user()->userType === 'staff')
                          <a href="{{ route('admin.profile') }}" class="dropdown-item">My Profile</a>
                        @endif
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

<div class="content-with-sidebar">

    <div class="profile-page">
        <!-- Updated Profile Header -->
        <div class="profile-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2 class="profile-title" style="margin: 0;">My Profile</h2>
    <a href="{{ route('admin.edit-profile') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-user-edit"></i> Edit Profile
    </a>
</div>
        
        <div class="profile-content">
            {{-- Personal Information Card --}}
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-user-circle"></i>
                    Personal Information
                </div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">IC Number:</span>
                    <span class="info-value">{{ $user->icNumber ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $user->phoneNumber ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Member Since:</span>
                    <span class="info-value">{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</span>
                </div>
            </div>
            
            {{-- Account Information Card --}}
            <div class="info-card account-card">
                <div class="card-title">
                    <i class="fas fa-shield-alt"></i>
                    Account Information
                </div>

                <div class="info-row">
                    <span class="info-label">User Type:</span>
                    <span class="info-value">{{ ucfirst($user->userType) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Position:</span>
                    <span class="info-value">{{ $user->staff->position ?? 'Not set' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Account Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-active">Active</span>
                    </span>
                </div>
            
            </div>
            
            {{-- Commission Management Section --}}
            @if(Auth::user()->userType === 'staff')
            <div class="commission-section" style="grid-column: span 2;">
                <div class="commission-card">
                    <div class="commission-header">
                        <h3 class="commission-title">
                            <i class="fas fa-money-check-alt"></i>
                            My Commissions
                        </h3>
                        <div class="commission-summary">
                            <div class="commission-box">
                                <div class="commission-label">Total Commissions</div>
                                <div class="commission-count">{{ $totalCommissions ?? 0 }}</div>
                                <small class="text-muted">All Time</small>
                            </div>
                            <div class="commission-box">
                                <div class="commission-label">This Month</div>
                                <div class="commission-count">{{ $monthlyCommissions ?? 0 }}</div>
                                <small class="text-muted">{{ date('F Y') }}</small>
                            </div>
                            <div class="commission-box">
                                <div class="commission-label">Last Commission</div>
                                <div class="commission-count">
                                    @if($latestCommissionDate)
                                        {{ \Carbon\Carbon::parse($latestCommissionDate)->format('d M') }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <small class="text-muted">Date Recorded</small>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Add New Commission Form --}}
                    <div class="commission-form">
                        <h4 style="color:#bc3737;margin-bottom:20px;font-size:16px">
                            <i class="fas fa-plus-circle"></i> Record New Commission
                        </h4>
                        <form method="POST" action="{{ route('staff.commission.add') }}" id="commissionForm">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Commission Type *</label>
                                <input type="text" name="commissionType" class="form-control" 
                                       placeholder="Enter commission type (e.g., Booking, Referral, Upsell, etc.)"
                                       required>
                                <small class="text-muted">Enter any commission type you want</small>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Commission Date *</label>
                                <input type="date" name="commissionDate" class="form-control" 
                                       value="{{ date('Y-m-d') }}" 
                                       max="{{ date('Y-m-d') }}"
                                       required>
                                <small class="text-muted">Date when the commission was earned</small>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Additional Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="3" 
                                          placeholder="Add any additional details about this commission..."></textarea>
                                <small class="text-muted">e.g., Customer name, booking reference, or special circumstances</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width:100%;padding:14px;font-size:16px;font-weight:600">
                                <i class="fas fa-save"></i> Save Commission Record
                            </button>
                        </form>
                    </div>
                    
                    {{-- Commission History --}}
                    <div class="commission-history">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
                            <h4 style="color:#333;font-size:16px;display:flex;align-items:center;gap:10px">
                                <i class="fas fa-history"></i> Commission History
                            </h4>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('all')">All Types</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('booking')">Booking</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('referral')">Referral</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('upsell')">Upsell</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('special')">Special Package</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('corporate')">Corporate</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('group')">Group</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCommissions('other')">Other</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        @if($commissions && $commissions->count() > 0)
                        <div class="table-responsive">
                            <table class="history-table" id="commissionTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Notes</th>
                                        <th>Recorded On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commissions as $commission)
                                    <tr data-type="{{ strtolower($commission->commissionType) }}">
                                        <td>
                                            <span class="activity-indicator {{ \Carbon\Carbon::parse($commission->commissionDate)->isToday() ? 'indicator-recent' : 'indicator-old' }}"></span>
                                            {{ \Carbon\Carbon::parse($commission->commissionDate)->format('d M Y') }}
                                            @if(\Carbon\Carbon::parse($commission->commissionDate)->isToday())
                                                <br><small class="text-success">Today</small>
                                            @elseif(\Carbon\Carbon::parse($commission->commissionDate)->isYesterday())
                                                <br><small class="text-muted">Yesterday</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="commission-type-badge">
                                                {{ ucfirst($commission->commissionType) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($commission->notes)
                                                <div style="max-width:250px;word-wrap:break-word">
                                                    {{ $commission->notes }}
                                                </div>
                                            @else
                                                <span class="text-muted">No notes</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($commission->created_at)->format('d M Y') }}
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($commission->created_at)->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="editCommission({{ $commission->id }})"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteCommission({{ $commission->id }})"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="no-commission">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <h4>No commissions recorded yet</h4>
                            <p>Add your first commission record using the form above.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Edit Commission Modal -->
<div class="modal fade" id="editCommissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Commission Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCommissionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Commission Type</label>
                        <input type="text" name="commissionType" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Commission Date</label>
                        <input type="date" name="commissionDate" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effect to cards
        const cards = document.querySelectorAll('.info-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
                this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });
        
        // Add click effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(btn => {
            btn.addEventListener('mousedown', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            btn.addEventListener('mouseup', function() {
                this.style.transform = '';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
        
        // Profile dropdown toggle
        const profileContainer = document.getElementById('profile-container');
        const profileDropdown = document.getElementById('profile-dropdown');
        
        if (profileContainer && profileDropdown) {
            profileContainer.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
            });
            
            // Close dropdown when clicking elsewhere
            document.addEventListener('click', function() {
                profileDropdown.style.display = 'none';
            });
        }
        
        // Commission form validation
        const commissionForm = document.getElementById('commissionForm');
        if (commissionForm) {
            commissionForm.addEventListener('submit', function(e) {
                const commissionDate = new Date(this.querySelector('input[name="commissionDate"]').value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (commissionDate > today) {
                    e.preventDefault();
                    alert('Commission date cannot be in the future.');
                    return false;
                }
                
                if (confirm('Add this commission record?')) {
                    return true;
                } else {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
    
    function filterCommissions(type) {
        const rows = document.querySelectorAll('#commissionTable tbody tr');
        rows.forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else {
                const rowType = row.dataset.type.toLowerCase();
                if (rowType === type.toLowerCase()) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
        
        // Update dropdown text
        const dropdownBtn = document.querySelector('.dropdown-toggle');
        const typeNames = {
            'all': 'All Types',
            'booking': 'Booking',
            'referral': 'Referral',
            'upsell': 'Upsell',
            'special': 'Special Package',
            'corporate': 'Corporate',
            'group': 'Group',
            'other': 'Other'
        };
        dropdownBtn.innerHTML = `<i class="fas fa-filter"></i> ${typeNames[type] || type}`;
    }
    
    function editCommission(commissionId) {
        // Fetch commission data
        fetch(`/staff/commission/${commissionId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const form = document.getElementById('editCommissionForm');
                    form.action = `/staff/commission/${commissionId}`;
                    
                    // Set form values
                    form.querySelector('[name="commissionType"]').value = data.commission.commissionType;
                    form.querySelector('[name="commissionDate"]').value = data.commission.commissionDate;
                    form.querySelector('[name="notes"]').value = data.commission.notes || '';
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editCommissionModal'));
                    modal.show();
                }
            })
            .catch(error => {
                alert('Error loading commission data');
            });
    }
    
    function deleteCommission(commissionId) {
        if (confirm('Are you sure you want to delete this commission record?')) {
            fetch(`/staff/commission/${commissionId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Commission record deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
            });
        }
    }
    
    // Set max date for commission date input
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.max = today;
        });
    });
</script>
</body>
</html>
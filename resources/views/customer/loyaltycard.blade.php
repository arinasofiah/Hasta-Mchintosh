<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loyalty Card - Hasta Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet"> 
    
    <style>
        .loyalty-card-box {
            background: linear-gradient(135deg, #bc3737 0%, #8a2424 100%);
            border-radius: 20px;
            padding: 30px;
            color: white;
            box-shadow: 0 10px 20px rgba(188, 55, 55, 0.3);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .card-header-text {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stamp-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .stamp-circle {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            border: 2px dashed rgba(255, 255, 255, 0.5);
            position: relative;
        }

        .stamp-circle.active {
            background-color: white;
            color: #bc3737;
            border: 2px solid white;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.8);
        }

        .stamp-circle.special-stamp {
            border-color: #ffd700;
            color: #ffd700; 
            background-color: rgba(255, 215, 0, 0.1);
        }
        
        .stamp-circle.special-stamp.active {
            background: linear-gradient(45deg, #ffd700, #ffecb3); 
            color: #8a2424;
            border: 2px solid #fff;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            transform: scale(1.1);
        }
        .voucher-item {
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
            border-left: 5px solid #28a745;
        }

        .voucher-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .voucher-item.used {
            border-left-color: #6c757d; 
            background-color: #f8f9fa;
            opacity: 0.7;
        }

        .code-box {
            background: #f1f3f5;
            padding: 5px 12px;
            border-radius: 6px;
            font-family: monospace;
            font-weight: bold;
            letter-spacing: 1px;
            color: #333;
        }
    </style>
</head>
<body class="has-scrollable-content">
    
    <div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
        <div id="menu">
            <button class="head_button" onclick="window.location.href='{{ route('customer.dashboard') }}'">Home</button>
            <button class="head_button">Vehicles</button>
        </div>
        <div id="profile">
            <div id="profile-container">
                <img id="pfp" src="{{ asset('img/racc_icon.png') }}">
                <div id="profile-dropdown">
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
            @auth <span id="username">{{ Auth::user()->name }}</span> @endauth
        </div>
    </div>

    <div class="content-with-sidebar">
        
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('customer.profile') }}">My Profile</a></li>
                <li><a href="{{ route('bookingHistory') }}">My Bookings</a></li>
                <li><a href="{{ route('customer.loyaltycard') }}" class="active">Loyalty Card</a></li>
                <li> <a href="{{ route('customer.profile.edit') }}">Edit Profile </a></li>
            </ul>
        </div>

        <div class="profile-page">
            <h2 class="profile-title">My Loyalty Program</h2>
            
            <div class="loyalty-card-box">
                <div class="card-header-text">
                    <div>
                        <h3 style="margin:0;">Hasta Rewards</h3>
                        <small>Matric: {{ $card->matricNumber }}</small>
                    </div>
                    <div style="text-align: right;">
                        <h1 style="margin:0; font-size: 3rem;">{{ $card->stampCount }}</h1>
                        <small>Total Stamps</small>
                    </div>
                </div>

                <p style="font-size: 1.1rem;">
                    🎁 <strong>Every stamp</strong> gets a <span style="text-decoration: underline;">RM10 Voucher</span>! <br>
                    🌟 Collect <strong>5 stamps</strong> for a <strong style="color: #ffd700;">SPECIAL REWARD</strong>!
                </p>

                <div class="stamp-container">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="stamp-circle 
                            {{ $i == 5 ? 'special-stamp' : '' }} 
                            {{ ($card->stampCount % 5 >= $i || ($card->stampCount > 0 && $card->stampCount % 5 == 0)) ? 'active' : '' }}">
                            
                            @if($i == 5)
                                ★
                            @else
                                {{ $i }}
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <h3 class="mt-5 mb-3">My Vouchers</h3>

            @forelse($vouchers as $promo)
                <div class="voucher-item {{ $promo->pivot->is_used ? 'used' : '' }}">
                    <div>
                        <h5 class="mb-1" style="font-weight: 600;">{{ $promo->title }}</h5>
                        <div class="text-muted small">
                            {{ $promo->description }} • 
                            @if($promo->discountType == 'percentage')
                                {{ intval($promo->discountValue) }}% OFF
                            @else
                                RM{{ intval($promo->discountValue) }} OFF
                            @endif
                        </div>
                        <div class="text-muted small mt-1">
                            Received: {{ $promo->pivot->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="code-box">{{ $promo->code }}</div>
                        <div style="font-size: 12px; margin-top: 5px; font-weight: 600; color: {{ $promo->pivot->is_used ? '#6c757d' : '#28a745' }}">
                            {{ $promo->pivot->is_used ? 'REDEEMED' : 'ACTIVE' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-5 text-muted" style="background: #f8f9fa; border-radius: 10px;">
                    <h4>Start Your Journey! 🚗</h4>
                    <p>Rent for 9+ hours to get your first <strong>RM10 Voucher</strong> instantly!</p>
                </div>
            @endforelse

        </div>
    </div>

</body>
</html>
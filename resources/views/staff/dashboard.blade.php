<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Admin - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
     <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    
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
    
    <nav class="d-flex flex-column">
        <a href="{{ route('admin.dashboard') }}" class="nav-item active"> Dashboard</a>
        <a href="#" class="nav-item"> Bookings</a>
        <a href="{{ route('admin.fleet') }}" class="nav-item"> Fleet</a> 
        <a href="#" class="nav-item"> Promotions</a>
        <a href="#" class="nav-item"> Settings</a>
    </nav>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Staff Dashboard</h2>


</body>
</html>
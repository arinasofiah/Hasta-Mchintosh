<div id="header">
    <a href="{{ url('/') }}">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}" alt="Logo">
    </a>

    <div id="menu">
        <a href="{{ url('/') }}"><button class="head_button">Home</button></a>
        <a href="{{ route('vehicles.index') }}"><button class="head_button">Vehicles</button></a>
        
        {{-- Example of a link only for logged in users (e.g. My Bookings) --}}
        @auth
        <a href="{{ url('/customer/customer/bookings') }}">
        <button class="head_button">My Bookings</button>
        </a>
        @endauth

        <button class="head_button">About Us</button>
        <button class="head_button">Contact Us</button>
    </div>

    <div id="profile">
        {{-- Profile Dropdown: Only show functionality if Auth, or simple links if Guest --}}
        <div id="profile-container">
            {{-- Default icon or User Avatar --}}
            <img id="pfp" src="{{ asset('img/racc_icon.png') }}" alt="Profile">

            <div id="profile-dropdown">
                @guest
                    {{-- Guest Links --}}
                    <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                    <a href="{{ route('register') }}" class="dropdown-item">Register</a>
                @endguest

                @auth
                    {{-- Authenticated Links --}}
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; color: #d94242; font-weight: bold;">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        {{-- Username Toggle --}}
        @guest
            <a id="username" href="{{ route('login') }}">Log in</a>
        @endguest

        @auth
            <span id="username">{{ Auth::user()->name }}</span>
        @endauth
    </div>
</div>
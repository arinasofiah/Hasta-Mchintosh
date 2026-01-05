<div id="header">
    <a href="{{ url('/') }}">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}" alt="Logo">
    </a>

    <div id="menu">
        <a href="{{ url('/') }}"><button class="head_button">Home</button></a>
        <a href="{{ route('vehicles.index') }}"><button class="head_button">Vehicles</button></a>
        <a href="{{ url('/details') }}"><button class="head_button">Details</button></a>
        <!--<a href="{{ url('/about') }}"><button class="head_button">About Us</button></a>
        <a href="{{ url('/contact') }}"><button class="head_button">Contact Us</button></a>-->
    </div>

    <div id="profile">
        <div id="profile-container">
            <img id="pfp" src="{{ asset('img/racc_icon.png') }}">

            <div id="profile-dropdown">
                @guest
                    <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                    <a href="{{ route('register') }}" class="dropdown-item">Register</a>
                @endguest

                @auth
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer;">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        @guest
            <a id="username" href="{{ route('login') }}">Log in</a>
        @endguest

        @auth
            <span id="username">{{ Auth::user()->name }}</span>
        @endauth
    </div>
</div>
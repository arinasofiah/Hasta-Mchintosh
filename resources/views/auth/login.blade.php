<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Login</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="left-section">
            <div class="logo"></div>
            <h1 class="headline">
                For You,<br>
                From Us
            </h1>
            <p class="sub-headline">Book your preference car now !</p>
            <div class="car-visuals">
                <img src="{{ asset('images/redcar.png') }}" alt="Red Car" class="car-image">
            </div>
        </div>

        <div class="right-section">
            <div class="login-card">
                
                <h3 class="login-title">Login</h3>
                <p class="credential-prompt">Enter your Credentials to access your account</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="options-row">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember for 30 days
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                Forget password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="login-button">
                        Login
                    </button>
                </form>

                <div class="no-account">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="sign-up-link">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Hasta Travel Register</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
</head>
<body>

<div class="container">
    <div class="left">
        <img src="{{ asset('img/hasta_logo.jpg') }}" width="120">
        <h1>For You,<br>From Us</h1>
        <p>Become our member to start booking now</p>
    </div>

    <div class="right">
        <div class="card">
            <h2>Welcome to Hasta Travel!</h2>

            @if ($errors->any())
                <div style="color: red; margin-bottom: 10px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/register">
                @csrf

                <label>Email address</label>
                <input type="email" name="email" required>

                <label>Full Name</label>
                <input type="text" id="fullName" name="name" required>

                <label>Phone Number</label>
                <input type="tel" name="phone" required>

                <label>National Identification Card</label>
                <input type="text" id="icNumber" name="icNumber" required>

                <label>Upload National IC (For Autofill)</label>
                <input type="file" id="icUpload" accept="image/*">

                <small class="hint" id="statusMsg">
                    Upload your IC to autofill your name and ID number.
                </small>

                <div class="mt-4">
                    <x-input-label for="matricNumber" :value="__('Matric Number')" />
                    <x-text-input id="matricNumber" class="block mt-1 w-full" 
                                  type="text" 
                                  name="matricNumber" 
                                  :value="old('matricNumber')" 
                                  required 
                                  placeholder="e.g. A21CS0001" />
                    <x-input-error :messages="$errors->get('matricNumber')" class="mt-2" />
                </div>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>Enter Password Again</label>
                <input type="password" name="password_confirmation" required>

                <button type="submit" id="submitBtn" disabled>Sign Up</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/ocr.js') }}"></script>
</body>
</html>



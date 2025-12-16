<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour | Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            background-color: #f0f2f5;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
            background: white;
            overflow: hidden;
        }

        .left-form {
            flex: 1;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        p.subtitle {
            font-size: 0.9rem;
            margin-bottom: 25px;
            color: #444;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f9f9f9;
        }

        input:focus {
            border-color: #3f663e;
            background: #fff;
            outline: none;
        }

        .email-wrapper {
            position: relative;
        }

        .verify-link {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.85rem;
            font-weight: 700;
            color: #003399;
            text-decoration: none;
        }

        .upload-box {
            position: relative;
            margin-bottom: 12px;
        }

        .upload-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .btn-green {
            width: 100%;
            padding: 12px;
            background: #3f663e;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-green:hover {
            background: #2f4d2e;
        }

        .spacer {
            height: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .right-image {
            flex: 1.3;
            background-image: url('{{ asset('img/hastawelcome.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 80%;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }
            .right-image {
                height: 300px;
                order: -1;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="left-form">
        <h1>Welcome to Hasta Travel</h1>
        <p class="subtitle">Kindly enter your personal information before renting a vehicle</p>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div class="input-group">
                <label>Email address</label>
                <div class="email-wrapper">
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="input-group">
                <label>Matric / Staff Number</label>
                <input type="text" name="matric_number" required>
            </div>

            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" required>
            </div>

            <label style="margin-top:20px;font-weight:600;">
                Upload documents for verification
            </label>

            <div class="upload-box">
                <label>National Identification Card</label>
                <input type="file" name="ic_document" required>
            </div>

            <div class="upload-box">
                <label>Driver's License</label>
                <input type="file" name="license_document" required>
            </div>

            <div class="upload-box">
                <label>Matric / Staff Card</label>
                <input type="file" name="student_card_document" required>
            </div>

            <button class="btn-green">Verify</button>

            <div class="spacer"></div>

            <h2 class="section-title">Set Your Password</h2>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn-green">Create Account</button>

            <div style="height:30px;"></div>
        </form>
    </div>

    <div class="right-image"></div>
</div>

</body>
</html>

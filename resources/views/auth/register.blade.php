<!DOCTYPE html>
<html>
<head>
  <title>Hasta Travel Register</title>
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>

<div class="container">

  <!-- LEFT SIDE -->
  <div class="left">
    <img src="{{ asset('img/hasta_logo') }}" width="120">
    <h1>For You,<br>From Us</h1>
    <p>Become our member to start booking now</p>

    <img src="{{ asset('img/redcar.png') }}" class="car">
  </div>

  <!-- RIGHT SIDE -->
  <div class="right">
    <div class="card">
      <h2>Welcome to Hasta Travel!</h2>

      <form method="POST" action="/register">
        @csrf

        <label>Email address</label>
        <input type="email" name="email" required>

        <label>Full Name</label>
        <input type="text" id="fullName" name="name" readonly>

        <label>Phone Number</label>
        <input type="tel" name="phone" required>

        <label>National Identification Card</label>
        <input type="text" id="icNumber" name="ic" readonly>

        <label>Upload National IC</label>
        <input type="file" id="icUpload">

        <label>Driver’s License</label>
        <input type="text" id="licenseNumber" readonly>

        <input type="file" id="licenseUpload">

        <label>Student Card</label>
        <input type="text" id="studentNumber" readonly>

        <input type="file" id="studentUpload">

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Enter Password Again</label>
        <input type="password" name="password_confirmation" required>

        <button id="submitBtn" disabled>Sign Up</button>
      </form>
    </div>
  </div>

</div>

<script src="{{ asset('js/ocr.js') }}"></script>
</body>
</html>

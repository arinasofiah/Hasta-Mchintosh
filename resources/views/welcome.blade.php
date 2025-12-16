<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">

    <style>
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #bc3737;
            color: white;
            text-align: center;
        }
    </style>
</head>

<body>
@include('profile.partials.header') <!--just include this one for header-->
<div id="body">
    {{-- Page content goes here --}}
</div>

<div class="footer">

    <div class="logo">
        <img src="{{ asset('img/hasta_logo.jpg') }}">
    </div>

    <div class="footer-item">
        <div class="footer-icon">📍</div>
        <div>
            <span class="title">Address</span><br>
            Student Mall UTM<br>
            Skudai, 81300, Johor Bahru
        </div>
    </div>

    <div class="footer-item">
        <div class="footer-icon">✉️</div>
        <div>
            <span class="title">Email</span><br>
            <a href="mailto:hastatravel@gmail.com">hastatravel@gmail.com</a>
        </div>
    </div>

    <div class="footer-item">
        <div class="footer-icon">📞</div>
        <div>
            <span class="title">Phone</span><br>
            <a href="tel:01110900700">011-1090 0700</a>
        </div>
    </div>

</div>

</body>
</html>

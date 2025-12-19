<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pickup.css') }}" rel="stylesheet">

</head>

<body>
@include('profile.partials.header')
<div id="body">
    
<div class="container">
    <div class="pickup-layout">
        <aside class="vehicle-card">
            <p id="res_txt">Reserved Vehicle</p>
            <p id="car_name">Perodua Axia 2024<p>
            <p id="car_type">Hatchback</p>

            <img src="{{ asset('img/redcar.png') }}">

            <p id="car_det">Full Vehicle Details<p>
            <ul class="features">
                <li> <img src="{{ asset('img/car_icon.png') }}"><span>Automatic</span></li>
                <li><img src="{{ asset('img/car_icon.png') }}"><span>RON 95</span></li>
                <li><img src="{{ asset('img/car_icon.png') }}"><span>Air Conditioner</span></li>
                <li> <img src="{{ asset('img/car_icon.png') }}"><span>5 Seats</span></li>
                <li><img src="{{ asset('img/car_icon.png') }}"><span>4 Doors</span></li>
                <li><img src="{{ asset('img/car_icon.png') }}"><span>1.3k km</span></li>
            </ul>
            <p id="day_pr">RM130 / Day</p>
            <p id="all_pr">Total MYR 530</p>
        </aside>
    </div>
</div>

</div>


</body>
</html>

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
        <div class="vehicle-card">
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
</div>
         <div class="pickup_form">
            <p id="form_name">Pick Up Details</p>
            <p class="main_txt">Upload Photos</p>
            <p class="sub_txt">Upload photos of the car before pick up</p>
            <p class="main_txt">Pick Up Information</p>
            <p class="sub_txt">Fill in pick up details</p>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div>
                    <label for="loc">Pickup Location:</label>
                    <input type="text" id="loc">
                    </div>
                    <div>
                    <label for="date_pickup">Pickup Location:</label>
                    <input type="date" id="date_pickup">
                    </div>
                </div>

                <label class="checkbox">
                    <input type="checkbox"> 
                    <span>I have read and accepted the Terms and Conditions</span>
                </label>

                <div id="btn_div"> 
                    <button class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>


</body>
</html>

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
            <p id="car_name">{{$vehicle->model}}<p>
            <p id="car_type">{{$vehicle->vehicleType}}</p>

            <img src="{{ asset('img/redcar.png') }}">

            <p id="car_det">Full Vehicle Details<p>
            <ul class="features">
                 <li> <img src="{{ asset('img/car_icon.png') }}"><span>{{$vehicle->vehicleType}}</span></li>
                <li><img src="{{ asset('img/car_icon.png') }}"><span>{{$vehicle->fuelType}}</span></li>
                <li><img src="{{ asset('img/car_icon.png') }}"><span>{{ $vehicle->ac ? 'AC' : 'No AC' }}</span></li>
                <li> <img src="{{ asset('img/car_icon.png') }}"><span>{{$vehicle->seat}} Seat</span></li>
            </ul>
            <p id="day_pr">{{$vehicle->pricePerDay}} / Day</p>
            <p id="all_pr">Total MYR 530</p>
</div>
         <div class="pickup_form">
            <!--<div class="no-pay">
                <p>Before confirming Pick Up details, Please pay.</p>
            </div>-->
            <p id="form_name">Pick Up Details</p>
            <p class="main_txt">Upload Photos</p>
            <p class="sub_txt">Upload photos of the car before pick up</p>
            <form action="{{ route('pickup.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="drop-zone">
                <p>Drop files to upload</p>
                <p>or</p>
                <p><input type="file" id="file-input" accept="image/*" name="pickupPhoto"/></p>
            </div>
            <p class="main_txt">Pick Up Information</p>
            <p class="sub_txt">Fill in pick up details</p>
            
           @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <div class="form-row">
                    <div>
                    <label for="loc">Location:</label>
                   <input type="text" id="loc" name="pickupLocation" required>
                    </div>
                    <div>
                    <label for="date_pickup">Pickup date:</label>
                    <input type="date" id="date_pickup" name="pickupDate" required>
                    </div>
                </div>

                <label class="checkbox">
                     <input type="checkbox" name="agreementForm" value="yes" required> 
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

<div class="modal fade" id="emergencyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4" style="border-radius: 20px;">
      <div class="modal-body">
        <h3 class="fw-bold mb-4">Important</h3>
        <p class="mb-4">In case of an emergency, such as an accident or mechanical problems, please contact our hotline:</p>
        <h2 class="fw-bold mb-5">+60 12-326 1234</h2>
        
        <button type="button" class="btn btn-danger w-100 py-2" id="confirmSave" style="border-radius: 10px; background-color: #CB3737;">
          Understood
        </button>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('showModal'))
        var myModal = new bootstrap.Modal(document.getElementById('emergencyModal'));
        myModal.show();
    @endif
});
</script>
</body>
</html>

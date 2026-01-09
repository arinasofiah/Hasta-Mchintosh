<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasta Travel & Tour</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pickup.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
@include('profile.partials.header')
<div id="body">   
<div class="container">
    <div class="pickup-layout">
        <div class="vehicle-card">
            <p id="res_txt">Reserved Vehicle</p>
            <p id="car_name">{{$vehicle->model}}</p>
            <p id="car_type">{{$vehicle->vehicleType}}</p>

            <img src="{{ asset('img/redcar.png') }}" style="width: 100%; margin-bottom: 15px;">

            <p id="car_det">Full Vehicle Details</p>
            <ul class="features">
                <li><i class="fas fa-car"></i><span>{{$vehicle->vehicleType}}</span></li>
                <li><i class="fas fa-gas-pump"></i><span>{{$vehicle->fuelType}}</span></li>
                <li><i class="fas fa-snowflake"></i><span>{{ $vehicle->ac ? 'AC' : 'No AC' }}</span></li>
                <li><i class="fas fa-users"></i><span>{{$vehicle->seat}} Seat</span></li>
            </ul>

            <p id="car_det">Full Pickup Details</p>
            <ul class="features">
                <li><i class="fas fa-map-marker-alt"></i><span>{{$pickup->pickupLocation}}</span></li>
                <li><i class="fas fa-calendar-alt"></i><span>{{$booking->startDate}}</span></li>
                <li><i class="fas fa-clock"></i><span>{{$pickup->pickupTime}}</span></li>
                <li><i class="fas fa-gas-pump"></i><span>{{$vehicle->fuelLevel}}%</span></li>
            </ul>

            <p id="car_det">Full Return Details</p>
            <ul class="features">
                <li><i class="fas fa-map-marker-alt"></i><span>{{$return->returnLocation}}</span></li>
                <li><i class="fas fa-calendar-alt"></i><span>{{$booking->endDate}}</span></li>
                <li><i class="fas fa-clock"></i><span>{{$return->returnTime}}</span></li>
                <li><i class="fas fa-gas-pump"></i><span>{{$vehicle->fuelLevel}}%</span></li>
            </ul>

            <p id="day_pr">{{$vehicle->pricePerDay}} / Day</p>
            <p id="all_pr">Total MYR 530</p>
        </div>

        <div class="pickup_form">
            <form action="{{ route('pickup.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pickupID" value="{{ $pickup->pickupID }}">
                <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">
                <input type="hidden" name="returnID" value="{{ $return->returnID }}">
                
                @if($onlyDepositPaid)
                    <div class="no-pay"><p>Before confirming Pick Up details, Please pay.</p></div>
                @endif

                <p id="form_name">Pick up vehicle Inspection</p>
                <p class="main_txt">Upload 4 Angle Photos</p>
                <p class="sub_txt">Please provide clear photos of all sides of the vehicle.</p>

                <div class="photo-grid">
                    <div class="mini-drop-zone" onclick="document.getElementById('imgFront').click()">
                        <p><strong>Front View</strong></p>
                        <img src="{{ asset('img/car_sides/front.png') }}" class="placeholder-img" id="iconFront">
                        <input type="file" id="imgFront" name="photo_front" accept="image/*" hidden onchange="preview(this, 'pFront', 'iconFront')">
                        <img id="pFront" class="preview-img">
                    </div>
                    <div class="mini-drop-zone" onclick="document.getElementById('imgBack').click()">
                        <p><strong>Back View</strong></p>
                        <img src="{{ asset('img/car_sides/back.png') }}" class="placeholder-img" id="iconBack">
                        <input type="file" id="imgBack" name="photo_back" accept="image/*" hidden onchange="preview(this, 'pBack','iconBack')">
                        <img id="pBack" class="preview-img">
                    </div>
                    <div class="mini-drop-zone" onclick="document.getElementById('imgLeft').click()">
                        <p><strong>Left Side</strong></p>
                        <img src="{{ asset('img/car_sides/left.png') }}" class="placeholder-img" id="iconLeft">
                        <input type="file" id="imgLeft" name="photo_left" accept="image/*" hidden onchange="preview(this, 'pLeft','iconLeft')">
                        <img id="pLeft" class="preview-img">
                    </div>
                    <div class="mini-drop-zone" onclick="document.getElementById('imgRight').click()">
                        <p><strong>Right Side</strong></p>
                        <img src="{{ asset('img/car_sides/right.png') }}" class="placeholder-img" id="iconRight">
                        <input type="file" id="imgRight" name="photo_right" accept="image/*" hidden onchange="preview(this, 'pRight','iconRight')">
                        <img id="pRight" class="preview-img">
                    </div>
                </div>

                <p class="sub_txt">Please provide your signature below to confirm you agree with Terms and Conditions</p>
                <div class="signature-container">
                    <canvas id="signature-pad">  </canvas>
                    <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <button type="button" id="clear-signature" style="background: none; border: none; color: #CB3737; text-decoration: underline; cursor: pointer;">Clear Signature</button>
                    </div>
                    <input type="hidden" name="signature" id="signature-input" required>
                </div>


                <div id="btn_div"> 
                    <button class="btn-primary" {{ $onlyDepositPaid ? 'disabled style=opacity:0.5;' : '' }}>Save Pick Up</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script>
    function preview(input, previewId, placeholderId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            // Show the actual photo
            let img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
            
            // Hide the placeholder outline
            document.getElementById(placeholderId).style.display = 'none';
            
            // Optional: Change text color to show success
            input.parentElement.querySelector('p').style.color = '#CB3737';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

    document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    const input = document.getElementById('signature-input');
    const clearBtn = document.getElementById('clear-signature');
    const ctx = canvas.getContext('2d');

    // 1. Match canvas resolution to its display size
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);
        ctx.strokeStyle = "#333"; // Ink color
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    let drawing = false;

    // 2. Coordinate calculation helper
    function getXY(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    // 3. Drawing Events
    function start(e) {
        drawing = true;
        const pos = getXY(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        e.preventDefault();
    }

    function move(e) {
        if (!drawing) return;
        const pos = getXY(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        e.preventDefault();
    }

    function stop() {
        if (drawing) {
            drawing = false;
            // Transfer canvas data to hidden input
            input.value = canvas.toDataURL("image/png");
        }
    }

    // Mouse listeners
    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    window.addEventListener('mouseup', stop);

    // Touch listeners (for Mobile/iPad)
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', stop);

    // Clear function
    clearBtn.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        input.value = "";
    });
});
</script>
</body>
</html>
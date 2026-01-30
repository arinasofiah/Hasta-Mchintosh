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
<div id="header">
        <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}" alt="Hasta Logo">
        
        <div id="menu">
            <button class="head_button" onclick="window.location.href='{{ route('customer.dashboard') }}'">Home</button>
            <button class="head_button" onclick="window.location.href='{{ route('customer.dashboard') }}'">Vehicles</button>
        </div>
        
        <div id="profile">
            <div id="profile-container">
                <img id="pfp" src="{{ asset('img/racc_icon.png') }}" alt="Profile">
                
                <div id="profile-dropdown">
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">My Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
            
            @auth
                <span id="username">{{ Auth::user()->name }}</span>
            @endauth
        </div>
    </div>
<div id="body">   
<div class="container">
    <div class="pickup-layout">
        <div class="vehicle-card">
            <p id="res_txt">Reserved Vehicle</p>
            <p id="car_name">{{$vehicle->model}}</p>

            @if($vehicle->vehiclePhoto)
                    <img src="{{ Storage::url($vehicle->vehiclePhoto) }}" style="width: 100%; margin-bottom: 15px;" class="vehicle-image" alt="{{ $vehicle->model }}">
            @else
                    <img src="{{ asset('img/default-car.jpg') }}" style="width: 100%; margin-bottom: 15px;" class="vehicle-image" alt="Default Car">
            @endif

            <p id="car_det">Vehicle Details</p>
            <ul class="features">
                <li><i class="fas fa-car"></i><span>{{$vehicle->vehicleType}}</span></li>
                <li><i class="fas fa-gas-pump"></i><span>{{$vehicle->fuelType}}</span></li>
                <li><i class="fas fa-snowflake"></i><span>{{ $vehicle->ac ? 'AC' : 'No AC' }}</span></li>
                <li><i class="fas fa-users"></i><span>{{$vehicle->seat}} Seat</span></li>
            </ul>

            <p id="car_det">Pickup Details</p>
            <ul class="features">
                <li><i class="fas fa-map-marker-alt"></i><span>{{$pickup->pickupLocation}}</span></li>
                <li><i class="fas fa-calendar-alt"></i><span>{{$booking->startDate}}</span></li>
                <li><i class="fas fa-clock"></i><span>{{$pickup->pickupTime}}</span></li>
                <li><i class="fas fa-gas-pump"></i><span>{{$vehicle->fuelLevel}}%</span></li>
            </ul>

            <p id="car_det">Return Details</p>
            <ul class="features">
                <li><i class="fas fa-map-marker-alt"></i><span>{{$return->returnLocation}}</span></li>
                <li><i class="fas fa-calendar-alt"></i><span>{{$booking->endDate}}</span></li>
                <li><i class="fas fa-clock"></i><span>{{$return->returnTime}}</span></li>
                <li><i class="fas fa-gas-pump"></i><span>{{$vehicle->fuelLevel}}%</span></li>
            </ul>

            <p id="day_pr">{{$vehicle->pricePerDay}} / Day</p>
            <p id="all_pr">Total MYR {{$booking->totalPrice}}</p>
        </div>

        <div class="pickup_form">
            @if(!$pickup->pickupComplete)
            <form action="{{ route('pickup.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pickupID" value="{{ $pickup->pickupID }}">
                <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">
                <input type="hidden" name="returnID" value="{{ $return->returnID }}">

                <p id="form_name">Pick up vehicle form</p>
                <p class="main_txt">Upload 4 Angle Photos</p>
                <p class="sub_txt">Please provide clear photos of all sides of the vehicle.</p>

                <div class="photo-grid">
                    <div class="mini-drop-zone" onclick="document.getElementById('imgFront').click()">
                        <p><strong>Front View</strong></p>
                        <span class="error-msg" id="err_photo_front">Required</span>
                        <img src="{{ asset('img/car_sides/front.png') }}" class="placeholder-img" id="iconFront">
                        <input type="file" id="imgFront" name="photo_front" accept="image/*" hidden onchange="preview(this, 'pFront', 'iconFront')">
                        <img id="pFront" class="preview-img">
                    </div>
                    <div class="mini-drop-zone" onclick="document.getElementById('imgBack').click()">
                        <p><strong>Back View</strong></p>
                        <span class="error-msg">Required</span>
                        <img src="{{ asset('img/car_sides/back.png') }}" class="placeholder-img" id="iconBack">
                        <input type="file" id="imgBack" name="photo_back" accept="image/*" hidden onchange="preview(this, 'pBack','iconBack')">
                        <img id="pBack" class="preview-img">
                    </div>
                    <div class="mini-drop-zone" onclick="document.getElementById('imgLeft').click()">
                        <p><strong>Left Side</strong></p>
                        <span class="error-msg">Required</span>
                        <img src="{{ asset('img/car_sides/left.png') }}" class="placeholder-img" id="iconLeft">
                        <input type="file" id="imgLeft" name="photo_left" accept="image/*" hidden onchange="preview(this, 'pLeft','iconLeft')">
                        <img id="pLeft" class="preview-img">
                    </div>
                    <div class="mini-drop-zone" onclick="document.getElementById('imgRight').click()">
                        <p><strong>Right Side</strong></p>
                        <span class="error-msg">Required</span>
                        <img src="{{ asset('img/car_sides/right.png') }}" class="placeholder-img" id="iconRight">
                        <input type="file" id="imgRight" name="photo_right" accept="image/*" hidden onchange="preview(this, 'pRight','iconRight')">
                        <img id="pRight" class="preview-img">
                    </div>
                </div>

                <p class="sub_txt">
                    Please provide your signature below to confirm you agree with 
                    <a href="#" data-bs-toggle="modal" data-bs-target="#tncModal" style="color: #CB3737; text-decoration: underline;">
                        Terms and Conditions
                    </a>
                </p>
                <span class="error-msg" id="err_sig_group" style="margin-left: 10px;">Please provide either a signature or a signed document.</span>
                <div id="sig_area_wrapper" class="signature-section" style="display: flex; gap: 5px; align-items: flex-start; margin-top: 10px;">
                    <div style="flex: 1;">
                        <canvas id="signature-pad"></canvas>
                        <div style="margin-top: 5px;">
                            <button type="button" id="clear-signature" style="background: none; border: none; color: #CB3737; text-decoration: underline; cursor: pointer; font-size: 12px;">Clear Signature</button>
                        </div>
                        <input type="hidden" name="signature" id="signature-input">
                    </div>

                    <div class="mini-drop-zone signature-upload-box" onclick="document.getElementById('imgSigDoc').click()" style="flex: 1;">
                        <div id="upload-content">
                            <p style="font-size: 11px; margin: 0;"><strong>OR Upload Signed Doc</strong></p>
                            <i class="fas fa-file-signature" id="iconSigDoc" style="font-size: 1.2rem; color: #ccc; margin-top: 5px;"></i>
                        </div>
                        <input type="file" id="imgSigDoc" name="manual_signature_photo" accept="image/*" hidden onchange="preview(this, 'pSigDoc', 'iconSigDoc')">
                        <img id="pSigDoc" class="preview-img">
                    </div>
                </div>

                <div id="btn_div"> 
                    <button type="button" id="savePickupBtn" class="btn-primary">Save Pick Up</button>
                </div>
            </form>
            @else
        <div class="completed-tile" style="background: #f8f9fa; padding: 15px; border-radius: 15px; border-left: 5px solid #28a745; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-check-circle text-success"></i> <strong>Pickup Completed</strong></span>
            </div>
            
            <div id="pickup-details" style="display: none; margin-top: 15px;">
                <div class="photo-grid">
                    <img src="{{ asset($pickup->photo_front) }}" class="preview-img" style="display:block">
                    <img src="{{ asset($pickup->photo_back) }}" class="preview-img" style="display:block">
                </div>
                <p style="font-size: 12px; margin-top: 10px;">Status: Picked up on {{ $pickup->updated_at->format('d M, H:i') }}</p>
            </div>
        </div>

        <div class="return_form">
            @if(!$return->photo_dashboard)
    <form action="{{ route('return.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">
        <input type="hidden" name="late_fee" id="late_fee_input" value="0">
        <input type="hidden" name="fuel_fee" id="fuel_fee_input" value="0">
        <input type="hidden" name="total_fee" id="total_fee_input" value="0">

        <p id="form_name">Return vehicle form</p>
        <p class="main_txt">Upload 4 Angle Photos</p>

        <div class="photo-grid">
            <div class="mini-drop-zone" onclick="document.getElementById('imgRetFront').click()">
                <p><strong>Front View</strong></p>
                <span class="error-msg" >Required</span>
                <img src="{{ asset('img/car_sides/front.png') }}" class="placeholder-img" id="iconFront">
                <input type="file" id="imgRetFront" name="return_photo_front" accept="image/*" hidden onchange="preview(this, 'pRetFront', 'iconFront')">
                <img id="pRetFront" class="preview-img">
            </div>
            <div class="mini-drop-zone" onclick="document.getElementById('imgRetBack').click()">
                <p><strong>Back View</strong></p>
                <span class="error-msg" >Required</span>
                <img src="{{ asset('img/car_sides/back.png') }}" class="placeholder-img" id="iconBack">
                <input type="file" id="imgRetBack" name="return_photo_back" accept="image/*" hidden onchange="preview(this, 'pRetBack', 'iconBack')">
                <img id="pRetBack" class="preview-img">
            </div>
            <div class="mini-drop-zone" onclick="document.getElementById('imgRetLeft').click()">
                <p><strong>Left Side</strong></p>
                <span class="error-msg">Required</span>
                <img src="{{ asset('img/car_sides/left.png') }}" class="placeholder-img" id="iconLeft">
                <input type="file" id="imgRetLeft" name="return_photo_left" accept="image/*" hidden onchange="preview(this, 'pRetLeft', 'iconLeft')">
                <img id="pRetLeft" class="preview-img">
            </div>
            <div class="mini-drop-zone" onclick="document.getElementById('imgRetRight').click()">
                <p><strong>Right Side</strong></p>
                <span class="error-msg">Required</span>
                <img src="{{ asset('img/car_sides/right.png') }}" class="placeholder-img" id="iconRight">
                <input type="file" id="imgRetRight" name="return_photo_right" accept="image/*" hidden onchange="preview(this, 'pRetRight', 'iconRight')">
                <img id="pRetRight" class="preview-img">
            </div>
        </div>

        <p class="main_txt">Upload Dashboard Photos</p>
        <div class="photo-grid">
            <div class="mini-drop-zone" onclick="document.getElementById('imgDash').click()">
                <p><strong>Dashboard</strong></p>
                <span class="error-msg">Required</span>
                <img src="{{ asset('img/dashboard.jpg') }}" class="placeholder-img" id="iconDash">
                <input type="file" id="imgDash" name="return_photo_dashboard" accept="image/*" hidden onchange="preview(this, 'pDash', 'iconDash')">
                <img id="pDash" class="preview-img">
            </div>
            <div class="mini-drop-zone" onclick="document.getElementById('imgKeys').click()">
                <p><strong>Keys</strong></p>
                <span class="error-msg">Required</span>
                <img src="{{ asset('img/keys.jpg') }}" class="placeholder-img" id="iconKeys">
                <input type="file" id="imgKeys" name="return_photo_keys" accept="image/*" hidden onchange="preview(this, 'pKeys', 'iconKeys')">
                <img id="pKeys" class="preview-img">
            </div>
        </div>

        <div class="radio-section">
            <span>Were any traffic tickets received?</span>
            <label class="radio-label">
                <input type="radio" name="isFined" value="yes" onchange="toggleTicketBox(this)"> <span>Yes</span>
            </label>
            <label class="radio-label">
                <input type="radio" name="isFined" value="no" onchange="toggleTicketBox(this)" checked> <span>No</span>
            </label>
        </div>

        <div id="ticket-upload-wrapper" style="display: none; margin-top: 15px;">
            <p class="sub_txt">Please provide clear photos of traffic ticket(s).</p>
            <div class="mini-drop-zone" onclick="document.getElementById('imgTicket').click()">
                <p><strong>Traffic ticket photos</strong></p>
                <i class="fas fa-file-circle-plus" id="iconTicket" style="font-size: 1.6rem; color: #ccc; margin-top: 5px;"></i>
                <input type="file" id="imgTicket" name="trafficTicketPhoto[]" accept="image/*" hidden multiple onchange="previewFileNames(this, 'fileNameList', 'iconTicket')">
                
                <div id="fileNameList" style="margin-top: 10px; text-align: left; font-size: 12px; color: #333;"></div>
            </div>
        </div>

        </div><p class="main_txt">Return information</p>
        <p class="sub_txt">Please provide information about the return</p>

       <div class="return-info-grid">
    <div class="input-group">
        <span class="error-msg">Required</span>
        <label for="fuel">Fuel amount (%)</label>
        <input type="number" id="fuel" name="fuelAmount" placeholder="0">
    </div>

    <div class="input-group">
        <span class="error-msg">Required</span>
        <label for="ac_ret_time">Return time</label>
        <input type="time" id="ac_ret_time" name="acRetTime">
    </div>

    <div class="input-group full-width">
        <span class="error-msg">Required</span>
        <label for="feed">Feedback</label>
        <textarea id="feed" name="feedback" rows="4" placeholder="How was the ride?"></textarea>
    </div>
</div>

        <div id="btn_div"> 
             <button type="submit" class="btn-primary">Save Return</button>
        </div>
        </form>
        @else
                <div class="completed-tile return-done" style="background: #f8f9fa; padding: 15px; border-radius: 15px; border-left: 5px solid #28a745; margin-bottom: 20px;">
                        <span><i class="fas fa-check-circle text-success"></i> <strong>Return completed</strong></span>
                </div>
                <div class="summary-fees">
                   <div class="fee-summary-card">
                        <div class="fee-header">Payment Summary</div>
    
                        <div class="fee-row">
                            <span class="fee-label">Late Return Fee (50 RM for every 30min)</span>
                            <span class="fee-value">RM {{ number_format($return->late_fee, 2) }}</span>
                        </div>

                        <div class="fee-row">
                            <span class="fee-label">Refuel Surcharge (50 RM per 10%) </span>
                            <span class="fee-value">RM {{ number_format($return->fuel_fee, 2) }}</span>
                        </div>

                        <hr class="fee-divider">

                        <div class="total-row">
                            <span class="total-label">Total Amount Due</span>
                            <span class="total-value">RM {{ number_format($return->total_fee, 2) }}</span>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('payment.remaining', $booking->bookingID) }}" class="btn btn-success">
                                <i class="fas fa-credit-card"></i> Pay Remaining Balance
                            </a>
                        </div>
                    </div>
                @endif
                </div>
            @endif 
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="tncModal" tabindex="-1" aria-labelledby="tncModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tncModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('img/terms_temp.jpg') }}" alt="Terms and Conditions" style="width: 100%; height: auto;">
            </div>
            <div class="modal-footer">
                <a href="{{ asset('img/terms_temp.jpg') }}" download="Terms_and_Conditions.png" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download T&C
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pickupSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
            <div class="modal-body text-center" style="padding: 30px;">
                <div style="color: #28a745; font-size: 50px; margin-bottom: 15px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4 style="font-weight: 700;">Pickup Confirmed!</h4>
                <p class="text-muted">Your vehicle pickup details have been successfully recorded.</p>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="http://127.0.0.1:8000/customer/customer/bookings" class="btn btn-primary" style="background-color: #CB3737; border: none; padding: 10px; border-radius: 8px;">
                        Go to Booking History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function preview(input, previewId, placeholderId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            let img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
            
            document.getElementById(placeholderId).style.display = 'none';
            
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

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);
        ctx.strokeStyle = "#333";
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    let drawing = false;

    function getXY(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

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
            input.value = canvas.toDataURL("image/png");
        }
    }

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    window.addEventListener('mouseup', stop);

    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', stop);

    clearBtn.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        input.value = "";
    });
    
});
function toggleTicketBox(radio) {
    const ticketBox = document.getElementById('ticket-upload-wrapper');
    if (radio.value === 'yes') {
        ticketBox.style.display = 'block';
    } else {
        ticketBox.style.display = 'none';
        document.getElementById('imgTicket').value = "";
        document.getElementById('pTicket').style.display = "none";
        document.getElementById('iconTicket').style.display = "block";
    }
}

function previewFileNames(input, listId, iconId) {
        const listContainer = document.getElementById(listId);
        const icon = document.getElementById(iconId);
        listContainer.innerHTML = "";

        if (input.files && input.files.length > 0) {
            icon.style.display = 'none';
            for (let i = 0; i < input.files.length; i++) {
                const nameItem = document.createElement("div");
                nameItem.style.cssText = "padding:4px 8px; margin-bottom:4px; background:#f0f0f0; border-radius:5px; border-left:3px solid #CB3737;";
                nameItem.innerHTML = `<i class="fa-solid fa-file-image" style="margin-right:8px; color:#666;"></i> ${input.files[i].name}`;
                listContainer.appendChild(nameItem);
            }
        } else {
            icon.style.display = 'block';
        }
    }

    function calculateLateFee(scheduledTime, actualTime) {
        if (!actualTime) return 0;
        
        function timeToMinutes(timeStr) {
            const parts = timeStr.split(':');
            return (parseInt(parts[0]) * 60) + parseInt(parts[1]);
        }

        const scheduledMinutes = timeToMinutes(scheduledTime);
        const actualMinutes = timeToMinutes(actualTime);
        const diff = actualMinutes - scheduledMinutes;

        if (diff <= 0) return 0;
        return Math.ceil(diff / 30) * 50;
    }
    const fuelInput = document.getElementById('fuel');
        const timeInput = document.getElementById('ac_ret_time');
        
        const pickupFuel = {{ $vehicle->fuelLevel }}; 
        const scheduledTime = "{{ $return->returnTime }}";

        function updateTotals() {
            const lateFee = calculateLateFee(scheduledTime, timeInput.value);
            document.getElementById('late-fee-display').innerText = `RM ${lateFee.toFixed(2)}`;
            document.getElementById('late_fee_input').value = lateFee;

            const currentFuel = parseFloat(fuelInput.value) || pickupFuel;
            let fuelFee = 0;
            if (currentFuel < pickupFuel) {
                fuelFee = Math.ceil((pickupFuel - currentFuel) / 10) * 50;
            }
            document.getElementById('fuel-fee-display').innerText = `RM ${fuelFee.toFixed(2)}`;
            document.getElementById('fuel_fee_input').value = fuelFee;

            const total = lateFee + fuelFee;
            document.getElementById('total_fee_input').value = total;
        }

        if(fuelInput) fuelInput.addEventListener('input', updateTotals);
        if(timeInput) timeInput.addEventListener('change', updateTotals);

function updateTotals() {
    const lateFee = calculateLateFee(scheduledTime, timeInput.value);
    const currentFuel = parseFloat(fuelInput.value) || pickupFuel;
    let fuelFee = 0;
    if (currentFuel < pickupFuel) {
        fuelFee = Math.ceil((pickupFuel - currentFuel) / 10) * 50;
    }
    const total = lateFee + fuelFee;

    if(document.getElementById('late_fee_input')) document.getElementById('late_fee_input').value = lateFee;
    if(document.getElementById('fuel_fee_input')) document.getElementById('fuel_fee_input').value = fuelFee;
    if(document.getElementById('total_fee_input')) document.getElementById('total_fee_input').value = total;
}

document.getElementById('savePickupBtn').addEventListener('click', function() {
    const btn = this;
    const form = btn.closest('form');
    let isValid = true;

    document.querySelectorAll('.error-msg').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.mini-drop-zone, .signature-section').forEach(el => el.classList.remove('field-error'));

    const photos = ['imgFront', 'imgBack', 'imgLeft', 'imgRight'];
    photos.forEach(id => {
        const input = document.getElementById(id);
        if (!input.files || input.files.length === 0) {
            isValid = false;
            const container = input.closest('.mini-drop-zone');
            container.classList.add('field-error');
            container.querySelector('.error-msg').style.display = 'block';
        }
    });

    const signatureData = document.getElementById('signature-input').value;
    const signatureFile = document.getElementById('imgSigDoc').files.length;

    if (!signatureData && signatureFile === 0) {
        isValid = false;
        document.getElementById('err_sig_group').style.display = 'block';
        document.getElementById('sig_area_wrapper').classList.add('field-error');
    }

    if (isValid) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => {
            var myModal = new bootstrap.Modal(document.getElementById('pickupSuccessModal'));
            myModal.show();
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = 'Save Pick Up';
            alert('Error saving. Please try again.');
        });
    } else {
        document.querySelector('.field-error').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});


</script>
</body>
</html>
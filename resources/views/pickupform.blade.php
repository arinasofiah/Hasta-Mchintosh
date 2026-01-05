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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
                 <li><i class="fas fa-car"></i><span>{{$vehicle->vehicleType}}</span></li>
                <li><i class="fas fa-gas-pump"></i><span>{{$vehicle->fuelType}}</span></li>
                <li><i class="fas fa-snowflake"></i><span>{{ $vehicle->ac ? 'AC' : 'No AC' }}</span></li>
                <li> <i class="fas fa-users"></i><span>{{$vehicle->seat}} Seat</span></li>
            </ul>
            <p id="day_pr">{{$vehicle->pricePerDay}} / Day</p>
            <p id="all_pr">Total MYR 530</p>
</div>
         <div class="pickup_form">
        <form action="{{ route('pickup.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="pickupID" value="{{ $pickup->pickupID }}">
        <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">
        @if($onlyDepositPaid)
            <div class="no-pay">
                <p>Before confirming Pick Up details, Please pay.</p>
            </div>
        @endif
            <p id="form_name">Pick Up Details</p>
            <p class="main_txt">Upload Photos</p>
            <p class="sub_txt">Upload photos of the car before pick up</p>
             @error('pickupPhoto')
            <div style="color: #bc3737; font-size: 0.875rem; margin-top: 5px; font-weight: 500;">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
            <div id="drop-zone">
            <div id="upload-prompt">
                <p>Drop files to upload</p>
                <p>or</p>
                <p><button type="button" id="browseBtn" class="browse-btn">Browse</button></p>
            </div>

                <div id="file-info" style="display: none; padding: 20px;">
                    <i class="fas fa-file-image" style="color: #bc3737; font-size: 24px; margin-bottom: 10px;"></i>
                    <p><strong id="fileNameDisplay" style="font-size: 16px; color: #333;"></strong></p>
                    <p style="margin-top: 10px;">
                        <a href="javascript:void(0)" id="removeImage" style="color: #bc3737; font-size: 12px; text-decoration: underline;">
                            Remove and choose another
                        </a>
                    </p>
                </div>
                
                <input type="file" id="fileInput" name="pickupPhoto" accept="image/*" style="display: none;" required/>
            </div>

            <p class="main_txt">Pick Up Information</p>
            <p class="sub_txt">Confirm pick up details</p>

    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                <div class="form-row">
                    <div>
                    <label for="loc">Location:</label>
                   <p class="static-data">{{ $pickup->pickupLocation }}</p>
                    </div>
                    <div>
                    <label for="date_pickup">Pickup date:</label>
                    <p class="static-data">{{ $pickup->pickupDate }}</p>
                    </div>
                </div>

                <label class="checkbox">
                     <input type="checkbox" name="agreementForm" value="yes" required> 
                    <span>I have read and accepted the 
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#termsModal" style="color: #CB3737; text-decoration: underline; cursor: pointer;">
                            Terms and Conditions
                        </a>
                        </span>
                </label>

                <div id="btn_div"> 
                    <button class="btn-primary" {{ $onlyDepositPaid ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>Save</button>
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
    const understoodBtn = document.getElementById('confirmSave');
    if (understoodBtn) {
        understoodBtn.addEventListener('click', function() {
            window.location.href = "{{ route('dashboard') }}";
        });
    }

    const fileInput = document.getElementById('fileInput');
    const browseBtn = document.getElementById('browseBtn');
    const dropZone = document.getElementById('drop-zone');
    
    const uploadPrompt = document.getElementById('upload-prompt');
    const fileInfo = document.getElementById('file-info');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const removeImage = document.getElementById('removeImage');

    if (browseBtn && fileInput) {
        browseBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) handleFile(this.files[0]);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#bc3737';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#ddd';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#ddd';
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files; 
                handleFile(e.dataTransfer.files[0]);
            }
        });
    }

    function handleFile(file) {
        if (fileNameDisplay && uploadPrompt && fileInfo) {
            fileNameDisplay.textContent = file.name;
            uploadPrompt.style.display = 'none';
            fileInfo.style.display = 'block';
        }
    }

    if (removeImage) {
        removeImage.addEventListener('click', () => {
            fileInput.value = ''; 
            uploadPrompt.style.display = 'block';
            fileInfo.style.display = 'none';
            fileNameDisplay.textContent = '';
        });
    }
});
</script>

<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4" style="border-radius: 20px;">
            <div class="modal-body">
                <h3 class="fw-bold mb-3">Terms and Conditions</h3>
                <p style="text-align: left; margin: 20px 0; line-height: 1.5; color: #333;">
                    By proceeding with this {{ Request::is('*pickup*') ? 'pick up' : 'return' }}, you agree to the following terms:<br><br>
                    • Vehicle must be returned in the same condition as received.<br>
                    • Full liability applies for damages or late return.<br>
                    • HASTA Travel reserves the right to cancel bookings for suspicious activity.
                </p>
                <button type="button" class="btn-primary" data-bs-dismiss="modal" style="width: 100%; border-radius: 12px; height: 45px;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>

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
    <link href="{{ asset('css/return.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
@include('profile.partials.header')
<div id="body">
<p id="hd">Return form</p>    
<div class="container">
    <div class="return-layout">

        <div class="photo-upload">
            <p class="main_txt">Upload Photos</p>
            <p class="sub_txt">Upload photos of the car before returning it</p>
             @error('returnPhoto')
            <div style="color: #bc3737; font-size: 0.875rem; margin-top: 5px; font-weight: 500;">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
 <form action="{{ route('return.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="returnID" value="{{ $returnCar->returnID }}">
        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
        <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">

        <div id="drop-zone-photo" class="drop-zone">
                    <div id="upload-prompt-photo">
                        <p>Drop files to upload</p>
                        <p>or</p>
                        <p><button type="button" id="browseBtn-photo" class="browse-btn">Browse</button></p>
                    </div>
                    <div id="file-info-photo" style="display: none; padding: 20px;">
                        <i class="fas fa-file-image" style="color: #bc3737; font-size: 24px;"></i>
                        <p><strong id="fileNameDisplay-photo"></strong></p>
                        <p><a href="javascript:void(0)" id="removeImage-photo" style="color: #bc3737; font-size: 12px;">Remove</a></p>
                    </div>
                    <input type="file" id="fileInput-photo" name="returnPhoto" accept="image/*" style="display: none;" required/>
                </div>

                <br>

                <p class="main_txt">Upload Traffic Tickets</p>
                <p class="sub_txt">Upload traffic tickets, if you received any</p>
                
                <div id="drop-zone-ticket" class="drop-zone">
                    <div id="upload-prompt-ticket">
                        <p>Drop files to upload</p>
                        <p>or</p>
                        <p><button type="button" id="browseBtn-ticket" class="browse-btn">Browse</button></p>
                    </div>
                    <div id="file-info-ticket" style="display: none; padding: 20px;">
                        <i class="fas fa-file-invoice-dollar" style="color: #bc3737; font-size: 24px;"></i>
                        <p><strong id="fileNameDisplay-ticket"></strong></p>
                        <p><a href="javascript:void(0)" id="removeImage-ticket" style="color: #bc3737; font-size: 12px;">Remove</a></p>
                    </div>
                    <input type="file" id="fileInput-ticket" name="trafficTicketPhoto" accept="image/*" style="display: none;"/>
                </div>

        </div>
         <div class="return_form">
            <p class="main_txt">Return Details</p>
            <p class="sub_txt">Fill in return details</p>

                <div class="form-row">
                    <div class="input-group">
                        <label for="loc">Return location:</label>
                        <p class="static-data">{{ $returnCar->returnLocation }}</p>
                    </div>
                    <div class="input-group">
                        <label for="date_return">Return date date:</label>
                        <p class="static-data">{{ $returnCar->returnDate }}</p>
                    </div>
                </div>
                 <div class="fuel_gr">
             @error('fuelAmount')
            <div style="color: #bc3737; font-size: 0.875rem; margin-top: 5px; font-weight: 500;">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror
            <label for="fuel">Fuel amount:</label>
            <input type="text" id="fuel" name="fuelAmount" placeholder="e.g. 0">
            </div>
                    <div class="radio-section">
                        <span>Were any traffic tickets received?</span>
                        <label class="radio-label"><input type="radio" name="isFined" value="yes"> <span>Yes</span></label>
                        <label class="radio-label"><input type="radio" name="isFined" value="no"> <span>No</span></label>
                    </div>

                     @error('feedback')
                    <div style="color: #bc3737; font-size: 0.875rem; margin-top: 5px; font-weight: 500;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror

                    <div class="input-group">
                    <label for="feed">Feedback</label>
                   <textarea id="feed" name="feedback" rows="5"></textarea>
                    </div>

                <label class="checkbox">
                     <input type="checkbox" name="terms" required> 
                    <span>I have read and accepted the 
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#termsModal" style="color: #CB3737; text-decoration: underline; cursor: pointer;">
                            Terms and Conditions
                        </a>
                        </span>
                </label>

                <div id="btn_div"> 
                    <button class="btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    function setupUpload(prefix) {
        const fileInput = document.getElementById('fileInput-' + prefix);
        const browseBtn = document.getElementById('browseBtn-' + prefix);
        const dropZone = document.getElementById('drop-zone-' + prefix);
        const uploadPrompt = document.getElementById('upload-prompt-' + prefix);
        const fileInfo = document.getElementById('file-info-' + prefix);
        const fileNameDisplay = document.getElementById('fileNameDisplay-' + prefix);
        const removeImage = document.getElementById('removeImage-' + prefix);

        if (!browseBtn || !fileInput) return;

        browseBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) handleFile(this.files[0]);
        });

        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.style.borderColor = '#bc3737'; });
        dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = '#ddd'; });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#ddd';
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files; 
                handleFile(e.dataTransfer.files[0]);
            }
        });

        function handleFile(file) {
            fileNameDisplay.textContent = file.name;
            uploadPrompt.style.display = 'none';
            fileInfo.style.display = 'block';
        }

        removeImage.addEventListener('click', () => {
            fileInput.value = ''; 
            uploadPrompt.style.display = 'block';
            fileInfo.style.display = 'none';
        });
    }

    // Initialize both upload areas
    setupUpload('photo');
    setupUpload('ticket');
});
</script>
</body>
</html>

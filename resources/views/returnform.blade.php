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

</head>

<body>
@include('profile.partials.header')
<div id="body">
<p id="hd">Return from</p>    
<div class="container">
    <div class="return-layout">
        <div class="photo-upload">
            <p class="main_txt">Upload Photos</p>
            <p class="sub_txt">Upload photos of the car before returning it</p>
 <form action="{{ route('return.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
            <div id="drop-zone">
                <p>Drop files to upload</p>
                <span>or</apan>
                <p><input type="file" id="file-input" name="returnPhoto" multiple accept="image/*" /></p>
            </div>

            <p class="main_txt">Upload Traffic Tickets</p>
            <p class="sub_txt">Upload traffic tickets, if you received any</p>

            <div id="drop-zone">
                <p>Drop files to upload</p>
                <span>or</span>
                <p><input type="file" id="file-input" name="trafficTicketPhoto" multiple accept="image/*" /></p>
            </div>
        </div>
         <div class="return_form">
            <p class="main_txt">Return Details</p>
            <p class="sub_txt">Fill in return details</p>
            

    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <div class="form-row">
                    <div class="input-group">
                        <label for="loc">Return location:</label>
                        <input type="text" id="loc" name="returnLocation" required>
                    </div>
                    <div class="input-group">
                        <label for="date_return">Return date date:</label>
                        <input type="date" id="date_return" name="returnDate" required>
                    </div>
                </div>

                    <div class="radio-section">
                        <span>Were any traffic tickets received?</span>
                        <label class="radio-label"><input type="radio" name="isFined" value="yes"> <psna>Yes</span></label>
                        <label class="radio-label"><input type="radio" name="isFined" value="no"> <span>No</span></label>
                    </div>

                    <div class="input-group">
                    <label for="feed">Feedback</label>
                   <textarea id="feed" name="feedback" rows="5"></textarea>
                    </div>

                <label class="checkbox">
                     <input type="checkbox" name="terms" required> 
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

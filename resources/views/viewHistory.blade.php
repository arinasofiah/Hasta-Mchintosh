<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking History - HASTA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #d64444;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            background-color: white;
            padding: 8px 15px;
            font-size: 28px;
            font-weight: bold;
            color: #d64444;
            border: 3px solid white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 18px;
        }

        .user-icon {
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d64444;
            font-size: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #333;
        }

        .section {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 22px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .section-title.completed {
            color: #2d8659;
        }

        .section-title.upcoming {
            color: #d4941f;
        }

        .section-title.cancelled {
            color: #8b4444;
        }

        .booking-card {
            display: flex;
            align-items: center;
            gap: 30px;
            padding: 20px 0;
        }

        .car-image {
            width: 150px;
            height: 100px;
            object-fit: contain;
        }

        .booking-details {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .car-info h3 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #333;
        }

        .car-info p {
            color: #666;
            font-size: 14px;
        }

        .price {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            min-width: 100px;
        }

        .dates {
            font-size: 16px;
            color: #666;
            min-width: 150px;
        }

        .status {
            font-size: 16px;
            font-weight: 600;
            min-width: 120px;
        }

        .status.completed {
            color: #2d8659;
        }

        .status.upcoming {
            color: #d4941f;
        }

        .status.cancelled {
            color: #8b4444;
        }

        .action-btn {
            padding: 12px 30px;
            border: 2px solid #8b4444;
            background-color: white;
            color: #8b4444;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background-color: #8b4444;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 40px;
        }

        .page-btn {
            width: 45px;
            height: 45px;
            border: 1px solid #ddd;
            background-color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .page-btn:hover {
            background-color: #f0f0f0;
        }

        .page-btn.active {
            background-color: #d64444;
            color: white;
            border-color: #d64444;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-header {
            background-color: #d64444;
            color: white;
            padding: 20px 30px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 24px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 30px;
        }

        .detail-section {
            margin-bottom: 30px;
        }

        .detail-section h3 {
            font-size: 18px;
            color: #d64444;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .car-detail-image {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            display: block;
            border-radius: 8px;
        }

        .total-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn-primary {
            padding: 12px 30px;
            background-color: #d64444;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #b83838;
        }

        .btn-secondary {
            padding: 12px 30px;
            background-color: white;
            color: #666;
            border: 2px solid #ddd;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: #f5f5f5;
        }

        .btn-danger {
            padding: 12px 30px;
            background-color: #8b4444;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-danger:hover {
            background-color: #6d3535;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #d64444;
        }

        .date-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-message {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #856404;
        }

        .warning-message {
            background-color: #f8d7da;
            border-left: 4px solid #d64444;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .booking-details {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .booking-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .date-inputs {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 0;
            }

            .action-btn-link {
                text-decoration: none;
                display: inline-block;
                padding: 12px 30px;
                border: 2px solid #8b4444;
                background-color: white;
                color: #8b4444;
                border-radius: 25px;
                font-size: 16px;
                font-weight: 500;
                transition: all 0.3s;
                text-align: center;
            }

            .action-btn-link:hover {
                background-color: #8b4444;
                color: white;
            }
        }
    </style>
</head>
<body>
    <div id="header">
    <img id="logo" src="{{ asset('img/hasta_logo.jpg') }}">
    <div id="menu">
        <button class="head_button">Home</button>
        <button class="head_button">Vehicles</button>
        <button class="head_button">Details</button>
        <button class="head_button">About Us</button>
        <button class="head_button">Contact Us</button>
    </div>

    <div class="container">
        <h1>My Booking History</h1>

        <div class="section">
            <div class="section-title completed">Completed</div>
            <div class="booking-card">
                <img src="https://images.unsplash.com/photo-1597404294360-fcd6e48f0eb9?w=300&h=200&fit=crop" alt="Perodua Axia" class="car-image">
                <div class="booking-details">
                    <div class="car-info">
                        <h3>Perodua Axia 2018</h3>
                        <p>Hatchback</p>
                    </div>
                    <div class="price">RM240</div>
                    <div class="dates">12-14 Dec 2025</div>
                    <div class="status completed">Completed</div>
                    <button class="action-btn" onclick="showDetailsModal()">View Details</button>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title upcoming">Upcoming</div>
            <div class="booking-card">
                <img src="https://images.unsplash.com/photo-1619767886558-efdc259cde1a?w=300&h=200&fit=crop" alt="Toyota Vios" class="car-image">
                <div class="booking-details">
                    <div class="car-info">
                        <h3>Toyota Vios 2020</h3>
                        <p>Toyota Vios</p>
                    </div>
                    <div class="price">RM350</div>
                    <div class="dates">24-26 Dec 2025</div>
                    <div class="status upcoming">Upcoming</div>
                    <button class="action-btn" onclick="showModifyModal()">Modify/Cancel</button>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title cancelled">Cancelled</div>
            <div class="booking-card">
                <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=300&h=200&fit=crop" alt="Proton Saga" class="car-image">
                <div class="booking-details">
                    <div class="car-info">
                        <h3>Proton Saga 2019</h3>
                        <p>Proton Saga</p>
                    </div>
                    <div class="price">RM180</div>
                    <div class="dates">01-03 Nov 2025</div>
                    <div class="status cancelled">Cancelled</div>
                    <a href="booking-form.html" class="action-btn">Rebook</a>
                </div>
            </div>
        </div>

        <div class="pagination">
            <button class="page-btn" disabled>&lt;</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">&gt;</button>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Booking Details</h2>
                <button class="close-btn" onclick="closeModal('detailsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <img src="https://images.unsplash.com/photo-1597404294360-fcd6e48f0eb9?w=400&h=250&fit=crop" alt="Perodua Axia" class="car-detail-image">
                
                <div class="detail-section">
                    <h3>Vehicle Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Vehicle</span>
                        <span class="detail-value">Perodua Axia 2018</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type</span>
                        <span class="detail-value">Hatchback</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">License Plate</span>
                        <span class="detail-value">WXY 1234</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Transmission</span>
                        <span class="detail-value">Automatic</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Seats</span>
                        <span class="detail-value">5</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Booking Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Booking ID</span>
                        <span class="detail-value">#BK20251212001</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pickup Date & Time</span>
                        <span class="detail-value">12 Dec 2025, 10:00 AM</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Return Date & Time</span>
                        <span class="detail-value">14 Dec 2025, 10:00 AM</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">2 Days</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pickup Location</span>
                        <span class="detail-value">HASTA Headquarters, Kuala Lumpur</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Return Location</span>
                        <span class="detail-value">HASTA Headquarters, Kuala Lumpur</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Payment Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Daily Rate</span>
                        <span class="detail-value">RM120/day</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Number of Days</span>
                        <span class="detail-value">2</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Insurance</span>
                        <span class="detail-value">RM20</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tax (6%)</span>
                        <span class="detail-value">RM14.40</span>
                    </div>
                    <div class="total-section">
                        <div class="total-row">
                            <span>Total Amount</span>
                            <span>RM240.00</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Customer Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name</span>
                        <span class="detail-value">Aminah Talib</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone</span>
                        <span class="detail-value">+60 12-345 6789</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">aminah.talib@email.com</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('detailsModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Modify/Cancel Modal -->
    <div id="modifyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modify or Cancel Booking</h2>
                <button class="close-btn" onclick="closeModal('modifyModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="info-message">
                    <strong>Current Booking:</strong> Toyota Vios 2020<br>
                    <strong>Dates:</strong> 24-26 Dec 2025<br>
                    <strong>Total:</strong> RM350
                </div>

                <div class="detail-section">
                    <h3>Modify Booking</h3>
                    
                    <div class="date-inputs">
                        <div class="form-group">
                            <label for="pickupDate">Pickup Date</label>
                            <input type="date" id="pickupDate" value="2025-12-24">
                        </div>
                        <div class="form-group">
                            <label for="returnDate">Return Date</label>
                            <input type="date" id="returnDate" value="2025-12-26">
                        </div>
                    </div>

                    <div class="date-inputs">
                        <div class="form-group">
                            <label for="pickupTime">Pickup Time</label>
                            <input type="time" id="pickupTime" value="10:00">
                        </div>
                        <div class="form-group">
                            <label for="returnTime">Return Time</label>
                            <input type="time" id="returnTime" value="10:00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pickupLocation">Pickup Location</label>
                        <select id="pickupLocation">
                            <option>HASTA Headquarters, Kuala Lumpur</option>
                            <option>HASTA Petaling Jaya Branch</option>
                            <option>HASTA KLIA Branch</option>
                            <option>HASTA Johor Bahru Branch</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="returnLocation">Return Location</label>
                        <select id="returnLocation">
                            <option>HASTA Headquarters, Kuala Lumpur</option>
                            <option>HASTA Petaling Jaya Branch</option>
                            <option>HASTA KLIA Branch</option>
                            <option>HASTA Johor Bahru Branch</option>
                        </select>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Cancel Booking</h3>
                    <div class="warning-message">
                        <strong>Cancellation Policy:</strong><br>
                        • Refund only for cancellation made in 24 hours before pickup<br>
                        • No refund if cancelled less than 24 hours before pickup<br>
                        • Refund will make in 2 weeks of working days
                    </div>

                    <div class="form-group">
                        <label for="cancellationReason">Reason for Cancellation</label>
                        <textarea id="cancellationReason" placeholder="Optional"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('modifyModal')">Close</button>
                <button class="btn-danger" onclick="confirmCancel()">Cancel Booking</button>
                <button class="btn-primary" onclick="saveModification()">Save Changes</button>
            </div>
        </div>
    </div>

    <script>
        function showDetailsModal() {
            document.getElementById('detailsModal').classList.add('active');
        }

        function showModifyModal() {
            document.getElementById('modifyModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function confirmCancel() {
            if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                alert('Booking cancelled successfully. Refund will be processed within 2 weeks of working days.');
                closeModal('modifyModal');
            }
        }

        function saveModification() {
            alert('Booking modified successfully! A confirmation email has been sent to you.');
            closeModal('modifyModal');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>
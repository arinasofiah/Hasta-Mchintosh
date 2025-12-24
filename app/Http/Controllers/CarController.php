<?php
require_once "models/Car.php";
require_once "config/database.php";

// Connect to DB
$database = new Database();
$db = $database->getConnection();
$carModel = new Car($db);

// Simple router using "action" query param
$action = $_GET['action'] ?? '';

header("Content-Type: application/json");

switch ($action) {

    // Get available cars
    case 'available':
        $stmt = $carModel->getAvailableCars();
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cars);
        break;

    // Filter cars by type
    case 'filter':
        $type = $_GET['type'] ?? 'all';
        if ($type === 'all') {
            $stmt = $carModel->getAvailableCars();
        } else {
            $stmt = $carModel->getCarsByType($type);
        }
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cars);
        break;

    // Book a car
    case 'book':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data || !isset($data['car_id'])) {
            echo json_encode(['error' => 'Car ID required']);
            exit;
        }

        $carModel->id = $data['car_id'];
        if (!$carModel->getCarById()) {
            echo json_encode(['error' => 'Car not found']);
            exit;
        }

        if (!$carModel->available) {
            echo json_encode(['error' => 'Car not available']);
            exit;
        }

        // Calculate rental days
        $pickup = new DateTime($data['pickup_date']);
        $return = new DateTime($data['return_date']);
        $days = max(1, $pickup->diff($return)->days);

        $totalCost = $carModel->price * $days;

        // Save booking to DB
        $stmt = $db->prepare("INSERT INTO bookings
            (car_id, customer_name, customer_email, customer_phone, pickup_date, pickup_time, return_date, return_time, total_cost)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $carModel->id,
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['pickup_date'],
            $data['pickup_time'],
            $data['return_date'],
            $data['return_time'],
            $totalCost
        ]);

        $bookingId = $db->lastInsertId();

        // Mark car unavailable
        $carModel->available = 0;
        $carModel->updateAvailability();

        echo json_encode([
            'success' => true,
            'booking_id' => $bookingId,
            'car_name' => $carModel->name,
            'days' => $days,
            'total_cost' => $totalCost
        ]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}

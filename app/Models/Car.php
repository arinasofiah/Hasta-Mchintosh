<?php
// backend/models/Car.php

class Car {
    private $conn;
    private $table_name = "cars";

    public $id;
    public $name;
    public $type;
    public $price;
    public $currency;
    public $image;
    public $available;
    public $featured;
    public $passengers;
    public $bags;
    public $ac;
    public $auto;
    public $fuel;
    public $rating;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all cars
    public function getAllCars() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY featured DESC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get available cars
    public function getAvailableCars() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE available = 1 ORDER BY featured DESC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single car by ID
    public function getCarById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->type = $row['type'];
            $this->price = $row['price'];
            $this->currency = $row['currency'];
            $this->image = $row['image'];
            $this->available = $row['available'];
            $this->featured = $row['featured'];
            $this->passengers = $row['passengers'];
            $this->bags = $row['bags'];
            $this->ac = $row['ac'];
            $this->auto = $row['auto'];
            $this->fuel = $row['fuel'];
            $this->rating = $row['rating'];
            return true;
        }
        
        return false;
    }

    // Filter cars by type
    public function getCarsByType($type) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE type = ? AND available = 1 ORDER BY price ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $type);
        $stmt->execute();
        return $stmt;
    }

    // Filter cars by price range
    public function getCarsByPriceRange($min_price, $max_price) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE price BETWEEN ? AND ? AND available = 1 ORDER BY price ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $min_price);
        $stmt->bindParam(2, $max_price);
        $stmt->execute();
        return $stmt;
    }

    // Check availability for date range
    public function checkAvailability($car_id, $pickup_date, $return_date) {
        $query = "SELECT COUNT(*) as booking_count FROM bookings 
                  WHERE car_id = ? 
                  AND status != 'cancelled'
                  AND (
                      (pickup_date <= ? AND return_date >= ?) OR
                      (pickup_date <= ? AND return_date >= ?) OR
                      (pickup_date >= ? AND return_date <= ?)
                  )";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $car_id);
        $stmt->bindParam(2, $pickup_date);
        $stmt->bindParam(3, $pickup_date);
        $stmt->bindParam(4, $return_date);
        $stmt->bindParam(5, $return_date);
        $stmt->bindParam(6, $pickup_date);
        $stmt->bindParam(7, $return_date);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['booking_count'] == 0;
    }

    // Update car availability
    public function updateAvailability() {
        $query = "UPDATE " . $this->table_name . " SET available = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->available);
        $stmt->bindParam(2, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
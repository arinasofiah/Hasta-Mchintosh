<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RestoreLegacyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to ensure smooth insertion
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Users
        DB::table('users')->insertOrIgnore([
            [
                'userID' => 1,
                'email' => 'fifimede1504@gmail.com',
                'password' => '$2y$12$6K4s1FheL1u0Gz.0bLkMcu5YJeKJ2PoE.dnmE7pSkK/R7zd0TX2Yu',
                'name' => 'AIMAN BIN ALI',
                'icNumber' => '050106-12-5821',
                'userType' => 'customer',
                'remember_token' => NULL,
                'created_at' => '2025-12-29 03:18:59',
                'updated_at' => '2025-12-29 03:18:59',
            ],
            [
                'userID' => 2,
                'email' => 'admin@example.com',
                'password' => '$2y$12$6K4s1FheL1u0Gz.0bLkMcu5YJeKJ2PoE.dnmE7pSkK/R7zd0TX2Yu',
                'name' => 'administrator',
                'icNumber' => '000000-00-0000',
                'userType' => 'admin',
                'remember_token' => NULL,
                'created_at' => '2025-12-29 11:34:13',
                'updated_at' => '2025-12-29 03:35:23',
            ],
        ]);

        // 2. Customer
        DB::table('customer')->insertOrIgnore([
            [
                'userID' => 1,
                'matricNumber' => 'A24CS0225',
                'licenseNumber' => NULL,
                'college' => NULL,
                'faculty' => NULL,
                'depoBalance' => 0.00,
                'isBlacklisted' => 0,
                'blacklistReason' => NULL,
                'created_at' => '2025-12-29 03:18:59',
                'updated_at' => '2025-12-29 03:18:59',
            ],
        ]);

        // 3. Vehicles
        DB::table('vehicles')->insertOrIgnore([
            [
                'vehicleID' => 1,
                'vehicleType' => 'Hatchback',
                'model' => 'Perodua Axia 2018',
                'plateNumber' => 'HAB1234',
                'fuelLevel' => 85,
                'fuelType' => 'Petrol',
                'ac' => 1,
                'seat' => 5,
                'status' => 'available',
                'pricePerHour' => 15.00,
                'pricePerDay' => 120.00,
                'created_at' => '2025-12-29 12:34:33',
                'updated_at' => '2025-12-29 12:34:33',
            ],
            [
                'vehicleID' => 2,
                'vehicleType' => 'Hatchback',
                'model' => 'Perodua Myvi 2015',
                'plateNumber' => 'HAB1235',
                'fuelLevel' => 80,
                'fuelType' => 'Petrol',
                'ac' => 1,
                'seat' => 5,
                'status' => 'available',
                'pricePerHour' => 15.00,
                'pricePerDay' => 120.00,
                'created_at' => '2025-12-29 12:34:33',
                'updated_at' => '2025-12-29 12:34:33',
            ],
            [
                'vehicleID' => 3,
                'vehicleType' => 'Hatchback',
                'model' => 'Perodua Myvi 2020',
                'plateNumber' => 'HAB1236',
                'fuelLevel' => 90,
                'fuelType' => 'Petrol',
                'ac' => 1,
                'seat' => 5,
                'status' => 'available',
                'pricePerHour' => 18.75,
                'pricePerDay' => 150.00,
                'created_at' => '2025-12-29 12:34:33',
                'updated_at' => '2025-12-29 12:34:33',
            ],
            [
                'vehicleID' => 4,
                'vehicleType' => 'Hatchback',
                'model' => 'Perodua Axia 2024',
                'plateNumber' => 'HAB1237',
                'fuelLevel' => 95,
                'fuelType' => 'Petrol',
                'ac' => 1,
                'seat' => 5,
                'status' => 'available',
                'pricePerHour' => 16.25,
                'pricePerDay' => 130.00,
                'created_at' => '2025-12-29 12:34:33',
                'updated_at' => '2025-12-29 12:34:33',
            ],
            [
                'vehicleID' => 5,
                'vehicleType' => 'Sedan',
                'model' => 'Perodua Bezza 2018',
                'plateNumber' => 'SED1234',
                'fuelLevel' => 75,
                'fuelType' => 'Petrol',
                'ac' => 1,
                'seat' => 5,
                'status' => 'available',
                'pricePerHour' => 17.50,
                'pricePerDay' => 140.00,
                'created_at' => '2025-12-29 12:34:33',
                'updated_at' => '2025-12-29 12:34:33',
            ],
        ]);

        // Enable foreign key checks back
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

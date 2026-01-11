<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update bookingStatus enum to include 'reserved'
        DB::statement("ALTER TABLE booking MODIFY COLUMN bookingStatus ENUM('pending', 'confirmed', 'approved', 'cancelled', 'completed', 'reserved') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values (remove 'reserved')
        DB::statement("ALTER TABLE booking MODIFY COLUMN bookingStatus ENUM('pending', 'confirmed', 'approved', 'cancelled', 'completed') DEFAULT 'pending'");
    }
};
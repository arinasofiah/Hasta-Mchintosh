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
        // Update vehicles status enum to include 'reserved'
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'rented', 'maintenance', 'reserved') DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values (remove 'reserved')
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'rented', 'maintenance') DEFAULT 'available'");
    }
};
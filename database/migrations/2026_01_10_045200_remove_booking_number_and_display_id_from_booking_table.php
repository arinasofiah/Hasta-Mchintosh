<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Check if column exists before dropping
            if (Schema::hasColumn('booking', 'booking_number')) {
                $table->dropColumn('booking_number');
            }
            
            if (Schema::hasColumn('booking', 'display_id')) {
                $table->dropColumn('display_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Optionally restore columns in down() method
            $table->string('booking_number', 20)->nullable()->after('bookingID');
            $table->string('display_id', 20)->nullable()->after('booking_number');
        });
    }
};
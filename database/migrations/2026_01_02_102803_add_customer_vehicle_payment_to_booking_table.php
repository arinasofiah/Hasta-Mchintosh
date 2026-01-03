<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Add foreign key columns if they don't exist
            if (!Schema::hasColumn('booking', 'customerID')) {
                $table->foreignId('customerID')->nullable()->constrained('customer', 'userID');
            }
            
            if (!Schema::hasColumn('booking', 'vehicleID')) {
                $table->foreignId('vehicleID')->nullable()->constrained('vehicles', 'vehicleID');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('booking', function (Blueprint $table) {
            // Remove the columns if they exist
            if (Schema::hasColumn('booking', 'customerID')) {
                $table->dropForeign(['customerID']);
                $table->dropColumn('customerID');
            }
            
            if (Schema::hasColumn('booking', 'vehicleID')) {
                $table->dropForeign(['vehicleID']);
                $table->dropColumn('vehicleID');
            }
        });
    }
};

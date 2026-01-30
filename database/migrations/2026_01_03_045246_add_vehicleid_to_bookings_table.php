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
            if (!Schema::hasColumn('booking', 'vehicleID')) {
                $table->unsignedBigInteger('vehicleID')->after('bookingID');
                $table->foreign('vehicleID')->references('vehicleID')->on('vehicles')->onDelete('cascade');
            }
            if (!Schema::hasColumn('booking', 'customerID')) {
                $table->unsignedBigInteger('customerID')->after('vehicleID');
                 // Note: referencing 'users' table based on other migrations
                $table->foreign('customerID')->references('userID')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['vehicleID']);
            $table->dropColumn('vehicleID');
            $table->dropForeign(['customerID']);
            $table->dropColumn('customerID');
        });
    }
};

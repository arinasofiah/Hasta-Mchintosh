<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pickup', function (Blueprint $table) {
        // 1. Create the column (Must match bookingID type: unsignedBigInteger)
        $table->unsignedBigInteger('bookingID')->after('pickupID')->nullable();

        // 2. Set the Foreign Key relationship
        $table->foreign('bookingID')
              ->references('bookingID')
              ->on('booking')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('pickup', function (Blueprint $table) {
        // Drop the constraint first, then the column
        $table->dropForeign(['bookingID']);
        $table->dropColumn('bookingID');
    });
}
};

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
    Schema::table('return', function (Blueprint $table) {
        // 1. Add the column (matching the primary key type of the booking table)
        $table->unsignedBigInteger('bookingID')->after('returnID')->nullable();

        // 2. Create the Foreign Key constraint
        $table->foreign('bookingID')
              ->references('bookingID')
              ->on('booking')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('return', function (Blueprint $table) {
        // Drop the constraint first, then the column
        $table->dropForeign(['bookingID']);
        $table->dropColumn('bookingID');
    });
}
};

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
       Schema::table('payment', function (Blueprint $table) {
        // Add the bookingID column
        $table->unsignedBigInteger('bookingID')->after('paymentID');
        
        // Add foreign key constraint
        $table->foreign('bookingID')
              ->references('bookingID')
              ->on('booking')
              ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
        $table->dropForeign(['bookingID']);
        $table->dropColumn('bookingID');
        });
    }
};

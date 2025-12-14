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
        Schema::create('booking', function (Blueprint $table) {
            $table->id('bookingID');

            $table->string('bankNum');
            $table->string('penamaBank');
            $table->date('startDate');
            $table->data('endDate');
            $table->integer('bookingDuration');
            $table->enum('bookingStatus', ['pending', 'approved', 'cancelled', 'completed']);
            $table->decimal('totalPrice', 8, 2);
            $table->decimal('depositAmount', 8, 2);
            $table->boolean('rewardApplied')->default(false);
            $table->boolean('isStaffBooking')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};

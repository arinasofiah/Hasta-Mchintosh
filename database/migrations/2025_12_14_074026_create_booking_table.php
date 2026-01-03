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
      $table->unsignedBigInteger('vehicleID');
      $table->unsignedBigInteger('customerID');
      $table->string('bankNum')->nullable();
      $table->string('penamaBank')->nullable();
      $table->date('startDate');
      $table->date('endDate');
      $table->integer('bookingDuration')->nullable();
      $table->enum('bookingStatus', ['pending', 'confirmed', 'approved', 'cancelled', 'completed'])->default('pending');
      $table->decimal('totalPrice', 10, 2)->nullable();
      $table->decimal('depositAmount', 10, 2)->nullable();
      $table->boolean('rewardApplied')->default(false);
      $table->timestamp('reservation_expires_at')->nullable();
      $table->timestamps();

      $table->foreign('vehicleID')->references('vehicleID')->on('vehicles')->onDelete('cascade');
      $table->foreign('customerID')->references('userID')->on('users')->onDelete('cascade');
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

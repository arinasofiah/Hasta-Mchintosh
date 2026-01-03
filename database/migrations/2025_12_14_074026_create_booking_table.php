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
        Schema::create('booking', function (Blueprint $table) {
            $table->increments('bookingID'); // Custom primary key as per your model
            $table->unsignedInteger('vehicleID'); // Foreign key to vehicles.vehicleID
            $table->unsignedInteger('customerID'); // Foreign key to users.userID (assuming User model has userID as primary key)
            $table->date('startDate');
            $table->date('endDate');
            $table->integer('bookingDuration')->nullable(); // In hours
            $table->enum('bookingStatus', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->decimal('totalPrice', 10, 2)->nullable();
            $table->decimal('depositAmount', 10, 2)->nullable();
            $table->string('rewardApplied', 50)->nullable();
            $table->timestamp('reservation_expires_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('vehicleID')->references('vehicleID')->on('vehicles')->onDelete('cascade');
            $table->foreign('customerID')->references('userID')->on('users')->onDelete('cascade'); // Assumes users table has userID as primary key
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking');
    }
};

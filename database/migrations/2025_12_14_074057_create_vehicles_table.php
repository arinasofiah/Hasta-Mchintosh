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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicleID');

            $table->string('vehicleType', 50);
            $table->string('model', 100);
            $table->string('plateNumber', 20)->unique();
            $table->integer('fuelLevel');
            $table->string('fuelType');
            $table->boolean('ac')->default(false);
            $table->integer('seat');
            $table->string('transmission');
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->decimal('pricePerHour', 8, 2);
            $table->decimal('pricePerDay', 8, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

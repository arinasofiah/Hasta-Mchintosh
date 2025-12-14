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
        Schema::create('return', function (Blueprint $table) {
            $table->id('returnID');

            $table->date('returnDate');
            $table->string('returnLocation', 255); 
            $table->string('returnPhoto');
            $table->boolean('isFined')->default(false);
            $table->string('trafficTicketPhoto')->nullable();
            $table->integer('fuelAmount');
            $table->integer('lateHours')->default(0);
            $table->text('feedback');
            $table->decimal('refundCalculated', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return');
    }
};

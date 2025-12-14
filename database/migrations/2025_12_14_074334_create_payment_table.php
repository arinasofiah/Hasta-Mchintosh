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
        Schema::create('payment', function (Blueprint $table) {
            $table->id('paymentID');

            $table->string('bankName', 20);
            $table->decimal('amount', 10, 2);
            $table->string('qrPayment');
            $table->string('receiptImage');
            $table->enum('paymentStatus', ['pending', 'completed', 'failed'])->default('pending');
            $table->date('paymentDate');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};

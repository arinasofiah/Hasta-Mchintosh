<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_card_voucher', function (Blueprint $table) {
            $table->id();

            $table->string('matricNumber');
            $table->foreign('matricNumber')
                  ->references('matricNumber')
                  ->on('loyalty_card')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('voucher_id');
            $table->foreign('voucher_id')
                  ->references('voucherCode') 
                  ->on('voucher')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_card_voucher');
    }
};
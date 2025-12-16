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
        Schema::create('loyalty_card', function (Blueprint $table) {
            $table->id('matricNumber');
            $table->integer('stampCount');
            $table->boolean('rewardEligible')->default(false);
            $table->string('voucherCode',100);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_card');
    }
};

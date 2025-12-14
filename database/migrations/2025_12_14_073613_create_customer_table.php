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
        Schema::create('customer', function (Blueprint $table) {
            $table->id('matricNumber');

            $table->string('name');
            $table->string('licenseNumber');
            $table->string('icNumber');
            $table->string('phoneNumber');
            $table->string('college');
            $table->string('faculty');
            $table->float('depoBalance', 8, 2);
            $table->boolean('isBlacklisted')->default(false);
            $table->string('blacklistReason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};

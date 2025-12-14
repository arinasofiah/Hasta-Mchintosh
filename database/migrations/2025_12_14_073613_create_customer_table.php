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
            $table->id();

            $table->string('matric_number')->unique();
            $table->string('name');
            $table->string('license_number');
            $table->string('ic_number');
            $table->string('phone_number');
            $table->string('college');
            $table->string('faculty');
            $table->decimal('deposit_balance', 8, 2);
            $table->boolean('is_blacklisted')->default(false);
            $table->string('blacklist_reason')->nullable();

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

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
        // Link to User table: userID is the PK and FK
        $table->foreignId('userID')->primary()->constrained('users', 'userID')->onDelete('cascade');
        
        // Attributes unique to Customer
        $table->string('matricNumber')->unique()->nullable(); 
        $table->string('licenseNumber')->nullable();
        $table->string('college')->nullable();
        $table->string('faculty')->nullable();
        $table->decimal('depoBalance', 10, 2)->default(0.00);
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

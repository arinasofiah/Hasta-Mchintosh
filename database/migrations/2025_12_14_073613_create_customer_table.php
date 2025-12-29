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
    Schema::create('customers', function (Blueprint $table) {
    // Primary Key as a string
    $table->string('matricNumber')->primary(); 

    $table->foreignId('userID')->unique()->constrained('users', 'userID')->onDelete('cascade');

    // These remain nullable so they can be filled after registration
    $table->string('licenseNumber')->nullable();
    $table->string('college')->nullable();
    $table->string('faculty')->nullable();
    $table->decimal('depoBalance', 10, 2)->default(0.00);
    $table->boolean('isBlacklisted')->default(false);
    
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

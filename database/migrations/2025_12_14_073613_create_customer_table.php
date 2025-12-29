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
        
        $table->string('matricNumber')->primary(); 
        
        
        $table->foreignId('userID')->unique()->constrained('users', 'userID')->onDelete('cascade');

        
        $table->string('licenseNumber');
        $table->string('college');
        $table->string('faculty');
        $table->decimal('depoBalance', 10, 2)->default(0);
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

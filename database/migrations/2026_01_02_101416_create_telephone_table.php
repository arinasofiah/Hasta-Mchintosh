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
        Schema::create('telephone', function (Blueprint $table) {
            // phoneNumber is the Primary Key {PK}
            $table->string('phoneNumber')->primary(); 
            // Add any other phone-specific columns if needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telephone');
    }
};

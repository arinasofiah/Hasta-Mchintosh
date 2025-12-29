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
            $table->string('matricNumber')->primary(); 

            $table->integer('stampCount')->default(0);
            $table->boolean('rewardEligible')->default(false);
            $table->decimal('commissionAmount', 8, 2)->default(0.00);

            $table->foreign('matricNumber')
                ->references('matricNumber')
                ->on('customer')
                ->onDelete('cascade');

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

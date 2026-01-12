<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('return', function (Blueprint $table) {
            $table->time('actual_return_time')->nullable(); // Actual time they came back
            $table->decimal('late_fee', 10, 2)->default(0.00);
            $table->decimal('fuel_fee', 10, 2)->default(0.00);
            $table->decimal('total_fee', 10, 2)->default(0.00);
        });
    }

    public function down()
    {
        Schema::table('return', function (Blueprint $table) {
            $table->dropColumn(['actual_return_time', 'late_fee', 'fuel_fee', 'total_fee']);
        });
    }
};

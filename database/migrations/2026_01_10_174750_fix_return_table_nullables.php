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
    Schema::table('return', function (Blueprint $table) {
        // This tells the database these fields ARE NOT required at the start
        $table->string('photo_front')->nullable()->change();
        $table->string('photo_back')->nullable()->change();
        $table->string('photo_left')->nullable()->change();
        $table->string('photo_right')->nullable()->change();
        $table->string('photo_dashboard')->nullable()->change();
        $table->string('photo_keys')->nullable()->change();
        $table->string('fuelAmount')->nullable()->change();
        $table->boolean('isfined')->default(0)->change();
        $table->string('trafficTicketPhoto')->nullable()->change();
    });
}
};

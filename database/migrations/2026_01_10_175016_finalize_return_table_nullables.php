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
        // Making all fields that caused errors nullable
        $table->text('feedback')->nullable()->change();
        $table->date('returnDate')->nullable()->change();
        $table->string('returnLocation')->nullable()->change();
        $table->string('fuelAmount')->nullable()->change();
    });
}
};

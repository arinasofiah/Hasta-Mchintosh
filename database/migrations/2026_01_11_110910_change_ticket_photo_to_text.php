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
            // Change from VARCHAR(255) to TEXT to fit many paths
            $table->text('trafficTicketPhoto')->nullable()->change();
        });
    }
};

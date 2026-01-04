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
    Schema::table('pickup', function (Blueprint $table) {
        $table->time('pickupTime')->nullable()->after('pickupDate');
    });
}

public function down(): void
{
    Schema::table('pickup', function (Blueprint $table) {
        $table->dropColumn('pickupTime');
    });
}
};

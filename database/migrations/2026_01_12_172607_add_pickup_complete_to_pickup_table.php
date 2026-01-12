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
        // Default to false (0) since a new booking hasn't been picked up yet
        $table->boolean('pickupComplete')->default(false)->after('signature_path');
    });
}

public function down(): void
{
    Schema::table('pickup', function (Blueprint $table) {
        $table->dropColumn('pickupComplete');
    });
}
};

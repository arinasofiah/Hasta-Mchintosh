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
        Schema::table('customer', function (Blueprint $table) {
            // Just add the file paths - nothing else
            $table->string('ic_passport_path')->nullable()->after('faculty');
            $table->string('driving_license_path')->nullable()->after('ic_passport_path');
            $table->string('matric_card_path')->nullable()->after('driving_license_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            // Simply drop the columns
            $table->dropColumn([
                'ic_passport_path',
                'driving_license_path',
                'matric_card_path',
            ]);
        });
    }
};
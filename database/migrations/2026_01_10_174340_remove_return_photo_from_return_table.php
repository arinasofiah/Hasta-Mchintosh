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
        // This drops the column causing the error
        $table->dropColumn('returnPhoto');
    });
}

public function down(): void
{
    Schema::table('return', function (Blueprint $table) {
        // This allows you to "undo" the change if needed
        $table->string('returnPhoto')->nullable();
    });
}
};

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
    Schema::table('payment', function (Blueprint $table) {
        // This removes the column from the 'payment' table
        $table->dropColumn('qrPayment');
    });
}

public function down(): void
{
    Schema::table('payment', function (Blueprint $table) {
        // This allows you to undo the change if needed
        $table->string('qrPayment')->nullable();
    });
}
};

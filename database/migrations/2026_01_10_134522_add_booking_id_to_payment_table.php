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
            
            
            // Add bankOwnerName if it doesn't exist
            if (!Schema::hasColumn('payment', 'bankOwnerName')) {
                $table->string('bankOwnerName', 100)->nullable()->after('bankName');
            }
            
            // Add paymentType if it doesn't exist
            if (!Schema::hasColumn('payment', 'paymentType')) {
                $table->enum('paymentType', ['deposit', 'full', 'remaining'])->default('deposit')->after('amount');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
           
            $table->dropColumn(['bankOwnerName', 'paymentType']);
        });
    }
};
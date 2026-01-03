<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Remove phoneNumber column from users table (if it exists)
        if (Schema::hasColumn('users', 'phoneNumber')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop foreign key first
                $table->dropForeign(['phoneNumber']);
                // Then drop column
                $table->dropColumn('phoneNumber');
            });
        }
        
        // Step 2: Add userID to telephone table if not exists
        if (!Schema::hasColumn('telephone', 'userID')) {
            Schema::table('telephone', function (Blueprint $table) {
                $table->unsignedBigInteger('userID')->after('phoneNumber');
                $table->foreign('userID')
                      ->references('userID')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // Optional: Reverse if needed
    }
};
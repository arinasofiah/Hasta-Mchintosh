<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update customerID using userID for records where customerID is 0 or NULL and userID is set
        DB::table('booking')
            ->where(function($query) {
                $query->whereNull('customerID')
                      ->orWhere('customerID', 0);
            })
            ->whereNotNull('userID')
            ->where('userID', '!=', 0)
            ->update(['customerID' => DB::raw('userID')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed as this is a data fix
    }
};

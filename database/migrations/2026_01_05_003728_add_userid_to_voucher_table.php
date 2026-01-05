<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('voucher', function (Blueprint $table) {
            $table->integer('userID')->nullable()->after('voucherCode'); 
        });
    }

    public function down()
    {
        Schema::table('voucher', function (Blueprint $table) {
            $table->dropColumn('userID');
        });
    }
};

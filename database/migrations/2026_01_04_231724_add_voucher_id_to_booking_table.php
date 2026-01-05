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
        if (!Schema::hasColumn('booking', 'voucher_id')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->bigInteger('voucher_id')->nullable()->after('promo_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('booking', 'voucher_id')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropColumn('voucher_id');
            });
        }
    }
};

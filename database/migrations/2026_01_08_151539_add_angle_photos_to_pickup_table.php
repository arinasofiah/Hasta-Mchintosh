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
    Schema::table('pickup', function (Blueprint $table) {
        // Adding the 4 specific columns after the existing pickupPhoto column
        $table->string('photo_front')->nullable()->after('pickupPhoto');
        $table->string('photo_back')->nullable()->after('photo_front');
        $table->string('photo_left')->nullable()->after('photo_back');
        $table->string('photo_right')->nullable()->after('photo_left');
    });
}

public function down()
{
    Schema::table('pickup', function (Blueprint $table) {
        $table->dropColumn(['photo_front', 'photo_back', 'photo_left', 'photo_right']);
    });
}
};

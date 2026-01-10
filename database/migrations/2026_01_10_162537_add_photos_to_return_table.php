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
        Schema::table('return', function (Blueprint $table) {
            $table->string('photo_front')->nullable();
            $table->string('photo_back')->nullable();
            $table->string('photo_left')->nullable();
            $table->string('photo_right')->nullable();
            $table->string('photo_dashboard')->nullable();
            $table->string('photo_keys')->nullable();
        });
    }

    public function down()
    {
        Schema::table('return', function (Blueprint $table) {
            $table->dropColumn(['photo_front', 'photo_back', 'photo_left', 'photo_right', 'photo_dashboard', 'photo_keys']);
        });
}
};

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
        // Add column for the signature image path
        $table->string('signature_path')->nullable()->after('photo_right');
        // Optional: Remove the old checkbox column
        $table->dropColumn('agreementForm'); 
    });
}

public function down()
{
    Schema::table('pickup', function (Blueprint $table) {
        $table->dropColumn('signature_path');
        $table->integer('agreementForm')->default(0);
    });
}
};

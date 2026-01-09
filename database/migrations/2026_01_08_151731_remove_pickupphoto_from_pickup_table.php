<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('pickup', function (Blueprint $table) {
        // Remove the old single photo column
        $table->dropColumn('pickupPhoto');
    });
}

public function down()
{
    Schema::table('pickup', function (Blueprint $table) {
        // This allows you to undo the migration if needed
        $table->string('pickupPhoto')->nullable()->after('pickupLocation');
    });
}
};

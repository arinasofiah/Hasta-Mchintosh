<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promotion', function (Blueprint $table) {
            $table->string('code')->nullable()->after('promoID');
            $table->string('title')->nullable()->after('code');  
            $table->text('description')->nullable()->after('title'); 
            
            $table->enum('discountType', ['percentage', 'fixed'])->default('fixed')->after('discountValue');

            $table->string('applicableModel')->default('All')->after('applicableDays');
        });
    }

    public function down(): void
    {
        Schema::table('promotion', function (Blueprint $table) {
            $table->dropColumn(['code', 'title', 'description', 'discountType', 'applicableModel']);
        });
    }
};
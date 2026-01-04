<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_card_promotion', function (Blueprint $table) {
            $table->id(); // 每张领取的券都有唯一的编号
            
            // 1. 谁领的？(关联 loyalty_card)
            $table->string('matricNumber');
            $table->foreign('matricNumber')
                  ->references('matricNumber')
                  ->on('loyalty_card')
                  ->onDelete('cascade');

            // 2. 领了什么？(关联 promotion)
            // 注意：你的 Promotion 模型主键是 promoID
            $table->unsignedBigInteger('promotion_id');
            $table->foreign('promotion_id')
                  ->references('promoID') 
                  ->on('promotion')
                  ->onDelete('cascade');

            // 3. 用过了吗？
            $table->boolean('is_used')->default(false);

            $table->timestamps(); // 记录领取时间
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_card_promotion');
    }
};
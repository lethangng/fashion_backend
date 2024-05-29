<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên gợi nhớ');
            $table->string('code')->nullable();
            $table->integer('price')->default(0)->comment('Giá giảm: Là phần trăm hoặc giá cụ thể');
            $table->integer('for_sum')->default(0)->comment('Điều kiện để giảm giá là tổng đơn là bao nhiêu tiền');
            $table->integer('coupon_type')->default(0)->comment('0: Fix giá (giảm giá cụ thể ví dụ 500k / 1 đơn) | 1: Giảm bao nhiêu % cho 1 đơn');
            $table->date('expired')->nullable()->comment('Ngày cuối cùng mã này có thể sử dụng (hết hạn)');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

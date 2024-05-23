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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->double('total_price')->nullable();
            $table->unsignedBigInteger('delivery_address_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->timestamp('order_date')->nullable()->comment('Ngay dat hang');
            $table->timestamp('delivery_date')->nullable()->comment('Ngay giao hang');
            $table->integer('status')->nullable()->comment('Trang thai don hang: Đang xử lý|Đã giao|Đã hủy|  0: mới tiếp nhận, 2: đang xử lý, 3: chuyển qua kho đóng gói, 4: đang giao hàng, 1: hoàn tất');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

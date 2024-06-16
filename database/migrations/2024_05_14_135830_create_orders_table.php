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
            $table->integer('total_price')->default(0);
            $table->string('delivery_address')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('price_off')->default(0);
            // $table->timestamp('order_date')->nullable()->comment('Ngay dat hang');
            // $table->timestamp('delivery_date')->nullable()->comment('Ngay giao hang');
            $table->integer('status')->default(0)->comment('Trang thai don hang:  0: mới tiếp nhận, 1: đang xử lý, 2: chuyển qua kho đóng gói, 3: đang giao hàng, 4: hoàn tất, 5: đã hủy,');
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

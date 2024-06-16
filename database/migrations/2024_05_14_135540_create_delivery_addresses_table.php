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
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('fullname')->nullable()->comment('Tên người nhận');
            $table->string('phone_number')->nullable()->comment('Số điện thoại người nhận');
            $table->string('city')->nullable()->comment('Tinh/Thanh pho');
            $table->string('address')->nullable()->comment('Vi tri');
            $table->boolean('is_select')->default(0)->comment('Dia chi mac dinh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_addresses');
    }
};

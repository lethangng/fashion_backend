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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('colors')->nullable();
            $table->string('sizes')->nullable();
            $table->integer('import_price')->default(0)->comment('Giá nhập');
            $table->boolean('newest')->default(1)->comment('1: mới nhất | 0: không phải');
            $table->string('image')->nullable();
            $table->text('list_images')->nullable();
            $table->boolean('status')->default(1)->comment('1: còn hàng | 0: hết hàng');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

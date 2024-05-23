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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('u_id')->nullable();
            $table->string('fullname');
            $table->string('image')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email');
            $table->string('password')->nullable();
            $table->boolean('role')->nullable()->comment('0 = admin || 1 = user');
            $table->string('login_type')->nullable()->comment('password || facebook || google');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

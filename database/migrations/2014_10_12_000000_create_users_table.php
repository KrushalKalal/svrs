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
            $table->string('member_code', 30)->unique();
            $table->string('sponsor_id', 30)->nullable()->index();
            $table->string('role')->default('member');
            $table->boolean('status')->default(true);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('password');
            $table->decimal('amount', 12, 2)->default(0)->nullable();
            $table->decimal('coin_price', 12, 2)->nullable();       
            $table->string('attachment')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('otp')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('fcm_token')->nullable();
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

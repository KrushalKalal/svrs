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
        Schema::create('coin_trades', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');   // who traded
            $table->unsignedBigInteger('coin_id');   // which coin

            $table->enum('type', ['buy', 'sell']);   // trade type

            $table->decimal('price', 12, 2);         // executed price
            $table->decimal('quantity', 12, 4);      // amount bought/sold

            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('coin_id')
                ->references('id')->on('coins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_trades');
    }
};

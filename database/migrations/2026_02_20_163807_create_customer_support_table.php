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
        Schema::create('customer_support', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');   // who submitted ticket
            $table->text('message');                 // user message
            $table->string('attachment')->nullable(); // uploaded file
            $table->text('reply')->nullable();       // admin reply (NEW FIELD)
            $table->timestamp('replied_at')->nullable(); // reply time (optional)
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_support');
    }
};

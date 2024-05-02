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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_id');
            $table->string('shop_name');
            $table->string('mobile')->unique();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('shop_address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('documents')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('status')->default(0);
            $table->string('remarks')->nullable();
            $table->string('token')->uniqid();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};

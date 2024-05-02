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
            $table->string('order_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('products_id');
            $table->float('price');
            $table->float('qty')->default(1);
            $table->string('unit');
            $table->string('total_price');
            $table->enum('status',['pending','complete','cancel'])->default('pending');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->foreign('products_id')->references('id')->on('products');
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

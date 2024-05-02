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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('c_logo')->nullable();
            $table->string('c_name')->nullable();
            $table->text('tnc')->nullable();
            $table->text('pp')->nullable();
            $table->string('c_address')->nullable();
            $table->string('c_mobile')->nullable();
            $table->string('c_email')->nullable();
            $table->text('footer')->nullable();
            $table->float('cgst')->nullable()->default(0);
            $table->float('sgst')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

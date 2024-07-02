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
        Schema::create('goods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('category');
            $table->string('color');
            $table->integer('rate');
            $table->string('size');
            $table->uuid('merk_id');
            $table->integer('ask_rate');
            $table->integer('bid_rate');
            $table->integer('ask_price');
            $table->integer('bid_price');
            $table->string('image')->nullable();
            $table->uuid('type_id');
            $table->uuid('tray_id')->nullable();
            $table->boolean('availability')->default(true);
            $table->boolean('safe_status');
            $table->timestamps();

            $table->foreign('merk_id')->references('id')->on('merks')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('goods_types')->onDelete('cascade');
            $table->foreign('tray_id')->references('id')->on('trays')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};

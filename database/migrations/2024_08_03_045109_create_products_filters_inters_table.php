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
        Schema::create('products_filters_inters', function (Blueprint $table) {
            $table->id('products_filters_inters_id');
            $table->unsignedBigInteger('products_id');
            $table->unsignedBigInteger('products_filters_id');
            $table->foreign('products_id')->references('products_id')->on('products')->onDelete('cascade');
            $table->foreign('products_filters_id')->references('products_filters_id')->on('products_filters')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_filters_inters');
    }
};

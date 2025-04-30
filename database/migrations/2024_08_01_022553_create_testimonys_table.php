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
        Schema::create('testimonys', function (Blueprint $table) {
            $table->id('testimonys_id');
            $table->string('title');
            $table->string('testimony');
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('services_id')->nullable();
            $table->unsignedBigInteger('products_id')->nullable();

            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->foreign('services_id')->references('services_id')->on('services')->onDelete('cascade');
            $table->foreign('products_id')->references('products_id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonys');
    }
};

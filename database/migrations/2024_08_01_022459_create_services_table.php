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
        Schema::create('services', function (Blueprint $table) {
            $table->id('services_id');
            $table->string('benefit');
            $table->integer('duration');
            $table->unsignedBigInteger('service_sections_id');
            $table->unsignedBigInteger('common_attributes_id');
            $table->foreign('service_sections_id')->references('service_sections_id')->on('service_sections')->onDelete('cascade');
            $table->foreign('common_attributes_id')->references('common_attributes_id')->on('common_attributes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

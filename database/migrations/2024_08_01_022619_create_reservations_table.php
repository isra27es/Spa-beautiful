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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservations_id');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->boolean('status');
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('unregistered_users_id')->nullable();
            $table->unsignedBigInteger('package_services_id')->nullable();
            $table->unsignedBigInteger('services_id')->nullable();

            $table->foreign('unregistered_users_id')->references('unregistered_users_id')->on('unregistered_users')->onDelete('cascade');
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->foreign('package_services_id')->references('package_services_id')->on('package_services')->onDelete('cascade');
            $table->foreign('services_id')->references('services_id')->on('services')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

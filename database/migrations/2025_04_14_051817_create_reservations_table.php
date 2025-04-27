<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('donation_id');
        $table->unsignedBigInteger('recipient_id');
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->time('pickup_time');
        $table->date('pickup_date');
        $table->timestamps();

        $table->foreign('donation_id')->references('id')->on('donations')->onDelete('cascade');
        $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
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

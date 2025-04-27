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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('food_category');
            $table->text('food_description');
            $table->integer('estimated_servings');
            $table->date('best_before');
            $table->enum('donation_type', ['direct', 'dropoff']);
            $table->text('pickup_location');
            $table->text('additional_instructions')->nullable();
            $table->string('contact_number');
            $table->enum('status', ['available', 'reserved', 'completed'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
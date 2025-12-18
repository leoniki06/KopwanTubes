<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('mood');
            $table->string('food_name');
            $table->text('description');
            $table->string('category');
            $table->text('reason');
            $table->string('image_url')->nullable();
            $table->decimal('calories', 8, 2)->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_recommendations');
    }
};
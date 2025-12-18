<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('premium_recipes', function (Blueprint $table) {
            $table->id();
            $table->string('chef_name');
            $table->string('chef_photo')->nullable();
            $table->string('recipe_name');
            $table->text('description');
            $table->string('mood_category')->nullable(); // Relasi dengan mood
            $table->json('ingredients'); // Format JSON
            $table->json('step_by_step'); // Array step-by-step
            $table->string('video_url')->nullable();
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Sulit'])->default('Sedang');
            $table->integer('cooking_time'); // Dalam menit
            $table->integer('servings')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('premium_recipes');
    }
};
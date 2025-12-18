<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_recommendations', function (Blueprint $table) {
            $table->string('restaurant_name')->nullable()->after('food_name');
            $table->string('restaurant_location')->nullable()->after('restaurant_name');
            $table->decimal('rating', 3, 1)->nullable()->after('restaurant_location');
            $table->decimal('price_range', 10, 2)->nullable()->after('rating');
            $table->string('preparation_time')->nullable()->after('price_range');
        });
    }

    public function down(): void
    {
        Schema::table('food_recommendations', function (Blueprint $table) {
            $table->dropColumn(['restaurant_name', 'restaurant_location', 'rating', 'price_range', 'preparation_time']);
        });
    }
};
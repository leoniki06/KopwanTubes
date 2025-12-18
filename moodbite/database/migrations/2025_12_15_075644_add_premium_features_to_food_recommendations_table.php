<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_recommendations', function (Blueprint $table) {
            // Kolom untuk status premium
            $table->boolean('is_premium')->default(false)->after('calories');
            $table->decimal('premium_price', 10, 2)->nullable()->after('is_premium');
            
            // Kolom untuk informasi lengkap (hanya untuk premium/opsional)
            $table->text('location_details')->nullable()->after('premium_price');
            $table->json('operational_hours')->nullable()->after('location_details');
            $table->string('contact_info')->nullable()->after('operational_hours');
            $table->string('website')->nullable()->after('contact_info');
            
            // Kolom untuk fitur premium tambahan
            $table->boolean('has_reservation')->default(false)->after('website');
            $table->boolean('has_delivery')->default(false)->after('has_reservation');
            $table->json('dietary_info')->nullable()->after('has_delivery'); // ['vegetarian', 'gluten-free', etc]
            $table->json('image_urls')->nullable()->after('dietary_info');
        });
    }

    public function down(): void
    {
        Schema::table('food_recommendations', function (Blueprint $table) {
            $table->dropColumn([
                'is_premium',
                'premium_price',
                'location_details',
                'operational_hours',
                'contact_info',
                'website',
                'has_reservation',
                'has_delivery',
                'dietary_info',
                'image_urls'
            ]);
        });
    }
};
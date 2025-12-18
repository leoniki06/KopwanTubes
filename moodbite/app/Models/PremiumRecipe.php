<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'chef_name',
        'chef_photo',
        'recipe_name',
        'description',
        'mood_category',
        'ingredients',
        'step_by_step',
        'video_url',
        'difficulty',
        'cooking_time',
        'servings',
        'is_active'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'step_by_step' => 'array',
        'is_active' => 'boolean',
        'cooking_time' => 'integer',
        'servings' => 'integer'
    ];

    // Scope untuk resep aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Filter berdasarkan mood
    public function scopeByMood($query, $mood)
    {
        return $query->where('mood_category', $mood);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FoodRecommendation;
use App\Models\PremiumRecipe;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // ========== CEK STATUS PREMIUM USER ==========
        $isUserPremium = false;
        $premiumDaysLeft = 0;
        
        if ($user->is_premium && $user->premium_until && $user->premium_until > now()) {
            $isUserPremium = true;
            $premiumDaysLeft = $user->premium_until->diffInDays(now());
        } elseif ($user->is_premium && $user->premium_until && $user->premium_until->isPast()) {
            // Premium expired, update status
            $user->update(['is_premium' => false]);
        }
        
        // ========== REKOMENDASI FREE UNTUK SEMUA USER ==========
        // Ambil makanan yang is_premium = false (untuk free user)
        $freeRecommendations = FoodRecommendation::where('is_premium', false)
            ->inRandomOrder()
            ->take(6)
            ->get();
        
        // ========== REKOMENDASI PREMIUM (jika user premium) ==========
        $premiumFoodRecommendations = collect();
        if ($isUserPremium) {
            // Ambil makanan premium dari tabel food_recommendations
            $premiumFoodRecommendations = FoodRecommendation::where('is_premium', true)
                ->inRandomOrder()
                ->take(3)
                ->get();
        }
        
        // ========== RESEP EKSKLUSIF PREMIUM (hanya untuk premium user) ==========
        $premiumExclusiveRecipes = collect();
        if ($isUserPremium) {
            // Ambil resep eksklusif dari tabel premium_recipes
            $premiumExclusiveRecipes = PremiumRecipe::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }
        
        // ========== PREVIEW UNTUK NON-PREMIUM ==========
        $exclusivePreview = null;
        if (!$isUserPremium) {
            // Preview 2 resep eksklusif untuk promosi
            $exclusivePreview = PremiumRecipe::where('is_active', true)
                ->inRandomOrder()
                ->take(2)
                ->get(['id', 'chef_name', 'recipe_name', 'mood_category', 'difficulty']);
        }
        
        // ========== STATISTIK ==========
        $stats = [
            'total_free_food' => FoodRecommendation::where('is_premium', false)->count(),
            'total_premium_food' => FoodRecommendation::where('is_premium', true)->count(),
            'total_exclusive_recipes' => PremiumRecipe::where('is_active', true)->count(),
            'is_user_premium' => $isUserPremium,
            'premium_days_left' => $premiumDaysLeft,
            'user_plan' => $user->premium_plan ?? 'Free'
        ];
        
        // ========== WELCOME MESSAGE ==========
        $hour = now()->hour;
        if ($hour < 12) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour < 19) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }
        
        return view('dashboard', compact(
            'user',
            'isUserPremium',
            'freeRecommendations',
            'premiumFoodRecommendations',
            'premiumExclusiveRecipes',
            'exclusivePreview',
            'stats',
            'greeting'
        ));
    }
    
    /**
     * Quick mood selection
     */
    public function quickMood(Request $request)
    {
        $request->validate([
            'mood' => 'required|in:happy,sad,energetic,relaxed,stressed'
        ]);
        
        $user = Auth::user();
        $mood = $request->mood;
        
        // Simpan preferensi mood
        $preferences = $user->food_preferences ?? [];
        $preferences['current_mood'] = $mood;
        $preferences['last_mood_update'] = now()->toDateTimeString();
        
        $user->update(['food_preferences' => $preferences]);
        
        // Cari rekomendasi berdasarkan mood
        $recommendations = FoodRecommendation::where('mood', $mood)
            ->where('is_premium', false)
            ->take(4)
            ->get();
        
        // Jika user premium, tambahkan resep premium
        $premiumRecipes = [];
        if ($user->is_premium && $user->premium_until > now()) {
            $premiumRecipes = PremiumRecipe::where('mood_category', $mood)
                ->where('is_active', true)
                ->take(2)
                ->get();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Mood berhasil disimpan!',
            'mood' => $mood,
            'recommendations' => $recommendations,
            'premium_recipes' => $premiumRecipes,
            'is_premium' => $user->is_premium && $user->premium_until > now()
        ]);
    }
}
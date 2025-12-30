<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodRecommendationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PremiumRecipeController;
use App\Models\Membership;

// ====================
// SPLASH SCREEN
// ====================
Route::get('/', function () {
    return view('splash');
})->name('splash');

// ====================
// AUTH (GUEST ONLY)
// ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ====================
// AUTHENTICATED USER
// ====================
Route::middleware('auth')->group(function () {

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // FOOD RECOMMENDATION
    Route::get('/recommendations', [FoodRecommendationController::class, 'index'])
        ->name('recommendations');
    Route::post('/recommendations', [FoodRecommendationController::class, 'getRecommendations']);

    // ====================
    // PROFILE
    // ====================
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
        Route::get('/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
        Route::get('/recipe-history', [ProfileController::class, 'recipeHistory'])->name('profile.recipe-history');
    });

    // ====================
    // MEMBERSHIP / PREMIUM
    // ====================
    Route::prefix('membership')->group(function () {
        Route::get('/', [MembershipController::class, 'index'])->name('membership.index');
        Route::post('/purchase', [MembershipController::class, 'purchase'])->name('membership.purchase');
        Route::get('/success', [MembershipController::class, 'success'])->name('membership.success');
        Route::get('/history', [MembershipController::class, 'history'])->name('membership.history');
        Route::post('/callback', [MembershipController::class, 'callback'])->name('membership.callback');
    });

    // ====================
    // PREMIUM RECIPES - RESEP EKSKLUSIF
    // ====================
    Route::prefix('premium')->group(function () {
        // Halaman utama resep eksklusif (perlu premium)
        Route::get('/recipes', [PremiumRecipeController::class, 'index'])
            ->name('premium.recipes.index');
        
        // Detail resep eksklusif (perlu premium)
        Route::get('/recipes/{id}', [PremiumRecipeController::class, 'show'])
            ->name('premium.recipes.show');
        
        // Preview resep untuk non-premium (info terbatas)
        Route::get('/recipes/{id}/preview', [PremiumRecipeController::class, 'preview'])
            ->name('premium.recipes.preview');
        
        // Cari resep berdasarkan mood
        Route::get('/recipes/mood/{mood}', [PremiumRecipeController::class, 'byMood'])
            ->name('premium.recipes.mood');
        
        // Filter resep berdasarkan kesulitan
        Route::get('/recipes/difficulty/{difficulty}', [PremiumRecipeController::class, 'byDifficulty'])
            ->name('premium.recipes.difficulty');
        
        // Favorit Resep
        Route::post('/recipes/{id}/favorite', [PremiumRecipeController::class, 'toggleFavorite'])
            ->name('premium.recipes.favorite');
        
        // Dashboard premium user
        Route::get('/dashboard', [PremiumRecipeController::class, 'dashboard'])
            ->name('premium.dashboard');
    });
});

// ====================
// PUBLIC ROUTES (PREMIUM PROMO)
// ====================
Route::prefix('premium')->group(function () {
    // Halaman info premium (public)
    Route::get('/info', function () {
        return view('premium.info');
    })->name('premium.info');
    
    // List chef ternama (public)
    Route::get('/chefs', function () {
        return view('premium.chefs');
    })->name('premium.chefs');
});

// ====================
// API ROUTES
// ====================
Route::middleware('auth')->group(function () {
    Route::get('/api/check-premium-access', function () {
        $user = Auth::user();
        return response()->json([
            'is_premium' => $user->is_premium,
            'premium_until' => $user->premium_until,
            'can_access' => $user->canAccessPremiumFeatures()
        ]);
    })->name('api.check-premium-access');
    
    // API Premium Recipes
    Route::prefix('api/premium')->group(function () {
        Route::get('/recipes', [PremiumRecipeController::class, 'apiIndex'])
            ->name('api.premium.recipes');
        Route::get('/favorites', [PremiumRecipeController::class, 'apiFavorites'])
            ->name('api.premium.favorites');
    });
});

// ====================
// FALLBACK
// ====================
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('splash');
});
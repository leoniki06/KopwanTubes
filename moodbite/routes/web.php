<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodRecommendationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PremiumRecipeController; // TAMBAHKAN INI
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
        
        // TAMBAH ROUTE UNTUK FAVORIT RESEP
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
            ->name('premium.recipes.index')
            ->middleware('premium'); // Middleware khusus premium
        
        // Detail resep eksklusif (perlu premium)
        Route::get('/recipes/{id}', [PremiumRecipeController::class, 'show'])
            ->name('premium.recipes.show')
            ->middleware('premium');
        
        // Preview resep untuk non-premium (info terbatas)
        Route::get('/recipes/{id}/preview', [PremiumRecipeController::class, 'preview'])
            ->name('premium.recipes.preview');
        
        // Cari resep berdasarkan mood
        Route::get('/recipes/mood/{mood}', [PremiumRecipeController::class, 'byMood'])
            ->name('premium.recipes.mood')
            ->middleware('premium');
        
        // Filter resep berdasarkan kesulitan
        Route::get('/recipes/difficulty/{difficulty}', [PremiumRecipeController::class, 'byDifficulty'])
            ->name('premium.recipes.difficulty')
            ->middleware('premium');
        
        // Favorit Resep
        Route::post('/recipes/{id}/favorite', [PremiumRecipeController::class, 'toggleFavorite'])
            ->name('premium.recipes.favorite')
            ->middleware('premium');
        
        // Simpan resep yang dilihat ke history
        Route::post('/recipes/{id}/view', [PremiumRecipeController::class, 'recordView'])
            ->name('premium.recipes.view');
        
        // Rekomendasi resep personal
        Route::get('/recipes/recommended', [PremiumRecipeController::class, 'recommended'])
            ->name('premium.recipes.recommended')
            ->middleware('premium');
        
        // Dashboard premium user
        Route::get('/dashboard', [PremiumRecipeController::class, 'dashboard'])
            ->name('premium.dashboard')
            ->middleware('premium');
    });

    // ====================
    // API RESEP EKSKLUSIF
    // ====================
    Route::prefix('api/premium')->group(function () {
        // Get resep eksklusif (JSON API)
        Route::get('/recipes', [PremiumRecipeController::class, 'apiIndex'])
            ->name('api.premium.recipes')
            ->middleware('premium');
        
        // Cek status premium user
        Route::get('/status', function () {
            $user = Auth::user();
            return response()->json([
                'is_premium' => $user->is_premium,
                'premium_until' => $user->premium_until,
                'premium_plan' => $user->premium_plan,
                'can_access' => $user->canAccessPremiumFeatures()
            ]);
        })->name('api.premium.status');
        
        // Get resep favorit
        Route::get('/favorites', [PremiumRecipeController::class, 'apiFavorites'])
            ->name('api.premium.favorites')
            ->middleware('premium');
        
        // Simulasi akses resep (untuk testing)
        Route::get('/test-access/{recipeId}', [PremiumRecipeController::class, 'testAccess'])
            ->name('api.premium.test-access');
    });
});


// ====================
// PUBLIC ROUTES (PREMIUM PROMO)
// ====================
Route::prefix('premium')->group(function () {
    // Halaman info premium (public)
    Route::get('/info', function () {
        return view('premium.info', [
            'benefits' => [
                [
                    'title' => 'Resep Eksklusif',
                    'description' => 'Akses ke resep-resep khusus dari chef ternama untuk dibuat di rumah.',
                    'icon' => '👨‍🍳'
                ],
                [
                    'title' => 'Video Tutorial',
                    'description' => 'Step-by-step video tutorial dari chef profesional.',
                    'icon' => '🎥'
                ],
                [
                    'title' => 'Bahan Premium',
                    'description' => 'Rekomendasi bahan berkualitas tinggi untuk hasil terbaik.',
                    'icon' => '⭐'
                ],
                [
                    'title' => 'Konsultasi Chef',
                    'description' => 'Tips dan trik langsung dari chef berpengalaman.',
                    'icon' => '💬'
                ]
            ]
        ]);
    })->name('premium.info');
    
    // List chef ternama (public)
    Route::get('/chefs', function () {
        return view('premium.chefs', [
            'chefs' => [
                ['name' => 'Chef Juna', 'specialty' => 'Fine Dining', 'experience' => '20 tahun'],
                ['name' => 'Chef Renatta', 'specialty' => 'Modern Indonesian', 'experience' => '15 tahun'],
                ['name' => 'Chef Arnold', 'specialty' => 'Pastry & Dessert', 'experience' => '12 tahun'],
                ['name' => 'Chef Farah', 'specialty' => 'Healthy Cooking', 'experience' => '10 tahun']
            ]
        ]);
    })->name('premium.chefs');
});


// ====================
// API
// ====================

// CEK AUTH
Route::get('/api/check-auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'is_premium' => Auth::check() ? Auth::user()->is_premium : false
    ]);
});

// API DETAIL MEMBERSHIP (AUTH)
Route::get('/api/membership/{id}', function ($id) {
    $membership = Membership::with('user')->findOrFail($id);

    // Authorization check
    if (Auth::id() !== $membership->user_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    return response()->json([
        'order_id' => $membership->order_id,
        'type' => $membership->type,
        'type_label' => $membership->type_label,
        'price' => $membership->price,
        'status' => $membership->status,
        'payment_status' => $membership->payment_status,
        'start_date' => $membership->start_date,
        'start_date_formatted' => optional($membership->start_date)->format('d F Y'),
        'end_date' => $membership->end_date,
        'end_date_formatted' => optional($membership->end_date)->format('d F Y'),
        'features' => $membership->features_list,
        'includes_premium_recipes' => true // TAMBAH INI
    ]);
})->middleware('auth');

// API CEK PREMIUM ACCESS (UNTUK MIDDLEWARE)
Route::get('/api/check-premium-access', function () {
    if (!Auth::check()) {
        return response()->json([
            'access' => false,
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    $user = Auth::user();
    $access = $user->canAccessPremiumFeatures();
    
    return response()->json($access);
})->name('api.check-premium-access');


// ====================
// FALLBACK ROUTES
// ====================
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('splash');
});
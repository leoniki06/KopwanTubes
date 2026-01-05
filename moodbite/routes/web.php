<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodRecommendationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PremiumRecipeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPremiumRecipeController;

Route::get('/', function () {
    return view('splash');
})->name('splash');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return app(DashboardController::class)->index();
    })->name('dashboard');

    Route::get('/recommendations', [FoodRecommendationController::class, 'index'])->name('recommendations');
    Route::post('/recommendations', [FoodRecommendationController::class, 'getRecommendations']);

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
        Route::get('/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
        Route::get('/recipe-history', [ProfileController::class, 'recipeHistory'])->name('profile.recipe-history');
    });

    Route::prefix('membership')->group(function () {
        Route::get('/', [MembershipController::class, 'index'])->name('membership.index');
        Route::post('/purchase', [MembershipController::class, 'purchase'])->name('membership.purchase');

        Route::get('/payment/{order_id}', [MembershipController::class, 'payment'])->name('membership.payment');
        Route::post('/payment/{order_id}/pay', [MembershipController::class, 'payMock'])->name('membership.payMock');

        Route::get('/success', [MembershipController::class, 'success'])->name('membership.success');
        Route::get('/history', [MembershipController::class, 'history'])->name('membership.history');
        Route::post('/callback', [MembershipController::class, 'callback'])->name('membership.callback');
        Route::post('/finish', [MembershipController::class, 'finish'])->name('membership.finish');
    });

    Route::prefix('premium')->group(function () {
        Route::get('/dashboard', [PremiumRecipeController::class, 'dashboard'])->name('premium.dashboard');

        Route::get('/recipes', [PremiumRecipeController::class, 'index'])->name('premium.recipes.index');
        Route::get('/recipes/{id}', [PremiumRecipeController::class, 'show'])->name('premium.recipes.show');

        Route::get('/recipes/{id}/preview', [PremiumRecipeController::class, 'preview'])->name('premium.recipes.preview');
        Route::get('/recipes/mood/{mood}', [PremiumRecipeController::class, 'byMood'])->name('premium.recipes.mood');
        Route::get('/recipes/difficulty/{difficulty}', [PremiumRecipeController::class, 'byDifficulty'])->name('premium.recipes.difficulty');

        Route::post('/recipes/{id}/favorite', [PremiumRecipeController::class, 'toggleFavorite'])->name('premium.recipes.favorite');
    });

    Route::prefix('api')->group(function () {
        Route::get('/check-premium-access', function () {
            $user = Auth::user();

            return response()->json([
                'is_premium' => $user->is_premium,
                'premium_until' => $user->premium_until,
                'can_access' => $user->canAccessPremiumFeatures()
            ]);
        })->name('api.check-premium-access');

        Route::prefix('premium')->group(function () {
            Route::get('/recipes', [PremiumRecipeController::class, 'apiIndex'])->name('api.premium.recipes');
            Route::get('/favorites', [PremiumRecipeController::class, 'apiFavorites'])->name('api.premium.favorites');
        });
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/memberships', [AdminController::class, 'memberships'])->name('admin.memberships');

    Route::prefix('premium-recipes')->group(function () {
        Route::get('/', [AdminPremiumRecipeController::class, 'index'])->name('admin.premium-recipes.index');
        Route::get('/create', [AdminPremiumRecipeController::class, 'create'])->name('admin.premium-recipes.create');
        Route::post('/', [AdminPremiumRecipeController::class, 'store'])->name('admin.premium-recipes.store');
        Route::get('/{id}/edit', [AdminPremiumRecipeController::class, 'edit'])->name('admin.premium-recipes.edit');
        Route::put('/{id}', [AdminPremiumRecipeController::class, 'update'])->name('admin.premium-recipes.update');
        Route::delete('/{id}', [AdminPremiumRecipeController::class, 'destroy'])->name('admin.premium-recipes.destroy');
        Route::patch('/{id}/toggle', [AdminPremiumRecipeController::class, 'toggle'])->name('admin.premium-recipes.toggle');
    });
});

Route::prefix('premium')->group(function () {
    Route::get('/info', function () {
        return view('premium.info');
    })->name('premium.info');

    Route::get('/chefs', function () {
        return view('premium.chefs');
    })->name('premium.chefs');
});

Route::fallback(function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('splash');
});

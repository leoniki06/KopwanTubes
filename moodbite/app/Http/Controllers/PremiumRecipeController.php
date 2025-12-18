<?php

namespace App\Http\Controllers;

use App\Models\PremiumRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PremiumRecipeController extends Controller
{
    // Middleware untuk cek premium
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('premium')->except(['index', 'showPreview']);
    }

    // Tampilkan semua resep eksklusif (hanya untuk premium)
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Jika bukan premium, redirect ke halaman membership
        if (!$user->isPremiumActive()) {
            return redirect()->route('membership')
                ->with('error', 'Anda perlu upgrade ke premium untuk mengakses resep eksklusif');
        }

        $recipes = PremiumRecipe::active()
            ->when($request->mood, function ($query, $mood) {
                return $query->byMood($mood);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('premium.recipes', compact('recipes'));
    }

    // Detail resep
    public function show($id)
    {
        $recipe = PremiumRecipe::active()->findOrFail($id);
        return view('premium.recipe-detail', compact('recipe'));
    }

    // Preview terbatas untuk non-premium
    public function showPreview($id)
    {
        $recipe = PremiumRecipe::active()->findOrFail($id);
        
        // Hanya tampilkan sebagian informasi
        $previewData = [
            'chef_name' => $recipe->chef_name,
            'recipe_name' => $recipe->recipe_name,
            'description' => substr($recipe->description, 0, 150) . '...',
            'difficulty' => $recipe->difficulty,
            'cooking_time' => $recipe->cooking_time,
            'is_preview' => true
        ];

        return view('premium.recipe-preview', compact('previewData'));
    }

    // Tambah ke favorit
    public function addToFavorites($id)
    {
        $user = Auth::user();
        $user->addFavoriteRecipe($id);
        
        return back()->with('success', 'Resep ditambahkan ke favorit!');
    }
}
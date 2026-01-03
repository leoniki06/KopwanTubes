<?php

namespace App\Http\Controllers;

use App\Models\PremiumRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PremiumRecipeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isPremiumActive()) {
            return redirect()->route('membership.index')
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

    public function show($id)
    {
        $recipe = PremiumRecipe::active()->findOrFail($id);
        return view('premium.recipe-detail', compact('recipe'));
    }

    public function preview($id)
    {
        $recipe = PremiumRecipe::active()->findOrFail($id);

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

    public function toggleFavorite($id)
    {
        $user = Auth::user();

        if (!$user || !$user->isPremiumActive()) {
            return back()->with('error', 'Harus premium untuk menambahkan favorit');
        }

        $user->addFavoriteRecipe($id);

        return back()->with('success', 'Resep ditambahkan ke favorit!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PremiumRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $user = Auth::user();
        if ($user) {
            $user->addToRecipeHistory($recipe->id);
        }

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

        if ($user->hasFavoritedRecipe($id)) {
            $user->removeFavoriteRecipe($id);
            return back()->with('success', 'Resep dihapus dari favorit!');
        }

        $user->addFavoriteRecipe($id);
        return back()->with('success', 'Resep ditambahkan ke favorit!');
    }

    public function adminIndex(Request $request)
    {
        $recipes = PremiumRecipe::query()
            ->when($request->mood, function ($query, $mood) {
                return $query->where('mood_category', $mood);
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'active') {
                    return $query->where('is_active', true);
                }
                if ($status === 'inactive') {
                    return $query->where('is_active', false);
                }
                return $query;
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $moods = PremiumRecipe::select('mood_category')->distinct()->pluck('mood_category');

        return view('admin.premium-recipes.index', compact('recipes', 'moods'));
    }

    public function create()
    {
        return view('admin.premium-recipes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'chef_name' => 'required|string|max:255',
            'chef_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'recipe_name' => 'required|string|max:255',
            'description' => 'required|string',
            'mood_category' => 'required|string|max:100',
            'ingredients' => 'required|array',
            'step_by_step' => 'required|array',
            'video_url' => 'nullable|string|max:255',
            'difficulty' => 'required|string|max:50',
            'cooking_time' => 'required|integer|min:1',
            'servings' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('chef_photo')) {
            $path = $request->file('chef_photo')->store('chef_photos', 'public');
            $data['chef_photo'] = $path;
        }

        PremiumRecipe::create($data);

        return redirect()->route('admin.premium.recipes.index')
            ->with('success', 'Resep berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $recipe = PremiumRecipe::findOrFail($id);
        return view('admin.premium-recipes.edit', compact('recipe'));
    }

    public function update(Request $request, $id)
    {
        $recipe = PremiumRecipe::findOrFail($id);

        $request->validate([
            'chef_name' => 'required|string|max:255',
            'chef_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'recipe_name' => 'required|string|max:255',
            'description' => 'required|string',
            'mood_category' => 'required|string|max:100',
            'ingredients' => 'required|array',
            'step_by_step' => 'required|array',
            'video_url' => 'nullable|string|max:255',
            'difficulty' => 'required|string|max:50',
            'cooking_time' => 'required|integer|min:1',
            'servings' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('chef_photo')) {
            if ($recipe->chef_photo && Storage::disk('public')->exists($recipe->chef_photo)) {
                Storage::disk('public')->delete($recipe->chef_photo);
            }

            $path = $request->file('chef_photo')->store('chef_photos', 'public');
            $data['chef_photo'] = $path;
        }

        $recipe->update($data);

        return redirect()->route('admin.premium.recipes.index')
            ->with('success', 'Resep berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $recipe = PremiumRecipe::findOrFail($id);

        if ($recipe->chef_photo && Storage::disk('public')->exists($recipe->chef_photo)) {
            Storage::disk('public')->delete($recipe->chef_photo);
        }

        $recipe->delete();

        return back()->with('success', 'Resep berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $recipe = PremiumRecipe::findOrFail($id);
        $recipe->is_active = !$recipe->is_active;
        $recipe->save();

        return back()->with('success', 'Status resep berhasil diupdate!');
    }
}
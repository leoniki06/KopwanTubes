<?php

namespace App\Http\Controllers;

use App\Models\PremiumRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPremiumRecipeController extends Controller
{
    // =========================
    // INDEX (LIST + FILTER)
    // =========================
    public function index(Request $request)
    {
        $filters = [
            'mood'   => $request->get('mood'),
            'status' => $request->get('status'),
        ];

        // list mood unik dari DB
        $moods = PremiumRecipe::select('mood_category')
            ->whereNotNull('mood_category')
            ->distinct()
            ->pluck('mood_category')
            ->toArray();

        // query resep
        $recipes = PremiumRecipe::query()
            ->when($filters['mood'], function ($q) use ($filters) {
                $q->where('mood_category', $filters['mood']);
            })
            ->when($filters['status'], function ($q) use ($filters) {
                if ($filters['status'] === 'active') {
                    $q->where('is_active', true);
                }
                if ($filters['status'] === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        return view('admin.premium-recipes.index', compact(
            'recipes',
            'moods',
            'filters'
        ));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('admin.premium-recipes.create');
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'chef_name'     => 'required|string|max:255',
            'chef_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'recipe_name'   => 'required|string|max:255',
            'description'   => 'required|string',
            'mood_category' => 'required|string|max:100',
            'ingredients'   => 'required',
            'step_by_step'  => 'required',
            'video_url'     => 'nullable|url',
            'difficulty'    => 'required|string|max:50',
            'cooking_time'  => 'required|integer|min:1',
            'servings'      => 'required|integer|min:1',
            'is_active'     => 'nullable|boolean',
        ]);

        // upload foto chef kalau ada
        $photoPath = null;
        if ($request->hasFile('chef_photo')) {
            $photoPath = $request->file('chef_photo')->store('chef_photos', 'public');
        }

        // ingredients & step_by_step boleh string (textarea) -> convert jadi array
        $ingredients = $this->toArrayInput($request->ingredients);
        $steps = $this->toArrayInput($request->step_by_step);

        PremiumRecipe::create([
            'chef_name'     => $request->chef_name,
            'chef_photo'    => $photoPath,
            'recipe_name'   => $request->recipe_name,
            'description'   => $request->description,
            'mood_category' => $request->mood_category,
            'ingredients'   => $ingredients,
            'step_by_step'  => $steps,
            'video_url'     => $request->video_url,
            'difficulty'    => $request->difficulty,
            'cooking_time'  => $request->cooking_time,
            'servings'      => $request->servings,
            'is_active'     => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.premium-recipes.index')
            ->with('success', 'Resep premium berhasil ditambahkan!');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $recipe = PremiumRecipe::findOrFail($id);
        return view('admin.premium-recipes.edit', compact('recipe'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $recipe = PremiumRecipe::findOrFail($id);

        $request->validate([
            'chef_name'     => 'required|string|max:255',
            'chef_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'recipe_name'   => 'required|string|max:255',
            'description'   => 'required|string',
            'mood_category' => 'required|string|max:100',
            'ingredients'   => 'required',
            'step_by_step'  => 'required',
            'video_url'     => 'nullable|url',
            'difficulty'    => 'required|string|max:50',
            'cooking_time'  => 'required|integer|min:1',
            'servings'      => 'required|integer|min:1',
        ]);

        // update foto chef kalau ada
        if ($request->hasFile('chef_photo')) {
            if ($recipe->chef_photo && Storage::disk('public')->exists($recipe->chef_photo)) {
                Storage::disk('public')->delete($recipe->chef_photo);
            }
            $recipe->chef_photo = $request->file('chef_photo')->store('chef_photos', 'public');
        }

        $recipe->update([
            'chef_name'     => $request->chef_name,
            'recipe_name'   => $request->recipe_name,
            'description'   => $request->description,
            'mood_category' => $request->mood_category,
            'ingredients'   => $this->toArrayInput($request->ingredients),
            'step_by_step'  => $this->toArrayInput($request->step_by_step),
            'video_url'     => $request->video_url,
            'difficulty'    => $request->difficulty,
            'cooking_time'  => $request->cooking_time,
            'servings'      => $request->servings,
        ]);

        return redirect()->route('admin.premium-recipes.index')
            ->with('success', 'Resep premium berhasil diperbarui!');
    }

    // =========================
    // DESTROY
    // =========================
    public function destroy($id)
    {
        $recipe = PremiumRecipe::findOrFail($id);

        if ($recipe->chef_photo && Storage::disk('public')->exists($recipe->chef_photo)) {
            Storage::disk('public')->delete($recipe->chef_photo);
        }

        $recipe->delete();

        return redirect()->route('admin.premium-recipes.index')
            ->with('success', 'Resep premium berhasil dihapus!');
    }

    // =========================
    // TOGGLE ACTIVE / INACTIVE
    // =========================
    public function toggle($id)
    {
        $recipe = PremiumRecipe::findOrFail($id);
        $recipe->is_active = !$recipe->is_active;
        $recipe->save();

        return redirect()->route('admin.premium-recipes.index')
            ->with('success', 'Status resep berhasil diubah!');
    }

    // =========================
    // HELPER: Convert input to array
    // =========================
    private function toArrayInput($input)
    {
        // kalau array langsung return
        if (is_array($input)) return $input;

        // kalau string textarea (pisah per baris)
        $lines = preg_split("/\r\n|\n|\r/", trim($input));
        $lines = array_filter($lines, fn($v) => trim($v) !== '');
        return array_values($lines);
    }
}

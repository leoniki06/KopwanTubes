<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\RecommendationHistory;
use App\Models\PremiumRecipe;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $recommendationHistory = RecommendationHistory::where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        return view('profile.show', compact('user', 'recommendationHistory'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->birthdate = $request->birthdate;
        $user->gender = $request->gender;
        $user->address = $request->address;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            $avatarName = 'avatar_' . $user->id . '_' . time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('public/avatars', $avatarName);
            $user->avatar = $avatarName;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $preferences = $request->input('preferences', []);
        $user->food_preferences = $preferences;
        $user->save();

        return back()->with('success', 'Preferensi makanan berhasil diperbarui!');
    }

    public function favorites()
    {
        $user = Auth::user();

        $favoriteIds = $user->favorite_recipes ?? [];

        $favorites = collect();
        if (!empty($favoriteIds)) {
            $favorites = PremiumRecipe::whereIn('id', $favoriteIds)
                ->where('is_active', true)
                ->get();
        }

        return view('profile.favorites', compact('user', 'favorites'));
    }

    public function recipeHistory()
    {
        $user = Auth::user();

        $history = $user->recipe_view_history ?? [];

        $recipeIds = collect($history)->pluck('recipe_id')->unique()->filter()->values();

        $recipes = collect();
        if ($recipeIds->count() > 0) {
            $recipes = PremiumRecipe::whereIn('id', $recipeIds)
                ->where('is_active', true)
                ->get();
        }

        return view('profile.recipe-history', compact('user', 'history', 'recipes'));
    }
}

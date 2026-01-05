<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodRecommendation;
use App\Models\RecommendationHistory;

class FoodRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isPremium = $user ? $user->isPremium() : false;

        $basicMoods = [
            ['key' => 'happy', 'label' => 'Bahagia', 'icon' => 'fa-smile', 'color' => '#FFD166'],
            ['key' => 'sad', 'label' => 'Sedih', 'icon' => 'fa-sad-tear', 'color' => '#87CEEB'],
            ['key' => 'energetic', 'label' => 'Berenergi', 'icon' => 'fa-bolt', 'color' => '#98FF98'],
            ['key' => 'stress', 'label' => 'Stress', 'icon' => 'fa-wind', 'color' => '#C8A2C8'],
            ['key' => 'romantic', 'label' => 'Romantis', 'icon' => 'fa-heart', 'color' => '#FF9AA2'],
            ['key' => 'hungry', 'label' => 'Lapar', 'icon' => 'fa-hamburger', 'color' => '#FFA94D'],
        ];

        $premiumMoods = [
            ['key' => 'anxious', 'label' => 'Anxious', 'icon' => 'fa-face-frown', 'color' => '#A0C4FF'],
            ['key' => 'lazy', 'label' => 'Lazy', 'icon' => 'fa-bed', 'color' => '#BDB2FF'],
            ['key' => 'rainy', 'label' => 'Rainy', 'icon' => 'fa-cloud-rain', 'color' => '#90E0EF'],
            ['key' => 'focus', 'label' => 'Focus', 'icon' => 'fa-bullseye', 'color' => '#B9FBC0'],
            ['key' => 'sleepy', 'label' => 'Sleepy', 'icon' => 'fa-moon', 'color' => '#FFC6FF'],
            ['key' => 'party', 'label' => 'Party', 'icon' => 'fa-champagne-glasses', 'color' => '#FFADAD'],
        ];

        $moods = $basicMoods;
        if ($isPremium) {
            $moods = array_merge($basicMoods, $premiumMoods);
        }

        $mood = $request->query('mood', null);
        $recommendations = collect();

        if ($mood) {
            $allowedMoods = collect($moods)->pluck('key')->toArray();

            if (!in_array($mood, $allowedMoods)) {
                return redirect()->route('recommendations')->with('error', 'Mood tidak tersedia.');
            }

            $query = FoodRecommendation::where('mood', $mood);

            if (!$isPremium) {
                $query->where('is_premium', false);
            }

            $recommendations = $query->orderBy('is_premium', 'desc')
                ->orderBy('rating', 'desc')
                ->get();

            if ($user) {
                $resultsToSave = $recommendations->map(function ($item) use ($isPremium) {
                    return $isPremium ? $item->full_info : $item->basic_info;
                })->values()->toArray();

                RecommendationHistory::create([
                    'user_id' => $user->id,
                    'mood' => $mood,
                    'results' => $resultsToSave,
                ]);
            }

            return view('recommendations.results', compact('mood', 'recommendations', 'isPremium'));
        }

        return view('recommendations.index', compact('moods', 'isPremium'));
    }

    public function getRecommendations(Request $request)
    {
        $request->validate([
            'mood' => 'required|string'
        ]);

        $user = auth()->user();
        $isPremium = $user ? $user->isPremium() : false;

        $basicMoods = ['happy', 'sad', 'energetic', 'stress', 'romantic', 'hungry'];
        $premiumMoods = ['anxious', 'lazy', 'rainy', 'focus', 'sleepy', 'party'];

        $allowedMoods = $basicMoods;
        if ($isPremium) {
            $allowedMoods = array_merge($basicMoods, $premiumMoods);
        }

        if (!in_array($request->mood, $allowedMoods)) {
            return redirect()->route('recommendations')->with('error', 'Mood tidak tersedia.');
        }

        $query = FoodRecommendation::where('mood', $request->mood);

        if (!$isPremium) {
            $query->where('is_premium', false);
        }

        $recommendations = $query->orderBy('is_premium', 'desc')
            ->orderBy('rating', 'desc')
            ->get();

        if ($user) {
            $resultsToSave = $recommendations->map(function ($item) use ($isPremium) {
                return $isPremium ? $item->full_info : $item->basic_info;
            })->values()->toArray();

            RecommendationHistory::create([
                'user_id' => $user->id,
                'mood' => $request->mood,
                'results' => $resultsToSave,
            ]);
        }

        return view('recommendations.results', [
            'mood' => $request->mood,
            'recommendations' => $recommendations,
            'isPremium' => $isPremium
        ]);
    }
}

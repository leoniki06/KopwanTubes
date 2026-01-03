<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodRecommendation;
use App\Models\User;
use App\Models\RecommendationHistory;

class FoodRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $moods = FoodRecommendation::select('mood')->distinct()->get();

        $mood = $request->query('mood', null);
        $recommendations = collect();

        if ($mood) {
            $user = auth()->user();
            $isPremium = $user ? $user->isPremium() : false;

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

        return view('recommendations.index', compact('moods'));
    }

    public function getRecommendations(Request $request)
    {
        $request->validate([
            'mood' => 'required|string'
        ]);

        $user = auth()->user();
        $isPremium = $user ? $user->isPremium() : false;

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

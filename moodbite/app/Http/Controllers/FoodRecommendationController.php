<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodRecommendation;
use App\Models\User;

class FoodRecommendationController extends Controller
{
    /**
     * Tampilkan halaman pilih mood atau hasil rekomendasi jika mood ada di query
     */
    public function index(Request $request)
    {
        // Ambil semua mood yang ada di database
        $moods = FoodRecommendation::select('mood')->distinct()->get();

        // Ambil mood dari query string ?mood=happy
        $mood = $request->query('mood', null);
        $recommendations = collect(); // default empty collection

        if ($mood) {
            // ==================== LOGIKA BARU ====================
            // 1. Cek apakah user login
            $user = auth()->user();
            
            // 2. Tentukan status premium user
            //    - Guest (tidak login) = free
            //    - User login tapi bukan premium = free
            //    - User login dan premium = premium
            $isPremium = $user ? $user->isPremium() : false;
            
            // 3. Query database dengan filter sesuai status user
            $query = FoodRecommendation::where('mood', $mood);
            
            if (!$isPremium) {
                // User biasa/guest: HANYA bisa lihat makanan non-premium
                $query->where('is_premium', false);
            }
            
            // Urutkan: makanan premium dulu (jika user premium), lalu rating tertinggi
            $recommendations = $query->orderBy('is_premium', 'desc')
                                    ->orderBy('rating', 'desc')
                                    ->get();
            // ==================== END LOGIKA BARU ====================

            // Kirim ke Blade hasil rekomendasi
            return view('recommendations.results', compact('mood', 'recommendations', 'isPremium'));
        }

        // Jika mood tidak ada, tampilkan halaman pilih mood
        return view('recommendations.index', compact('moods'));
    }

    /**
     * (Optional) Proses POST form jika masih ingin pakai submit form
     */
    public function getRecommendations(Request $request)
    {
        $request->validate([
            'mood' => 'required|string'
        ]);

        // ==================== LOGIKA BARU ====================
        $user = auth()->user();
        $isPremium = $user ? $user->isPremium() : false;
        
        $query = FoodRecommendation::where('mood', $request->mood);
        
        if (!$isPremium) {
            $query->where('is_premium', false);
        }
        
        $recommendations = $query->orderBy('is_premium', 'desc')
                                ->orderBy('rating', 'desc')
                                ->get();
        // ==================== END LOGIKA BARU ====================

        return view('recommendations.results', [
            'mood' => $request->mood,
            'recommendations' => $recommendations,
            'isPremium' => $isPremium
        ]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Membership;
use App\Models\PremiumRecipe; // Tambahkan ini

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'birthdate',
        'gender',
        'address',
        'food_preferences',
        'is_premium',
        'premium_until',
        'premium_plan',
        'payment_status',
        'payment_id',
        'favorite_recipes', // TAMBAH INI
        'recipe_view_history' // TAMBAH INI (opsional)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'food_preferences' => 'array',
        'birthdate' => 'date',
        'is_premium' => 'boolean',
        'premium_until' => 'datetime',
        'favorite_recipes' => 'array', // TAMBAH INI
        'recipe_view_history' => 'array' // TAMBAH INI (opsional)
    ];

    // Menghitung usia user
    public function getAgeAttribute()
    {
        if (!$this->birthdate) return null;

        return now()->diffInYears($this->birthdate);
    }

    // Format nomor telepon
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;

        $phone = preg_replace('/\D/', '', $this->phone);
        if (strlen($phone) === 12) {
            return '+62 ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 4) . ' ' . substr($phone, 9);
        }

        return $this->phone;
    }

    // Cek apakah user masih premium (method yang sudah ada)
    public function getIsActivePremiumAttribute()
    {
        if (!$this->is_premium) return false;

        if ($this->premium_until && $this->premium_until->isPast()) {
            $this->update(['is_premium' => false]);
            return false;
        }

        return true;
    }

    // Cek apakah user premium
    public function isPremium()
    {
        return $this->is_premium && $this->premium_until > now();
    }

    // =================== FITUR RESEP EKSKLUSIF ===================

    // Method untuk cek akses premium dengan detail
    public function canAccessPremiumFeatures()
    {
        if (!$this->is_premium) {
            return [
                'access' => false,
                'message' => 'Anda bukan member premium',
                'upgrade_url' => route('membership'),
                'expired' => false
            ];
        }

        if ($this->premium_until && $this->premium_until->isPast()) {
            $this->update(['is_premium' => false]);
            return [
                'access' => false,
                'message' => 'Membership premium Anda telah berakhir',
                'upgrade_url' => route('membership'),
                'expired' => true
            ];
        }

        return [
            'access' => true,
            'message' => 'Akses premium aktif',
            'days_remaining' => now()->diffInDays($this->premium_until, false),
            'plan' => $this->premium_plan
        ];
    }

    // Method khusus untuk cek akses resep eksklusif
    public function canAccessExclusiveRecipes()
    {
        $premiumCheck = $this->canAccessPremiumFeatures();
        
        if (!$premiumCheck['access']) {
            throw new \Exception($premiumCheck['message']);
        }
        
        return true;
    }

    // Tambah resep ke favorit
    public function addFavoriteRecipe($recipeId)
    {
        $favorites = $this->favorite_recipes ?? [];
        
        if (!in_array($recipeId, $favorites)) {
            $favorites[] = $recipeId;
            $this->favorite_recipes = array_unique($favorites);
            $this->save();
            
            return true;
        }
        
        return false;
    }

    // Hapus resep dari favorit
    public function removeFavoriteRecipe($recipeId)
    {
        $favorites = $this->favorite_recipes ?? [];
        
        if (($key = array_search($recipeId, $favorites)) !== false) {
            unset($favorites[$key]);
            $this->favorite_recipes = array_values($favorites); // Reset index
            $this->save();
            
            return true;
        }
        
        return false;
    }

    // Cek apakah resep sudah difavoritkan
    public function hasFavoritedRecipe($recipeId)
    {
        $favorites = $this->favorite_recipes ?? [];
        return in_array($recipeId, $favorites);
    }

    // Get semua resep favorit (relation)
    public function favoriteRecipes()
    {
        return $this->belongsToMany(PremiumRecipe::class, 'user_favorite_recipes', 'user_id', 'recipe_id')
                    ->withTimestamps()
                    ->where('is_active', true);
    }

    // Tambah ke riwayat resep yang dilihat
    public function addToRecipeHistory($recipeId)
    {
        $history = $this->recipe_view_history ?? [];
        
        // Tambahkan ke awal array
        array_unshift($history, [
            'recipe_id' => $recipeId,
            'viewed_at' => now()->toDateTimeString()
        ]);
        
        // Batasi hanya 50 resep terakhir
        $history = array_slice($history, 0, 50);
        
        $this->recipe_view_history = $history;
        $this->save();
    }

    // Get resep yang direkomendasikan berdasarkan mood dan preferensi
    public function getRecommendedRecipes($mood = null, $limit = 6)
    {
        if (!$this->canAccessPremiumFeatures()['access']) {
            return collect();
        }

        $query = PremiumRecipe::where('is_active', true);
        
        // Filter berdasarkan mood jika ada
        if ($mood) {
            $query->where('mood_category', $mood);
        }
        
        // Filter berdasarkan preferensi makanan user
        if ($this->food_preferences && count($this->food_preferences) > 0) {
            // Anda bisa menambahkan logika filtering berdasarkan preferensi
            // Misalnya: exclude ingredients yang tidak disukai
        }
        
        // Exclude resep yang sudah difavoritkan
        $favoriteIds = $this->favorite_recipes ?? [];
        if (count($favoriteIds) > 0) {
            $query->whereNotIn('id', $favoriteIds);
        }
        
        // Order berdasarkan cooking time yang sesuai dengan mood
        if ($mood == 'stres') {
            $query->orderBy('cooking_time', 'asc'); // Makanan cepat untuk stres
        } elseif ($mood == 'romantis') {
            $query->orderBy('difficulty', 'desc'); // Makanan special untuk romantis
        } else {
            $query->inRandomOrder();
        }
        
        return $query->limit($limit)->get();
    }

    // Get statistik penggunaan resep premium
    public function getRecipeStats()
    {
        $favoritesCount = count($this->favorite_recipes ?? []);
        $historyCount = count($this->recipe_view_history ?? []);
        
        // Hitung resep yang sudah dicoba (dari history)
        $triedRecipes = [];
        if ($this->recipe_view_history) {
            foreach ($this->recipe_view_history as $view) {
                if (isset($view['recipe_id'])) {
                    $triedRecipes[] = $view['recipe_id'];
                }
            }
            $triedRecipes = array_unique($triedRecipes);
        }
        
        return [
            'favorites_count' => $favoritesCount,
            'history_count' => $historyCount,
            'unique_tried' => count($triedRecipes),
            'premium_since' => $this->premium_until ? 
                $this->premium_until->diffForHumans(null, true) . ' tersisa' : 
                'Tidak aktif'
        ];
    }

    // =================== RELATIONS ===================

    // Relation dengan memberships
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    // Get current membership
    public function currentMembership()
    {
        return $this->memberships()
            ->where('status', 'active')
            ->where('payment_status', 'paid')
            ->where('end_date', '>', now())
            ->latest()
            ->first();
    }

    // Relation dengan premium recipes (melalui favorites)
    public function premiumRecipes()
    {
        return $this->hasMany(PremiumRecipe::class, 'chef_id'); // Jika chef juga user
    }

    // Method untuk upgrade ke premium
    public function upgradeToPremium($plan, $durationMonths)
    {
        $this->is_premium = true;
        $this->premium_plan = $plan;
        $this->premium_until = now()->addMonths($durationMonths);
        $this->save();
        
        // Buat record membership
        $this->memberships()->create([
            'plan' => $plan,
            'start_date' => now(),
            'end_date' => now()->addMonths($durationMonths),
            'status' => 'active',
            'payment_status' => 'paid'
        ]);
        
        return $this;
    }

    public function isPremiumActive()
    {
    return $this->isPremium();
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Membership;
use App\Models\PremiumRecipe;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'google_id',
        'provider',
        'role',
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
        'favorite_recipes',
        'recipe_view_history'
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
        'favorite_recipes' => 'array',
        'recipe_view_history' => 'array'
    ];

    public function getAgeAttribute()
    {
        if (!$this->birthdate) return null;
        return now()->diffInYears($this->birthdate);
    }

    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;

        $phone = preg_replace('/\D/', '', $this->phone);
        if (strlen($phone) === 12) {
            return '+62 ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 4) . ' ' . substr($phone, 9);
        }

        return $this->phone;
    }

    public function getIsActivePremiumAttribute()
    {
        if (!$this->is_premium) return false;

        if ($this->premium_until && $this->premium_until->isPast()) {
            $this->update(['is_premium' => false]);
            return false;
        }

        return true;
    }

    public function isPremium()
    {
        if ($this->isAdmin()) return true;
        return $this->is_premium && $this->premium_until > now();
    }

    public function isPremiumActive()
    {
        return $this->isPremium();
    }

    public function canAccessPremiumFeatures()
    {
        if ($this->isAdmin()) {
            return [
                'access' => true,
                'message' => 'Admin access',
                'days_remaining' => null,
                'plan' => 'admin'
            ];
        }

        if (!$this->is_premium) {
            return [
                'access' => false,
                'message' => 'Anda bukan member premium',
                'upgrade_url' => route('membership.index'),
                'expired' => false
            ];
        }

        if ($this->premium_until && $this->premium_until->isPast()) {
            $this->update(['is_premium' => false]);
            return [
                'access' => false,
                'message' => 'Membership premium Anda telah berakhir',
                'upgrade_url' => route('membership.index'),
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

    public function canAccessExclusiveRecipes()
    {
        $premiumCheck = $this->canAccessPremiumFeatures();
        
        if (!$premiumCheck['access']) {
            throw new \Exception($premiumCheck['message']);
        }

        return true;
    }

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

    public function removeFavoriteRecipe($recipeId)
    {
        $favorites = $this->favorite_recipes ?? [];

        if (($key = array_search($recipeId, $favorites)) !== false) {
            unset($favorites[$key]);
            $this->favorite_recipes = array_values($favorites);
            $this->save();
            return true;
        }

        return false;
    }

    public function hasFavoritedRecipe($recipeId)
    {
        $favorites = $this->favorite_recipes ?? [];
        return in_array($recipeId, $favorites);
    }

    public function favoriteRecipes()
    {
        return $this->belongsToMany(PremiumRecipe::class, 'user_favorite_recipes', 'user_id', 'recipe_id')
            ->withTimestamps()
            ->where('is_active', true);
    }

    public function addToRecipeHistory($recipeId)
    {
        $history = $this->recipe_view_history ?? [];

        array_unshift($history, [
            'recipe_id' => $recipeId,
            'viewed_at' => now()->toDateTimeString()
        ]);

        $history = array_slice($history, 0, 50);

        $this->recipe_view_history = $history;
        $this->save();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function currentMembership()
    {
        return $this->memberships()
            ->where('status', 'active')
            ->where('payment_status', 'paid')
            ->where('end_date', '>', now())
            ->latest()
            ->first();
    }

    public function premiumRecipes()
    {
        return $this->hasMany(PremiumRecipe::class, 'chef_id');
    }

    public function upgradeToPremium($plan, $durationMonths)
    {
        $this->is_premium = true;
        $this->premium_plan = $plan;
        $this->premium_until = now()->addMonths($durationMonths);
        $this->save();

        $this->memberships()->create([
            'plan' => $plan,
            'start_date' => now(),
            'end_date' => now()->addMonths($durationMonths),
            'status' => 'active',
            'payment_status' => 'paid'
        ]);

        return $this;
    }

    public function isAdmin()
    {
        return ($this->role ?? null) === 'admin';
    }

    public function hasRole($role)
    {
        return ($this->role ?? null) === $role;
    }
}

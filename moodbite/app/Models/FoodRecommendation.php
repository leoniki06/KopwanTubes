<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        // Basic fields (untuk semua user)
        'mood',
        'food_name',
        'restaurant_name',
        'restaurant_location',
        'description',
        'category',
        'reason',
        'rating',
        'price_range',
        'preparation_time',
        'calories',
        
        // Premium fields (full info untuk premium members)
        'is_premium',
        'premium_price',
        'location_details',
        'operational_hours',
        'contact_info',
        'website',
        'has_reservation',
        'has_delivery',
        'dietary_info',
        'image_urls',
        
        'tags'
    ];

    protected $casts = [
        // Basic casts
        'is_premium' => 'boolean',
        'has_reservation' => 'boolean',
        'has_delivery' => 'boolean',
        'premium_price' => 'decimal:2',
        'price_range' => 'decimal:2',
        'rating' => 'decimal:1',
        
        // JSON casts dengan CUSTOM ACCESSOR untuk handling data yang rusak
        // Tidak langsung cast ke array karena data bisa rusak
        // Kita akan handle dengan accessor
    ];

    // ==================== CUSTOM ACCESSORS ====================
    // Ini yang paling penting untuk fix data yang rusak!

    /**
     * Accessor untuk operational_hours
     */
    public function getOperationalHoursAttribute($value)
    {
        return $this->parseJsonField($value, []);
    }

    /**
     * Accessor untuk contact_info
     */
    public function getContactInfoAttribute($value)
    {
        return $this->parseJsonField($value, []);
    }

    /**
     * Accessor untuk dietary_info
     */
    public function getDietaryInfoAttribute($value)
    {
        return $this->parseJsonField($value, []);
    }

    /**
     * Accessor untuk tags
     */
    public function getTagsAttribute($value)
    {
        return $this->parseJsonField($value, []);
    }

    /**
     * Accessor untuk image_urls
     */
    public function getImageUrlsAttribute($value)
    {
        return $this->parseJsonField($value, []);
    }

    /**
     * Mutator untuk operational_hours (saat save ke database)
     */
    public function setOperationalHoursAttribute($value)
    {
        $this->attributes['operational_hours'] = $this->encodeToJson($value);
    }

    /**
     * Mutator untuk contact_info
     */
    public function setContactInfoAttribute($value)
    {
        $this->attributes['contact_info'] = $this->encodeToJson($value);
    }

    /**
     * Mutator untuk dietary_info
     */
    public function setDietaryInfoAttribute($value)
    {
        $this->attributes['dietary_info'] = $this->encodeToJson($value);
    }

    /**
     * Mutator untuk tags
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = $this->encodeToJson($value);
    }

    /**
     * Mutator untuk image_urls
     */
    public function setImageUrlsAttribute($value)
    {
        $this->attributes['image_urls'] = $this->encodeToJson($value);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Parse JSON field yang mungkin rusak
     */
    private function parseJsonField($value, $default = [])
    {
        // Jika null atau empty, return default
        if (empty($value)) {
            return $default;
        }

        // Jika sudah array, langsung return
        if (is_array($value)) {
            return $value;
        }

        // Coba decode sebagai JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Jika gagal, coba bersihkan string yang rusak
        return $this->cleanAndParseJson($value, $default);
    }

    /**
     * Bersihkan dan parse string JSON yang rusak
     */
    private function cleanAndParseJson($str, $default = [])
    {
        // Contoh data rusak dari file Anda:
        // "'dinner'"19:00 - 23:00\"\"special_date\"\"."
        // "{'session_1'N'09:00 - 11:001'\'session_21'\""

        // 1. Hapus karakter escape yang berlebihan
        $str = stripslashes($str);
        
        // 2. Normalize quotes: ubah single quote ke double quote untuk JSON valid
        // Tapi hati-hati dengan konten yang mengandung apostrophe
        $str = preg_replace_callback(
            '/(\w+)\s*:\s*([\'"])(.*?)\2/',
            function($matches) {
                return '"' . $matches[1] . '": "' . addslashes($matches[3]) . '"';
            },
            $str
        );
        
        // 3. Hapus karakter aneh di awal/akhir
        $str = trim($str, " \t\n\r\0\x0B\"'");
        
        // 4. Fix untuk array string yang rusak
        if (preg_match('/^\[.*\]$/', $str)) {
            // Sudah format array, coba parse
            $decoded = json_decode($str, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        } elseif (preg_match('/^\{.*\}$/', $str)) {
            // Format object
            $decoded = json_decode($str, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        } else {
            // Mungkin array string dipisah koma
            // Contoh: "vegant","halal","caffeine-free"
            $items = preg_split('/\s*,\s*/', trim($str, '[]"\''));
            if (count($items) > 1) {
                return array_map('trim', $items);
            }
            
            // Mungkin string biasa
            return [$str];
        }

        return $default;
    }

    /**
     * Encode data ke JSON untuk disimpan
     */
    private function encodeToJson($value)
    {
        if (empty($value)) {
            return null;
        }
        
        if (is_string($value)) {
            // Jika sudah string, coba decode dulu
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded, JSON_UNESCAPED_SLASHES);
            }
            // Jika bukan JSON valid, simpan sebagai array
            return json_encode([$value], JSON_UNESCAPED_SLASHES);
        }
        
        // Jika array, encode ke JSON
        return json_encode($value, JSON_UNESCAPED_SLASHES);
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope untuk rekomendasi premium saja
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }
    
    /**
     * Scope untuk rekomendasi free/regular saja
     */
    public function scopeRegular($query)
    {
        return $query->where('is_premium', false);
    }
    
    /**
     * Scope untuk mood tertentu
     */
    public function scopeByMood($query, $mood)
    {
        return $query->where('mood', $mood);
    }
    
    /**
     * Scope untuk user berdasarkan status membership
     */
    public function scopeForUser($query, $isPremiumMember = false)
    {
        if ($isPremiumMember) {
            // Premium members bisa lihat SEMUA
            return $query;
        } else {
            // Free members hanya bisa lihat yang regular
            return $query->where('is_premium', false);
        }
    }
    
    /**
     * Scope untuk makanan dengan delivery
     */
    public function scopeWithDelivery($query)
    {
        return $query->where('has_delivery', true);
    }
    
    /**
     * Scope untuk makanan dengan reservation
     */
    public function scopeWithReservation($query)
    {
        return $query->where('has_reservation', true);
    }

    // ==================== METHODS ====================
    
    /**
     * Cek apakah makanan ini memiliki fitur premium
     */
    public function hasPremiumFeatures()
    {
        return !empty($this->location_details) || 
               !empty($this->operational_hours) || 
               !empty($this->contact_info);
    }
    
    /**
     * Get primary image (untuk thumbnail)
     */
    public function getPrimaryImageAttribute()
    {
        $images = $this->image_urls;
        if (!empty($images) && is_array($images)) {
            return $images[0] ?? null;
        }
        return null;
    }
    
    /**
     * Get display price berdasarkan membership
     */
    public function getDisplayPriceAttribute()
    {
        if ($this->is_premium && $this->premium_price) {
            return $this->premium_price;
        }
        return $this->price_range;
    }
    
    /**
     * Format tags untuk display
     */
    public function getFormattedTagsAttribute()
    {
        $tags = $this->tags;
        
        if (empty($tags) || !is_array($tags)) {
            return [];
        }

        return array_map(function($tag) {
            // Hapus karakter aneh dulu
            $cleanTag = preg_replace('/[^\w\s-]/', '', $tag);
            return '#' . str_replace(' ', '_', trim($cleanTag));
        }, $tags);
    }
    
    /**
     * Cek apakah makanan ini cocok untuk dietary tertentu
     */
    public function isSuitableFor($dietary)
    {
        $dietaryInfo = $this->dietary_info;
        
        if (empty($dietaryInfo) || !is_array($dietaryInfo)) {
            return false;
        }
        
        return in_array($dietary, $dietaryInfo);
    }
    
    /**
     * Get simplified info untuk free members
     */
    public function getBasicInfoAttribute()
    {
        return [
            'id' => $this->id,
            'mood' => $this->mood,
            'food_name' => $this->food_name,
            'restaurant_name' => $this->restaurant_name,
            'description' => $this->description,
            'category' => $this->category,
            'reason' => $this->reason,
            'rating' => $this->rating,
            'price_range' => $this->price_range,
            'calories' => $this->calories,
            'is_premium' => $this->is_premium,
            'has_delivery' => $this->has_delivery,
            'primary_image' => $this->primary_image,
            'tags' => $this->tags,
        ];
    }
    
    /**
     * Get full info untuk premium members
     */
    public function getFullInfoAttribute()
    {
        $basicInfo = $this->basic_info;
        
        // Pastikan semua field premium sudah dalam format yang benar
        $premiumInfo = [
            'premium_price' => $this->premium_price,
            'location_details' => $this->location_details,
            'operational_hours' => $this->operational_hours,
            'contact_info' => $this->contact_info,
            'website' => $this->website,
            'has_reservation' => $this->has_reservation,
            'dietary_info' => $this->dietary_info,
            'image_urls' => $this->image_urls,
            'preparation_time' => $this->preparation_time,
            'restaurant_location' => $this->restaurant_location,
            'formatted_tags' => $this->formatted_tags,
        ];
        
        return array_merge($basicInfo, $premiumInfo);
    }

    /**
     * Debug method untuk melihat data mentah
     */
    public function debugRawData()
    {
        return [
            'operational_hours_raw' => $this->getRawOriginal('operational_hours'),
            'contact_info_raw' => $this->getRawOriginal('contact_info'),
            'dietary_info_raw' => $this->getRawOriginal('dietary_info'),
            'tags_raw' => $this->getRawOriginal('tags'),
            'operational_hours_parsed' => $this->operational_hours,
            'contact_info_parsed' => $this->contact_info,
            'dietary_info_parsed' => $this->dietary_info,
            'tags_parsed' => $this->tags,
        ];
    }

    // ==================== RELATIONSHIPS ====================
    
    /**
     * Relationship dengan user favorites (jika ada)
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
    /**
     * Relationship dengan reviews (jika ada)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    // ==================== STATIC METHODS ====================
    
    /**
     * Get recommended foods berdasarkan mood dan membership
     */
    public static function getRecommendations($mood, $isPremiumMember = false)
    {
        $query = self::byMood($mood);
        
        if (!$isPremiumMember) {
            $query = $query->regular();
        }
        
        return $query->orderBy('rating', 'desc')
                    ->orderBy('is_premium', 'desc') // Premium di atas
                    ->get()
                    ->map(function($item) {
                        // Pastikan data sudah diparsing dengan benar
                        return $item->full_info;
                    });
    }
    
    /**
     * Get semua mood yang tersedia
     */
    public static function getAvailableMoods()
    {
        return self::select('mood')
                  ->distinct()
                  ->orderBy('mood')
                  ->pluck('mood')
                  ->toArray();
    }
    
    /**
     * Get statistics
     */
    public static function getStats()
    {
        return [
            'total' => self::count(),
            'premium' => self::premium()->count(),
            'regular' => self::regular()->count(),
            'with_delivery' => self::withDelivery()->count(),
            'with_reservation' => self::withReservation()->count(),
        ];
    }

    /**
     * Fix semua data yang rusak di database
     * Jalankan sekali saja untuk membersihkan data
     */
    public static function fixAllBrokenData()
    {
        $items = self::all();
        $fixed = 0;
        
        foreach ($items as $item) {
            $original = $item->getOriginal();
            $dirty = false;
            
            // Force parsing untuk setiap field JSON
            $fields = ['operational_hours', 'contact_info', 'dietary_info', 'tags', 'image_urls'];
            
            foreach ($fields as $field) {
                $value = $original[$field] ?? null;
                if ($value && is_string($value)) {
                    // Paksa parsing dengan accessor
                    $parsed = $item->getAttribute($field);
                    
                    // Jika berbeda dari original, update
                    if ($value !== json_encode($parsed, JSON_UNESCAPED_SLASHES)) {
                        $item->$field = $parsed;
                        $dirty = true;
                    }
                }
            }
            
            if ($dirty) {
                $item->save();
                $fixed++;
            }
        }
        
        return "Fixed {$fixed} records";
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodRecommendation;

class FoodRecommendationSeeder extends Seeder
{
    public function run(): void
    {
        $recommendations = [
            // ==================== MEMBER PREMIUM ONLY ====================
            [
                'mood' => 'happy',
                'food_name' => 'Truffle Pasta Black Gold',
                'restaurant_name' => 'Gourmet Heaven Premium',
                'restaurant_location' => 'Plaza Senayan Lt. 5, Jakarta',
                'description' => 'Pasta dengan truffle hitam premium Italia, cream sauce truffle, dan parmesan 24 bulan',
                'category' => 'Italian Fine Dining',
                'reason' => 'Pengalaman kuliner eksklusif untuk member premium dengan bahan impor terbaik',
                'rating' => 4.9,
                'price_range' => 350000,
                'preparation_time' => '25-30 menit',
                'calories' => 720,
                // PREMIUM FEATURES
                'is_premium' => true,
                'premium_price' => 450000,
                'location_details' => 'Private dining room dengan view kota Jakarta, dress code: formal, sommelier service available',
                'operational_hours' => json_encode([
                    'senin-kamis' => '18:00 - 23:00',
                    'jumat-minggu' => '18:00 - 00:00',
                    'special_event' => 'By appointment only'
                ]),
                'contact_info' => 'Reservation: (021) 12345678 ext. 501 | WhatsApp: +62812-3456-7890',
                'website' => 'https://premium.gourmet-heaven.com',
                'has_reservation' => true,
                'has_delivery' => false,
                'dietary_info' => json_encode(['non-halal', 'contains-dairy', 'contains-gluten']),
                'image_urls' => json_encode([
                    'https://example.com/premium/truffle1.jpg',
                    'https://example.com/premium/truffle2.jpg',
                    'https://example.com/premium/truffle3.jpg'
                ]),
                'tags' => json_encode(['premium-only', 'exclusive', 'truffle', 'italian', 'fine-dining', 'member-only'])
            ],

            [
                'mood' => 'romantic',
                'food_name' => 'Golden Chocolate Fondue Experience',
                'restaurant_name' => 'L Amour Premium Dining',
                'restaurant_location' => 'Kemang Exclusive Club, Jakarta',
                'description' => 'Chocolate fondue dengan cokelat Belgia gold series, strawberry Jepang, dan buah imported',
                'category' => 'Premium Dessert',
                'reason' => 'Pengalaman romantis eksklusif dengan private butler service untuk member premium',
                'rating' => 4.9,
                'price_range' => 280000,
                'preparation_time' => '20-25 menit',
                'calories' => 450,
                // PREMIUM FEATURES
                'is_premium' => true,
                'premium_price' => 350000,
                'location_details' => 'Private rooftop dengan pemandangan kota, rose petals decoration, live piano music',
                'operational_hours' => json_encode([
                    'dinner' => '19:00 - 23:00',
                    'special_date' => '24 jam dengan reservasi 3 hari sebelumnya'
                ]),
                'contact_info' => 'Exclusive Concierge: (021) 98765432 | Priority Line for Premium Members',
                'website' => 'https://lamour-premium.com',
                'has_reservation' => true,
                'has_delivery' => false,
                'dietary_info' => json_encode(['vegetarian-option', 'contains-dairy', 'nuts-available']),
                'image_urls' => json_encode([
                    'https://example.com/premium/fondue1.jpg',
                    'https://example.com/premium/fondue2.jpg'
                ]),
                'tags' => json_encode(['premium-only', 'romantic', 'exclusive', 'date-night', 'luxury', 'member-only'])
            ],

            [
                'mood' => 'stress',
                'food_name' => 'Zen Master Tea Ceremony',
                'restaurant_name' => 'Zen Garden Premium',
                'restaurant_location' => 'Ubud, Bali (Private Villa)',
                'description' => 'Full tea ceremony experience dengan master tea dari Jepang, meditation session, dan aromatherapy',
                'category' => 'Wellness Experience',
                'reason' => 'Program destress eksklusif 2 jam untuk member premium dengan guided meditation',
                'rating' => 5.0,
                'price_range' => 500000,
                'preparation_time' => '120 menit (full experience)',
                'calories' => 80,
                // PREMIUM FEATURES
                'is_premium' => true,
                'premium_price' => 650000,
                'location_details' => 'Private zen garden villa, max 4 persons per session, traditional Japanese setting',
                'operational_hours' => json_encode([
                    'session_1' => '09:00 - 11:00',
                    'session_2' => '14:00 - 16:00',
                    'session_3' => '18:00 - 20:00'
                ]),
                'contact_info' => 'Premium Wellness Concierge: (0361) 11223344 | WhatsApp Only',
                'website' => 'https://zen-garden-premium.com',
                'has_reservation' => true,
                'has_delivery' => false,
                'dietary_info' => json_encode(['vegan', 'halal', 'caffeine-free', 'sugar-free']),
                'image_urls' => json_encode([
                    'https://example.com/premium/zen1.jpg',
                    'https://example.com/premium/zen2.jpg',
                    'https://example.com/premium/zen3.jpg'
                ]),
                'tags' => json_encode(['premium-only', 'wellness', 'meditation', 'exclusive', 'therapy', 'member-only'])
            ],

            // ==================== MEMBER FREE (Available for All) ====================
            [
                'mood' => 'happy',
                'food_name' => 'Ice Cream Sundae Special',
                'restaurant_name' => 'Sweet Heaven Ice Cream',
                'restaurant_location' => 'Jl. Makan Enak No. 123, Jakarta',
                'description' => 'Ice cream premium dengan topping cokelat leleh, kacang almond, dan buah cherry',
                'category' => 'Dessert',
                'reason' => 'Meningkatkan kebahagiaan dengan rasa manis dan tekstur lembut yang melepaskan endorfin',
                'rating' => 4.8,
                'price_range' => 45000,
                'preparation_time' => '10-15 menit',
                'calories' => 350,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['contains-dairy', 'contains-nuts']),
                'image_urls' => null,
                'tags' => json_encode(['manis', 'dingin', 'menyenangkan', 'family-friendly'])
            ],

            [
                'mood' => 'happy',
                'food_name' => 'Cokelat Panas Premium',
                'restaurant_name' => 'Chocolate Lounge',
                'restaurant_location' => 'Plaza Indonesia Lt. 3, Jakarta',
                'description' => 'Minuman cokelat Belgia dengan marshmallow homemade dan whipped cream',
                'category' => 'Minuman',
                'reason' => 'Cokelat berkualitas tinggi melepaskan serotonin yang membuat bahagia',
                'rating' => 4.6,
                'price_range' => 55000,
                'preparation_time' => '5-10 menit',
                'calories' => 280,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['contains-dairy', 'vegetarian']),
                'image_urls' => null,
                'tags' => json_encode(['hangat', 'comfort-food', 'nyaman', 'belgia'])
            ],

            [
                'mood' => 'sad',
                'food_name' => 'Bubur Ayam Special',
                'restaurant_name' => 'Bubur Ayam 24 Jam',
                'restaurant_location' => 'Jl. Kenangan No. 45, Bandung',
                'description' => 'Bubur nasi lembut dengan suwiran ayam kampung, cakwe, dan kuah kaldu ayam',
                'category' => 'Makanan Berat',
                'reason' => 'Memberikan kenyamanan dan kehangatan seperti pelukan ibu',
                'rating' => 4.5,
                'price_range' => 35000,
                'preparation_time' => '15-20 menit',
                'calories' => 320,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['halal', 'contains-chicken']),
                'image_urls' => null,
                'tags' => json_encode(['hangat', 'nyaman', 'bergizi', 'tradisional', 'indonesia'])
            ],

            [
                'mood' => 'sad',
                'food_name' => 'Sup Tomat Creamy',
                'restaurant_name' => 'Soup Nation',
                'restaurant_location' => 'Grand Indonesia, Jakarta',
                'description' => 'Sup tomat kental dengan roti sourdough panggang dan basil segar',
                'category' => 'Soup',
                'reason' => 'Rasa asam manis tomat dan keju dapat meningkatkan mood secara alami',
                'rating' => 4.3,
                'price_range' => 65000,
                'preparation_time' => '20-25 menit',
                'calories' => 220,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegetarian', 'contains-dairy', 'gluten-option']),
                'image_urls' => null,
                'tags' => json_encode(['hangat', 'sehat', 'creamy', 'internasional'])
            ],

            [
                'mood' => 'energetic',
                'food_name' => 'Superfood Smoothie Bowl',
                'restaurant_name' => 'Healthy Bowls Cafe',
                'restaurant_location' => 'Jl. Sehat No. 88, Bali',
                'description' => 'Campuran buah tropis, chia seeds, granola, dan topping superfood',
                'category' => 'Sarapan',
                'reason' => 'Memberikan energi alami dan tahan lama dari buah-buahan dan superfood',
                'rating' => 4.9,
                'price_range' => 75000,
                'preparation_time' => '10-15 menit',
                'calories' => 380,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegan', 'gluten-free', 'dairy-free']),
                'image_urls' => null,
                'tags' => json_encode(['sehat', 'segar', 'berenergi', 'vegan', 'breakfast'])
            ],

            [
                'mood' => 'energetic',
                'food_name' => 'Protein Power Salad',
                'restaurant_name' => 'Fit Kitchen',
                'restaurant_location' => 'Senayan City, Jakarta',
                'description' => 'Salad dengan quinoa, ayam panggang, alpukat, dan dressing lemon',
                'category' => 'Salad',
                'reason' => 'Protein tinggi memberikan energi tahan lama untuk aktivitas seharian',
                'rating' => 4.7,
                'price_range' => 85000,
                'preparation_time' => '15-20 menit',
                'calories' => 420,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['high-protein', 'gluten-free', 'contains-chicken']),
                'image_urls' => null,
                'tags' => json_encode(['sehat', 'protein', 'energi', 'high-protein', 'lunch'])
            ],

            [
                'mood' => 'stress',
                'food_name' => 'Teh Chamomile Lavender',
                'restaurant_name' => 'Zen Tea House',
                'restaurant_location' => 'Jl. Tenang No. 12, Yogyakarta',
                'description' => 'Teh herbal chamomile dengan lavender dan madu organik',
                'category' => 'Minuman',
                'reason' => 'Kombinasi chamomile dan lavender membantu mengurangi stres dan menenangkan pikiran',
                'rating' => 4.8,
                'price_range' => 35000,
                'preparation_time' => '5-7 menit',
                'calories' => 40,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegan', 'halal', 'caffeine-free', 'organic']),
                'image_urls' => null,
                'tags' => json_encode(['tenang', 'herbal', 'relaks', 'organik', 'tea'])
            ],

            [
                'mood' => 'romantic',
                'food_name' => 'Chocolate Dipped Strawberries',
                'restaurant_name' => 'Romantic Bites',
                'restaurant_location' => 'Kemang, Jakarta',
                'description' => 'Strawberry segar dicelup cokelat Belgia dengan taburan gold leaf',
                'category' => 'Dessert',
                'reason' => 'Kombinasi manis strawberry dan cokelat menciptakan atmosfer romantis',
                'rating' => 4.9,
                'price_range' => 95000,
                'preparation_time' => '10-15 menit',
                'calories' => 180,
                // FREE MEMBER - BASIC INFO ONLY
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegetarian', 'contains-dairy']),
                'image_urls' => null,
                'tags' => json_encode(['manis', 'romantis', 'istimewa', 'date-night', 'dessert'])
            ],

            // ==================== HYBRID (Basic for Free, Enhanced for Premium) ====================
            [
                'mood' => 'happy',
                'food_name' => 'Sushi Omakase Experience',
                'restaurant_name' => 'Sakura Japanese Dining',
                'restaurant_location' => 'Pacific Place, Jakarta',
                'description' => 'Chef selection sushi dengan bahan segar import langsung dari Jepang',
                'category' => 'Japanese',
                'reason' => 'Pengalaman sushi authentic yang meningkatkan mood dengan presentasi artistik',
                'rating' => 4.8,
                'price_range' => 300000,
                'preparation_time' => '30-40 menit',
                'calories' => 550,
                // HYBRID - Basic info for free, enhanced for premium
                'is_premium' => true,
                'premium_price' => 450000,
                'location_details' => 'FREE: Sushi bar regular seating | PREMIUM: Private sushi counter dengan chef personal',
                'operational_hours' => json_encode([
                    'free_member' => '11:00 - 22:00',
                    'premium_member' => 'Special slot: 18:00 - 21:00 (priority booking)'
                ]),
                'contact_info' => 'FREE: (021) 55556666 | PREMIUM: Priority Line ext. 888',
                'website' => 'https://sakura-dining.com',
                'has_reservation' => true,
                'has_delivery' => false,
                'dietary_info' => json_encode(['contains-fish', 'gluten-option', 'soy-sauce']),
                'image_urls' => json_encode([
                    'https://example.com/hybrid/sushi1.jpg'
                ]),
                'tags' => json_encode(['japanese', 'sushi', 'omakase', 'premium-option'])
            ],
        ];

        foreach ($recommendations as $recommendation) {
            FoodRecommendation::create($recommendation);
        }

        $this->command->info('✅ Food recommendations seeded successfully!');
        $this->command->info('📊 Total: ' . count($recommendations) . ' recommendations');
        $this->command->info('   👑 Premium: ' . collect($recommendations)->where('is_premium', true)->count());
        $this->command->info('   🆓 Free: ' . collect($recommendations)->where('is_premium', false)->count());
    }
}
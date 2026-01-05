<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodRecommendation;

class FoodRecommendationSeeder extends Seeder
{
    public function run(): void
    {
        $recommendations = [

            // ============================================================
            // ✅ PREMIUM ONLY (EXCLUSIVE)
            // ============================================================

            // ✅ HAPPY PREMIUM
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
                'tags' => json_encode(['premium-only','exclusive','truffle','italian','fine-dining','member-only'])
            ],

            // ✅ ROMANTIC PREMIUM
            [
                'mood' => 'romantic',
                'food_name' => 'Golden Chocolate Fondue Experience',
                'restaurant_name' => "L'Amour Premium Dining",
                'restaurant_location' => 'Kemang Exclusive Club, Jakarta',
                'description' => 'Chocolate fondue dengan cokelat Belgia gold series, strawberry Jepang, dan buah imported',
                'category' => 'Premium Dessert',
                'reason' => 'Pengalaman romantis eksklusif dengan private butler service untuk member premium',
                'rating' => 4.9,
                'price_range' => 280000,
                'preparation_time' => '20-25 menit',
                'calories' => 450,
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
                'dietary_info' => json_encode(['vegetarian-option','contains-dairy','nuts-available']),
                'image_urls' => json_encode([
                    'https://example.com/premium/fondue1.jpg',
                    'https://example.com/premium/fondue2.jpg'
                ]),
                'tags' => json_encode(['premium-only','romantic','exclusive','date-night','luxury','member-only'])
            ],

            // ✅ STRESS PREMIUM
            [
                'mood' => 'stress',
                'food_name' => 'Zen Master Tea Ceremony',
                'restaurant_name' => 'Zen Garden Premium',
                'restaurant_location' => 'Ubud, Bali (Private Villa)',
                'description' => 'Full tea ceremony dengan master tea Jepang + meditation session + aromatherapy',
                'category' => 'Wellness Experience',
                'reason' => 'Program destress eksklusif 2 jam untuk premium dengan guided meditation',
                'rating' => 5.0,
                'price_range' => 500000,
                'preparation_time' => '120 menit (full experience)',
                'calories' => 80,
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
                'dietary_info' => json_encode(['vegan','halal','caffeine-free','sugar-free']),
                'image_urls' => json_encode([
                    'https://example.com/premium/zen1.jpg',
                    'https://example.com/premium/zen2.jpg',
                    'https://example.com/premium/zen3.jpg'
                ]),
                'tags' => json_encode(['premium-only','wellness','meditation','exclusive','therapy','member-only'])
            ],

            // ✅ SAD PREMIUM (NEW ✅)
            [
                'mood' => 'sad',
                'food_name' => 'Comfort Wagyu Donburi',
                'restaurant_name' => 'Kumo Premium Japanese',
                'restaurant_location' => 'Senopati, Jakarta Selatan',
                'description' => 'Wagyu A5 bowl dengan onsen egg, miso butter, dan garlic chips crispy.',
                'category' => 'Premium Comfort Food',
                'reason' => 'Rasa savory gurih + tekstur lembut membantu meningkatkan hormon bahagia saat mood sedih.',
                'rating' => 4.9,
                'price_range' => 250000,
                'preparation_time' => '20-25 menit',
                'calories' => 680,
                'is_premium' => true,
                'premium_price' => 320000,
                'location_details' => 'Premium counter seat + complimentary ocha refill, tersedia mini dessert free.',
                'operational_hours' => json_encode([
                    'senin-minggu' => '12:00 - 23:00',
                ]),
                'contact_info' => '(021) 9877-1222 | WhatsApp: +62812-2222-3333',
                'website' => 'https://kumo-premium.com',
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['contains-beef','contains-egg','halal-option']),
                'image_urls' => json_encode([
                    'https://example.com/premium/wagyu1.jpg'
                ]),
                'tags' => json_encode(['premium-only','comfort','wagyu','japanese','boost-mood'])
            ],

            // ✅ ENERGETIC PREMIUM (NEW ✅)
            [
                'mood' => 'energetic',
                'food_name' => 'Energy Boost Salmon Poke Deluxe',
                'restaurant_name' => 'Poke Lab Premium',
                'restaurant_location' => 'SCBD, Jakarta',
                'description' => 'Poke bowl salmon premium dengan quinoa, edamame, alpukat, dan spicy mayo.',
                'category' => 'Premium Healthy Bowl',
                'reason' => 'Protein tinggi + omega-3 membantu tubuh tetap fokus, kuat, dan berenergi lebih lama.',
                'rating' => 4.9,
                'price_range' => 180000,
                'preparation_time' => '15-20 menit',
                'calories' => 520,
                'is_premium' => true,
                'premium_price' => 220000,
                'location_details' => 'Topping bisa custom unlimited untuk premium + free infused water.',
                'operational_hours' => json_encode([
                    'senin-jumat' => '10:00 - 22:00',
                    'sabtu-minggu' => '10:00 - 23:00',
                ]),
                'contact_info' => '(021) 8833-2233 | WhatsApp: +62812-7777-9999',
                'website' => 'https://pokelab-premium.com',
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['contains-fish','gluten-free-option']),
                'image_urls' => json_encode([
                    'https://example.com/premium/poke1.jpg'
                ]),
                'tags' => json_encode(['premium-only','healthy','poke','energetic','protein'])
            ],

            // ✅ HUNGRY PREMIUM (NEW ✅)
            [
                'mood' => 'hungry',
                'food_name' => 'Ultimate Feast Platter XL',
                'restaurant_name' => 'MoodBite Premium Feast House',
                'restaurant_location' => 'PIM 2, Jakarta',
                'description' => 'Platter jumbo: steak, chicken wings, fries, pasta creamy, dan dessert mini.',
                'category' => 'Premium Platter',
                'reason' => 'Kalau lagi lapar parah, ini paket paling pas karena super lengkap dan bikin kenyang maksimal.',
                'rating' => 4.9,
                'price_range' => 400000,
                'preparation_time' => '35-45 menit',
                'calories' => 1200,
                'is_premium' => true,
                'premium_price' => 500000,
                'location_details' => 'Private booth untuk premium + priority serving, free refill minuman 1x.',
                'operational_hours' => json_encode([
                    'everyday' => '11:00 - 23:00',
                ]),
                'contact_info' => '(021) 8899-0000 | WhatsApp: +62811-0000-9999',
                'website' => 'https://premium-feast.com',
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['contains-dairy','contains-gluten','halal']),
                'image_urls' => json_encode([
                    'https://example.com/premium/feast1.jpg'
                ]),
                'tags' => json_encode(['premium-only','hungry','big-portion','feast','xl'])
            ],

            // ✅ EXTRA PREMIUM MOODS (new benefit mood for premium)
            [
                'mood' => 'focus',
                'food_name' => 'Matcha Brain Booster Latte',
                'restaurant_name' => 'Mindful Brew Premium',
                'restaurant_location' => 'Kuningan, Jakarta',
                'description' => 'Matcha premium Uji Jepang + oat milk + honey drizzle.',
                'category' => 'Premium Drink',
                'reason' => 'L-theanine pada matcha membantu fokus tanpa bikin jitter kayak kopi.',
                'rating' => 4.8,
                'price_range' => 85000,
                'preparation_time' => '5-10 menit',
                'calories' => 180,
                'is_premium' => true,
                'premium_price' => 110000,
                'location_details' => 'Work-friendly cafe dengan colokan setiap meja + WiFi premium.',
                'operational_hours' => json_encode([
                    'everyday' => '08:00 - 22:00',
                ]),
                'contact_info' => '(021) 8899-4444',
                'website' => 'https://mindfulbrew.com',
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegetarian','dairy-free-option']),
                'image_urls' => null,
                'tags' => json_encode(['premium','focus','matcha','study','work'])
            ],

            [
                'mood' => 'sleepy',
                'food_name' => 'Warm Almond Milk & Honey',
                'restaurant_name' => 'Calm Night Premium',
                'restaurant_location' => 'Menteng, Jakarta',
                'description' => 'Susu almond hangat dengan madu + cinnamon + vanilla aroma.',
                'category' => 'Wellness Drink',
                'reason' => 'Menenangkan sistem saraf dan membantu kamu relax sebelum tidur.',
                'rating' => 4.8,
                'price_range' => 65000,
                'preparation_time' => '7-10 menit',
                'calories' => 160,
                'is_premium' => true,
                'premium_price' => 85000,
                'location_details' => 'Ada private silent lounge + aromatherapy candle di meja.',
                'operational_hours' => json_encode([
                    'everyday' => '16:00 - 01:00',
                ]),
                'contact_info' => '(021) 8111-1234',
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegan-option','gluten-free']),
                'image_urls' => null,
                'tags' => json_encode(['premium','sleepy','warm','relax','night'])
            ],

            [
                'mood' => 'party',
                'food_name' => 'Fire Cheese Volcano Chicken',
                'restaurant_name' => 'Party Bite Premium',
                'restaurant_location' => 'Kelapa Gading, Jakarta',
                'description' => 'Ayam panggang spicy dengan lelehan cheese volcano dan nachos.',
                'category' => 'Party Food',
                'reason' => 'Cocok buat mood party: spicy, cheesy, rame, dan bikin happy rame-rame!',
                'rating' => 4.8,
                'price_range' => 160000,
                'preparation_time' => '20-30 menit',
                'calories' => 900,
                'is_premium' => true,
                'premium_price' => 210000,
                'location_details' => 'Ada live DJ weekend + premium booth seat.',
                'operational_hours' => json_encode([
                    'weekday' => '12:00 - 23:00',
                    'weekend' => '12:00 - 01:00',
                ]),
                'contact_info' => '(021) 9090-9090',
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['halal','contains-dairy']),
                'image_urls' => null,
                'tags' => json_encode(['premium','party','spicy','cheese','hangout'])
            ],

            // ============================================================
            // ✅ FREE MEMBER (AVAILABLE FOR ALL)
            // ============================================================

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
                'tags' => json_encode(['manis','dingin','menyenangkan','family-friendly'])
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
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['halal','contains-chicken']),
                'image_urls' => null,
                'tags' => json_encode(['hangat','nyaman','bergizi','tradisional','indonesia'])
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
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegetarian','contains-dairy','gluten-option']),
                'image_urls' => null,
                'tags' => json_encode(['hangat','sehat','creamy','internasional'])
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
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegan','gluten-free','dairy-free']),
                'image_urls' => null,
                'tags' => json_encode(['sehat','segar','berenergi','vegan','breakfast'])
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
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegan','halal','caffeine-free','organic']),
                'image_urls' => null,
                'tags' => json_encode(['tenang','herbal','relaks','organik','tea'])
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
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => true,
                'has_delivery' => true,
                'dietary_info' => json_encode(['vegetarian','contains-dairy']),
                'image_urls' => null,
                'tags' => json_encode(['manis','romantis','istimewa','date-night','dessert'])
            ],

            // ✅ HUNGRY FREE (NEW ✅)
            [
                'mood' => 'hungry',
                'food_name' => 'Nasi Goreng Jumbo Spesial',
                'restaurant_name' => 'Warung Kenyang',
                'restaurant_location' => 'Jl. Pahlawan No. 99, Surabaya',
                'description' => 'Nasi goreng porsi jumbo dengan ayam suwir, telur, kerupuk, dan sambal ekstra.',
                'category' => 'Makanan Berat',
                'reason' => 'Kalau lapar banget, ini paling aman karena porsinya besar dan mengenyangkan.',
                'rating' => 4.7,
                'price_range' => 38000,
                'preparation_time' => '10-15 menit',
                'calories' => 780,
                'is_premium' => false,
                'premium_price' => null,
                'location_details' => null,
                'operational_hours' => null,
                'contact_info' => null,
                'website' => null,
                'has_reservation' => false,
                'has_delivery' => true,
                'dietary_info' => json_encode(['halal','contains-egg','contains-chicken']),
                'image_urls' => null,
                'tags' => json_encode(['jumbotron','mengenyangkan','murah','pedas-option'])
            ],
        ];

        // ✅ ANTI DUPLICATE: updateOrCreate
        foreach ($recommendations as $rec) {
            FoodRecommendation::updateOrCreate(
                [
                    'mood' => $rec['mood'],
                    'food_name' => $rec['food_name'],
                    'restaurant_name' => $rec['restaurant_name'],
                ],
                $rec
            );
        }

        $this->command->info('✅ Food recommendations seeded successfully!');
        $this->command->info('📊 Total: ' . count($recommendations) . ' recommendations');
        $this->command->info('   👑 Premium: ' . collect($recommendations)->where('is_premium', true)->count());
        $this->command->info('   🆓 Free: ' . collect($recommendations)->where('is_premium', false)->count());
    }
}

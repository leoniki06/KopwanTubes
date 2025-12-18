<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PremiumRecipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'chef_name' => 'Chef Juna Rorimpandey',
                'chef_photo' => 'chefs/juna.jpg',
                'recipe_name' => 'Beef Wellington Premium',
                'description' => 'Beef Wellington dengan teknik modern, menggunakan daging sapi wagyu grade A5 dan pastry handmade. Hidangan sempurna untuk anniversary atau dinner romantis.',
                'mood_category' => 'romantis',
                'ingredients' => json_encode([
                    ['name' => 'Daging Sapi Wagyu A5', 'quantity' => '500', 'unit' => 'gram'],
                    ['name' => 'Puff Pastry', 'quantity' => '250', 'unit' => 'gram'],
                    ['name' => 'Jamur Champignon', 'quantity' => '200', 'unit' => 'gram'],
                    ['name' => 'Dijon Mustard', 'quantity' => '2', 'unit' => 'sdm'],
                    ['name' => 'Red Wine', 'quantity' => '100', 'unit' => 'ml'],
                    ['name' => 'Thyme Fresh', 'quantity' => '3', 'unit' => 'batang'],
                    ['name' => 'Bawang Putih', 'quantity' => '3', 'unit' => 'siung'],
                    ['name' => 'Butter Unsalted', 'quantity' => '50', 'unit' => 'gram'],
                    ['name' => 'Sea Salt', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Black Pepper', 'quantity' => '1/2', 'unit' => 'sdt']
                ]),
                'step_by_step' => json_encode([
                    [
                        'step_number' => 1,
                        'instruction' => 'Siapkan daging wagyu, bumbui dengan garam dan merica. Panaskan wajan, sear daging selama 2 menit per sisi hingga kecoklatan.',
                        'duration' => '10 menit',
                        'tip' => 'Gunakan api tinggi untuk mendapatkan crust yang sempurna'
                    ],
                    [
                        'step_number' => 2,
                        'instruction' => 'Tumis jamur champignon cincang dengan butter dan bawang putih hingga semua air menguap.',
                        'duration' => '15 menit',
                        'tip' => 'Pastikan jamur benar-benar kering agar pastry tidak basah'
                    ],
                    [
                        'step_number' => 3,
                        'instruction' => 'Olesi daging dengan mustard, lalu lapisi dengan tumisan jamur.',
                        'duration' => '5 menit',
                        'tip' => 'Dinginkan daging terlebih dahulu sebelum dibungkus pastry'
                    ],
                    [
                        'step_number' => 4,
                        'instruction' => 'Bungkus daging dengan puff pastry, rekatkan ujung-ujungnya. Olesi dengan kuning telur.',
                        'duration' => '10 menit',
                        'tip' => 'Beri pola pada pastry dengan pisau untuk estetika'
                    ],
                    [
                        'step_number' => 5,
                        'instruction' => 'Panggang dalam oven 200°C selama 25 menit atau hingga pastry keemasan.',
                        'duration' => '25 menit',
                        'tip' => 'Istirahatkan 10 menit sebelum diiris'
                    ]
                ]),
                'video_url' => 'https://www.youtube.com/watch?v=C5k3PwCqP8s',
                'difficulty' => 'Sulit',
                'cooking_time' => 65,
                'servings' => 2,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'chef_name' => 'Chef Renatta Moeloek',
                'chef_photo' => 'chefs/renatta.jpg',
                'recipe_name' => 'Truffle Pasta Carbonara',
                'description' => 'Pasta carbonara dengan truffle hitam asli Italia, keju pecorino romano, dan teknik autentik tanpa krim. Cocok untuk mood bahagia!',
                'mood_category' => 'bahagia',
                'ingredients' => json_encode([
                    ['name' => 'Spaghetti', 'quantity' => '200', 'unit' => 'gram'],
                    ['name' => 'Guanciale (bisa pakai pancetta)', 'quantity' => '100', 'unit' => 'gram'],
                    ['name' => 'Pecorino Romano', 'quantity' => '80', 'unit' => 'gram'],
                    ['name' => 'Kuning Telur', 'quantity' => '3', 'unit' => 'butir'],
                    ['name' => 'Telur Utuh', 'quantity' => '1', 'unit' => 'butir'],
                    ['name' => 'Truffle Hitam', 'quantity' => '15', 'unit' => 'gram'],
                    ['name' => 'Lada Hitam', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Garam Laut', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Extra Virgin Olive Oil', 'quantity' => '2', 'unit' => 'sdm']
                ]),
                'step_by_step' => json_encode([
                    [
                        'step_number' => 1,
                        'instruction' => 'Rebus spaghetti dalam air mendidih dengan garam hingga al dente (kurang 1 menit dari waktu kemasan).',
                        'duration' => '8 menit',
                        'tip' => 'Simpan 1 cup air pasta untuk saus'
                    ],
                    [
                        'step_number' => 2,
                        'instruction' => 'Potong guanciale menjadi dadu kecil, tumis dengan api kecil hingga garing dan mengeluarkan minyak.',
                        'duration' => '10 menit',
                        'tip' => 'Jangan buang minyak dari guanciale, ini kunci rasa'
                    ],
                    [
                        'step_number' => 3,
                        'instruction' => 'Dalam mangkuk, kocok kuning telur, telur utuh, dan keju pecorino parut. Tambahkan lada hitam kasar.',
                        'duration' => '3 menit',
                        'tip' => 'Jangan tambah garam dulu, keju sudah asin'
                    ],
                    [
                        'step_number' => 4,
                        'instruction' => 'Campur pasta dengan guanciale, matikan api. Tambahkan campuran telur, aduk cepat dengan sedikit air pasta.',
                        'duration' => '2 menit',
                        'tip' => 'Aduk cepat agar telur tidak menjadi scramble'
                    ],
                    [
                        'step_number' => 5,
                        'instruction' => 'Parut truffle hitam di atas pasta sebelum disajikan.',
                        'duration' => '2 menit',
                        'tip' => 'Gunakan truffle slicer untuk irisan tipis'
                    ]
                ]),
                'video_url' => 'https://www.youtube.com/watch?v=J-fm7FULdSg',
                'difficulty' => 'Sedang',
                'cooking_time' => 25,
                'servings' => 2,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'chef_name' => 'Chef Arnold Poernomo',
                'chef_photo' => 'chefs/arnold.jpg',
                'recipe_name' => 'Salted Egg Lava Cake',
                'description' => 'Lava cake dengan isian salted egg yolk yang creamy dan melimpah. Comfort food terbaik untuk mengusir kesedihan.',
                'mood_category' => 'sedih',
                'ingredients' => json_encode([
                    ['name' => 'Dark Chocolate 70%', 'quantity' => '150', 'unit' => 'gram'],
                    ['name' => 'Butter Unsalted', 'quantity' => '100', 'unit' => 'gram'],
                    ['name' => 'Telur', 'quantity' => '2', 'unit' => 'butir'],
                    ['name' => 'Kuning Telur', 'quantity' => '2', 'unit' => 'butir'],
                    ['name' => 'Gula Pasir', 'quantity' => '60', 'unit' => 'gram'],
                    ['name' => 'Tepung Terigu', 'quantity' => '40', 'unit' => 'gram'],
                    ['name' => 'Kuning Telur Asin', 'quantity' => '4', 'unit' => 'butir'],
                    ['name' => 'Butter Cream', 'quantity' => '50', 'unit' => 'gram'],
                    ['name' => 'Susu Kental Manis', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Vanilla Extract', 'quantity' => '1', 'unit' => 'sdt']
                ]),
                'step_by_step' => json_encode([
                    [
                        'step_number' => 1,
                        'instruction' => 'Lelehkan cokelat dan butter dalam mangkuk di atas panci berisi air mendidih (double boiler).',
                        'duration' => '10 menit',
                        'tip' => 'Aduk terus agar tidak gosong'
                    ],
                    [
                        'step_number' => 2,
                        'instruction' => 'Kocok telur, kuning telur, dan gula hingga pucat dan mengembang.',
                        'duration' => '5 menit',
                        'tip' => 'Gunakan mixer kecepatan tinggi'
                    ],
                    [
                        'step_number' => 3,
                        'instruction' => 'Campur cokelat leleh dengan adonan telur, lalu ayak tepung di atasnya.',
                        'duration' => '3 menit',
                        'tip' => 'Aduk dengan spatula, jangan overmix'
                    ],
                    [
                        'step_number' => 4,
                        'instruction' => 'Untuk filling: haluskan kuning telur asin, campur dengan butter cream dan susu kental manis.',
                        'duration' => '5 menit',
                        'tip' => 'Bisa ditambahkan keju parut untuk extra flavor'
                    ],
                    [
                        'step_number' => 5,
                        'instruction' => 'Tuang adonan ke dalam ramekin yang sudah dioles butter, tambahkan 1 sdm filling di tengah.',
                        'duration' => '5 menit',
                        'tip' => 'Pastikan filling tertutup rapat oleh adonan'
                    ],
                    [
                        'step_number' => 6,
                        'instruction' => 'Panggang dalam oven 200°C selama 12-14 menit. Sajikan segera.',
                        'duration' => '14 menit',
                        'tip' => 'Jangan terlalu lama agar lava tetap cair'
                    ]
                ]),
                'video_url' => 'https://www.youtube.com/watch?v=XYZ123456',
                'difficulty' => 'Sedang',
                'cooking_time' => 42,
                'servings' => 4,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'chef_name' => 'Chef Farah Quinn',
                'chef_photo' => 'chefs/farah.jpg',
                'recipe_name' => 'Mediterranean Buddha Bowl',
                'description' => 'Bowl sehat dengan quinoa, roasted vegetables, chickpeas, dan lemon tahini dressing. Perfect untuk boost energi!',
                'mood_category' => 'semangat',
                'ingredients' => json_encode([
                    ['name' => 'Quinoa', 'quantity' => '1', 'unit' => 'cup'],
                    ['name' => 'Chickpeas kaleng', 'quantity' => '400', 'unit' => 'gram'],
                    ['name' => 'Ubi Jalar', 'quantity' => '1', 'unit' => 'buah besar'],
                    ['name' => 'Zucchini', 'quantity' => '1', 'unit' => 'buah'],
                    ['name' => 'Paprika Merah & Kuning', 'quantity' => '2', 'unit' => 'buah'],
                    ['name' => 'Bawang Merah', 'quantity' => '1', 'unit' => 'buah'],
                    ['name' => 'Tahini', 'quantity' => '3', 'unit' => 'sdm'],
                    ['name' => 'Air Perasan Lemon', 'quantity' => '1', 'unit' => 'buah'],
                    ['name' => 'Bawang Putih', 'quantity' => '2', 'unit' => 'siung'],
                    ['name' => 'Minyak Zaitun', 'quantity' => '3', 'unit' => 'sdm'],
                    ['name' => 'Bubuk Jinten', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Bubuk Paprika', 'quantity' => '1', 'unit' => 'sdt']
                ]),
                'step_by_step' => json_encode([
                    [
                        'step_number' => 1,
                        'instruction' => 'Cuci quinoa, masak dengan 2 cup air hingga empuk (15-20 menit). Sisihkan.',
                        'duration' => '20 menit',
                        'tip' => 'Bisa diganti dengan brown rice atau farro'
                    ],
                    [
                        'step_number' => 2,
                        'instruction' => 'Potong semua sayuran menjadi dadu, campur dengan minyak zaitun dan bumbu, panggang di oven 200°C selama 25 menit.',
                        'duration' => '30 menit',
                        'tip' => 'Jangan terlalu penuh di baking tray agar sayuran matang merata'
                    ],
                    [
                        'step_number' => 3,
                        'instruction' => 'Untuk chickpeas: tiriskan dan keringkan, campur dengan minyak zaitun dan bumbu, panggang 15 menit hingga renyah.',
                        'duration' => '20 menit',
                        'tip' => 'Goyang-goyangkan baking sheet setengah jalan'
                    ],
                    [
                        'step_number' => 4,
                        'instruction' => 'Buat dressing: kocok tahini, air perasan lemon, bawang putih parut, dan sedikit air hingga creamy.',
                        'duration' => '5 menit',
                        'tip' => 'Tambahkan air sedikit demi sedikit hingga mencapai konsistensi yang diinginkan'
                    ],
                    [
                        'step_number' => 5,
                        'instruction' => 'Susun quinoa, sayuran panggang, chickpeas dalam bowl, siram dengan dressing. Tambahkan microgreens.',
                        'duration' => '5 menit',
                        'tip' => 'Bisa tambahkan alpukat atau biji-bijian untuk extra crunch'
                    ]
                ]),
                'video_url' => 'https://www.youtube.com/watch?v=ABC789012',
                'difficulty' => 'Mudah',
                'cooking_time' => 80,
                'servings' => 2,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'chef_name' => 'Chef Vindex Tengker',
                'chef_photo' => 'chefs/vindex.jpg',
                'recipe_name' => 'Indonesian Rendang Wagyu',
                'description' => 'Rendang dengan daging wagyu, slow-cooked dengan rempah asli Minang. Comfort food tingkat dewa!',
                'mood_category' => 'stres',
                'ingredients' => json_encode([
                    ['name' => 'Daging Wagyu Sirloin', 'quantity' => '800', 'unit' => 'gram'],
                    ['name' => 'Santan Kental', 'quantity' => '1', 'unit' => 'liter'],
                    ['name' => 'Kelapa Parut Sangrai', 'quantity' => '200', 'unit' => 'gram'],
                    ['name' => 'Serai', 'quantity' => '3', 'unit' => 'batang'],
                    ['name' => 'Daun Jeruk', 'quantity' => '8', 'unit' => 'lembar'],
                    ['name' => 'Daun Salam', 'quantity' => '4', 'unit' => 'lembar'],
                    ['name' => 'Lengkuas', 'quantity' => '3', 'unit' => 'cm'],
                    ['name' => 'Bumbu Halus: Cabe Merah', 'quantity' => '15', 'unit' => 'buah'],
                    ['name' => 'Bumbu Halus: Bawang Merah', 'quantity' => '10', 'unit' => 'siung'],
                    ['name' => 'Bumbu Halus: Bawang Putih', 'quantity' => '5', 'unit' => 'siung'],
                    ['name' => 'Bumbu Halus: Jahe', 'quantity' => '3', 'unit' => 'cm'],
                    ['name' => 'Bumbu Halus: Kunyit', 'quantity' => '2', 'unit' => 'cm']
                ]),
                'step_by_step' => json_encode([
                    [
                        'step_number' => 1,
                        'instruction' => 'Potong daging wagyu sesuai serat, sisihkan.',
                        'duration' => '10 menit',
                        'tip' => 'Potong melawan serat agar lebih empuk'
                    ],
                    [
                        'step_number' => 2,
                        'instruction' => 'Tumis bumbu halus dengan sedikit minyak hingga wangi dan matang.',
                        'duration' => '15 menit',
                        'tip' => 'Tumis dengan api kecil hingga benar-benar matang'
                    ],
                    [
                        'step_number' => 3,
                        'instruction' => 'Masukkan daging, aduk hingga berubah warna. Tambahkan santan, daun rempah, dan kelapa sangrai.',
                        'duration' => '10 menit',
                        'tip' => 'Aduk terus agar santan tidak pecah'
                    ],
                    [
                        'step_number' => 4,
                        'instruction' => 'Masak dengan api kecil selama 4-5 jam hingga bumbu meresap dan kering.',
                        'duration' => '300 menit',
                        'tip' => 'Aduk sesekali agar tidak gosong'
                    ],
                    [
                        'step_number' => 5,
                        'instruction' => 'Koreksi rasa, sajikan dengan nasi hangat.',
                        'duration' => '5 menit',
                        'tip' => 'Rendang lebih enak keesokan harinya'
                    ]
                ]),
                'video_url' => 'https://www.youtube.com/watch?v=DEF345678',
                'difficulty' => 'Sulit',
                'cooking_time' => 340,
                'servings' => 6,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        // Insert data ke database
        foreach ($recipes as $recipe) {
            DB::table('premium_recipes')->insert($recipe);
        }

        $this->command->info('✅ Successfully seeded ' . count($recipes) . ' premium recipes!');
        $this->command->info('👨‍🍳 Chefs: Juna, Renatta, Arnold, Farah, Vindex');
        $this->command->info('🎯 Moods: romantis, bahagia, sedih, semangat, stres');
    }
}
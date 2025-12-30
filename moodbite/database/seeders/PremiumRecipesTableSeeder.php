<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PremiumRecipesTableSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            [
                'chef_name' => 'Chef Davina Hermawan',
                'chef_photo' => null,
                'recipe_name' => 'Dori Krispi Saus Creamy',
                'description' => 'Ikan dori krispi disajikan dengan garlic butter rice dan saus creamy gurih, dilengkapi lelehan mozzarella. Cocok untuk comfort food harian yang praktis dan lezat.',
                'mood_category' => 'bahagia',
                'ingredients' => json_encode([
                    // Dori
                    ['name' => 'Ikan dori filet', 'quantity' => '150', 'unit' => 'gram'],
                    ['name' => 'Tepung bumbu serbaguna', 'quantity' => '5', 'unit' => 'sdm'],
                    ['name' => 'Air', 'quantity' => '3', 'unit' => 'sdm'],
                    ['name' => 'Daun bawang (opsional)', 'quantity' => '1', 'unit' => 'batang'],

                    // Garlic butter rice
                    ['name' => 'Nasi putih', 'quantity' => '250', 'unit' => 'gram'],
                    ['name' => 'Wortel rebus (opsional)', 'quantity' => '70', 'unit' => 'gram'],
                    ['name' => 'Margarin', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Bawang putih cincang', 'quantity' => '2', 'unit' => 'siung'],
                    ['name' => 'Telur', 'quantity' => '1', 'unit' => 'butir'],
                    ['name' => 'Kaldu ayam bubuk', 'quantity' => '½', 'unit' => 'sdt'],
                    ['name' => 'Merica', 'quantity' => '⅛', 'unit' => 'sdt'],
                    ['name' => 'Kecap asin', 'quantity' => '½', 'unit' => 'sdm'],

                    // Creamy sauce
                    ['name' => 'Margarin', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Tepung bumbu serbaguna', 'quantity' => '1½', 'unit' => 'sdm'],
                    ['name' => 'Air', 'quantity' => '100', 'unit' => 'ml'],
                    ['name' => 'Susu cair', 'quantity' => '100', 'unit' => 'ml'],
                    ['name' => 'Gula pasir', 'quantity' => '¼', 'unit' => 'sdt'],

                    // Lainnya
                    ['name' => 'Keju mozzarella parut', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Minyak goreng', 'quantity' => 'secukupnya', 'unit' => '']
                ]),
                'step_by_step' => json_encode([
                    [
                        'step_number' => 1,
                        'instruction' => 'Panaskan wajan, masukkan margarin dan tepung bumbu serbaguna, masak sebentar lalu tambahkan air dan susu.',
                        'duration' => '5 menit',
                        'tip' => 'Aduk cepat agar tidak menggumpal'
                    ],
                    [
                        'step_number' => 2,
                        'instruction' => 'Tambahkan gula pasir, aduk hingga saus creamy mengental. Sisihkan.',
                        'duration' => '3 menit',
                        'tip' => 'Gunakan api kecil agar saus tidak pecah'
                    ],
                    [
                        'step_number' => 3,
                        'instruction' => 'Campurkan tepung bumbu dan air, masukkan ikan dori dan daun bawang, aduk rata.',
                        'duration' => '5 menit',
                        'tip' => 'Pastikan ikan terlapisi adonan dengan rata'
                    ],
                    [
                        'step_number' => 4,
                        'instruction' => 'Balurkan ikan ke tepung bumbu kering, goreng hingga kecokelatan dan tiriskan.',
                        'duration' => '7 menit',
                        'tip' => 'Gunakan minyak panas agar hasilnya krispi'
                    ],
                    [
                        'step_number' => 5,
                        'instruction' => 'Tumis margarin dan bawang putih hingga wangi, masukkan telur lalu orak-arik.',
                        'duration' => '3 menit',
                        'tip' => 'Gunakan api sedang'
                    ],
                    [
                        'step_number' => 6,
                        'instruction' => 'Masukkan nasi dan wortel, bumbui dengan kaldu, merica, dan kecap asin, aduk rata.',
                        'duration' => '5 menit',
                        'tip' => 'Aduk cepat agar nasi tidak lembek'
                    ],
                    [
                        'step_number' => 7,
                        'instruction' => 'Sajikan garlic butter rice, ikan dori krispi, siram saus creamy, taburi mozzarella lalu torch.',
                        'duration' => '5 menit',
                        'tip' => 'Jika tidak ada torch, bisa dipanggang sebentar'
                    ]
                ]),
                'video_url' => 'https://youtu.be/Yn4Vp28kpm4?si=nByYRDppx-GY5h87',
                'difficulty' => 'Mudah',
                'cooking_time' => 30,
                'servings' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'chef_name' => 'Chef Davina Hermawan',
                'chef_photo' => null,
                'recipe_name' => 'Sup Ayam Krim',
                'description' => 'Sup ayam krim yang hangat dan creamy, dilengkapi sesame stick renyah. Comfort food lembut yang cocok untuk menemani suasana hati sedang sedih.',
                'mood_category' => 'sedih',
                'ingredients' => json_encode([
                    // Marinasi ayam
                    ['name' => 'Paha ayam filet', 'quantity' => '200', 'unit' => 'gram'],
                    ['name' => 'Garam', 'quantity' => '¾', 'unit' => 'sdt'],
                    ['name' => 'Merica', 'quantity' => '⅛', 'unit' => 'sdt'],

                    // Bahan sup
                    ['name' => 'Bawang putih', 'quantity' => '2', 'unit' => 'siung'],
                    ['name' => 'Bawang bombai', 'quantity' => '½', 'unit' => 'buah'],
                    ['name' => 'Seledri (opsional)', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Wortel', 'quantity' => '1', 'unit' => 'buah'],
                    ['name' => 'Jagung pipil', 'quantity' => '50', 'unit' => 'gram'],
                    ['name' => 'Mentega tawar', 'quantity' => '2', 'unit' => 'sdm'],
                    ['name' => 'Tepung terigu protein sedang', 'quantity' => '3', 'unit' => 'sdm'],
                    ['name' => 'Air', 'quantity' => '350', 'unit' => 'ml'],
                    ['name' => 'Susu cair', 'quantity' => '350', 'unit' => 'ml'],
                    ['name' => 'Kaldu ayam bubuk', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Gula pasir', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Parsley kering', 'quantity' => '½', 'unit' => 'sdt'],

                    // Sesame stick
                    ['name' => 'Danish pastry', 'quantity' => '1', 'unit' => 'lembar'],
                    ['name' => 'Garam', 'quantity' => '¼', 'unit' => 'sdt'],
                    ['name' => 'Merica', 'quantity' => '⅛', 'unit' => 'sdt'],
                    ['name' => 'Telur kocok', 'quantity' => '1', 'unit' => 'butir'],
                    ['name' => 'Wijen putih', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Wijen hitam', 'quantity' => 'secukupnya', 'unit' => '']
                ]),
                'step_by_step' => json_encode([
                    [
                        'step_number' => 1,
                        'instruction' => 'Oles danish pastry dengan telur kocok lalu taburi garam, merica, wijen putih, dan wijen hitam.',
                        'duration' => '5 menit',
                        'tip' => 'Tekan ringan agar wijen menempel'
                    ],
                    [
                        'step_number' => 2,
                        'instruction' => 'Potong-potong pastry, tarik memanjang dan letakkan di loyang beralas baking paper.',
                        'duration' => '5 menit',
                        'tip' => 'Tarikan ringan membuat tekstur lebih renyah'
                    ],
                    [
                        'step_number' => 3,
                        'instruction' => 'Kerat bagian tengah pastry dengan belakang pisau, proofing 1 jam lalu panggang 7 menit pada suhu 190°C.',
                        'duration' => '67 menit',
                        'tip' => 'Proofing membuat pastry lebih flaky'
                    ],
                    [
                        'step_number' => 4,
                        'instruction' => 'Marinasi ayam dengan garam dan merica. Potong bawang bombai dan wortel, cincang bawang putih, iris seledri.',
                        'duration' => '10 menit',
                        'tip' => 'Potongan seragam membantu matang merata'
                    ],
                    [
                        'step_number' => 5,
                        'instruction' => 'Panaskan sedikit minyak, goreng ayam hingga kecokelatan lalu tiriskan dan potong-potong.',
                        'duration' => '7 menit',
                        'tip' => 'Goreng hingga permukaan ayam berwarna keemasan'
                    ],
                    [
                        'step_number' => 6,
                        'instruction' => 'Di wajan yang sama, masukkan mentega, tumis bawang bombai dan bawang putih hingga wangi, masukkan tepung terigu dan aduk hingga kecokelatan.',
                        'duration' => '5 menit',
                        'tip' => 'Aduk terus agar tepung tidak menggumpal'
                    ],
                    [
                        'step_number' => 7,
                        'instruction' => 'Masukkan air sedikit demi sedikit sambil diaduk, lalu masukkan seledri, wortel, jagung, ayam, susu, kaldu, parsley, dan gula.',
                        'duration' => '10 menit',
                        'tip' => 'Tambahkan cairan bertahap agar tekstur creamy'
                    ],
                    [
                        'step_number' => 8,
                        'instruction' => 'Masak hingga sup mendidih dan mengental. Sajikan hangat bersama sesame stick.',
                        'duration' => '5 menit',
                        'tip' => 'Sajikan selagi panas untuk rasa terbaik'
                    ]
                ]),
                'video_url' => 'https://youtu.be/WH33vKaQJug?si=6bylROThg0-7omba',
                'difficulty' => 'Mudah',
                'cooking_time' => 45,
                'servings' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'chef_name' => 'Chef Davina Hermawan',
                'chef_photo' => null,
                'recipe_name' => 'Grilled Chicken Mushroom Sauce',
                'description' => 'Dada ayam panggang dengan saus jamur creamy yang gurih, disajikan bersama kentang dan baby buncis. Cocok untuk menu western rumahan yang praktis dan elegan.',
                'mood_category' => 'bahagia',
                'ingredients' => json_encode([
                    // Ayam
                    ['name' => 'Dada ayam fillet', 'quantity' => '1', 'unit' => 'pasang'],
                    ['name' => 'Minyak', 'quantity' => '2', 'unit' => 'sdm'],
                    ['name' => 'Bawang putih halus / bubuk', 'quantity' => '2', 'unit' => 'sdt'],
                    ['name' => 'Mustard', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Jus lemon / kecap inggris', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Bubuk paprika', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Garam', 'quantity' => '¾', 'unit' => 'sdt'],
                    ['name' => 'Gula', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Lada hitam', 'quantity' => '¼', 'unit' => 'sdt'],

                    // Saus mushroom
                    ['name' => 'Jamur shitake', 'quantity' => '2', 'unit' => 'buah'],
                    ['name' => 'Jamur champignon', 'quantity' => '10', 'unit' => 'buah'],
                    ['name' => 'Bawang putih halus', 'quantity' => '4', 'unit' => 'siung'],
                    ['name' => 'Bawang merah halus', 'quantity' => '5', 'unit' => 'siung'],
                    ['name' => 'Thyme', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Mentega', 'quantity' => '2', 'unit' => 'sdm'],
                    ['name' => 'Krim cair', 'quantity' => '150', 'unit' => 'ml'],
                    ['name' => 'Air', 'quantity' => '200', 'unit' => 'ml'],
                    ['name' => 'Garam', 'quantity' => '¼', 'unit' => 'sdt'],
                    ['name' => 'Gula', 'quantity' => '½', 'unit' => 'sdt'],
                    ['name' => 'Merica', 'quantity' => '¼', 'unit' => 'sdt'],

                    // Pelengkap
                    ['name' => 'Kentang rebus', 'quantity' => '1', 'unit' => 'buah'],
                    ['name' => 'Baby buncis rebus', 'quantity' => '½', 'unit' => 'pack'],
                    ['name' => 'Bawang putih cincang', 'quantity' => '3', 'unit' => 'siung'],
                    ['name' => 'Mentega', 'quantity' => '4', 'unit' => 'sdm'],
                    ['name' => 'Parsley cincang', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Lemon', 'quantity' => 'secukupnya', 'unit' => '']
                ]),
                'step_by_step' => json_encode([
                    ['step_number' => 1, 'instruction' => 'Keringkan ayam dengan tisu lalu pipihkan menggunakan rolling pin atau benda tumpul.', 'duration' => '5 menit', 'tip' => 'Pipihkan agar ayam matang merata'],
                    ['step_number' => 2, 'instruction' => 'Marinasi ayam dengan minyak, bawang putih, mustard, jus lemon, paprika, garam, gula, dan lada hitam.', 'duration' => '5 menit', 'tip' => 'Baluri ayam hingga benar-benar rata'],
                    ['step_number' => 3, 'instruction' => 'Tutup ayam dengan cling wrap dan diamkan minimal 1 jam.', 'duration' => '60 menit', 'tip' => 'Bisa disimpan di kulkas untuk rasa lebih meresap'],
                    ['step_number' => 4, 'instruction' => 'Cincang jamur shitake dan champignon, sisihkan.', 'duration' => '5 menit', 'tip' => 'Potong kecil agar saus lebih halus'],
                    ['step_number' => 5, 'instruction' => 'Rebus baby buncis sebentar, bilas air dingin, tiriskan.', 'duration' => '5 menit', 'tip' => 'Air dingin menjaga warna tetap hijau'],
                    ['step_number' => 6, 'instruction' => 'Panaskan minyak, panggang ayam hingga kecokelatan di kedua sisi.', 'duration' => '10 menit', 'tip' => 'Jangan sering dibalik agar ayam juicy'],
                    ['step_number' => 7, 'instruction' => 'Masukkan kentang dan buncis, tambahkan sebagian mentega dan bawang putih, tumis hingga kecokelatan.', 'duration' => '7 menit', 'tip' => 'Gunakan api sedang'],
                    ['step_number' => 8, 'instruction' => 'Masukkan parsley dan perasan lemon, aduk lalu angkat kentang dan buncis.', 'duration' => '2 menit', 'tip' => 'Lemon memberi rasa segar'],
                    ['step_number' => 9, 'instruction' => 'Masukkan sisa mentega, siram lelehan mentega ke ayam, lalu tiriskan.', 'duration' => '3 menit', 'tip' => 'Butter basting bikin ayam lebih juicy'],
                    ['step_number' => 10, 'instruction' => 'Tumis jamur, masukkan bawang putih, bawang merah, dan thyme hingga wangi.', 'duration' => '7 menit', 'tip' => 'Gunakan api kecil agar tidak gosong'],
                    ['step_number' => 11, 'instruction' => 'Tambahkan krim, air, garam, gula, dan merica. Masak hingga saus mengental.', 'duration' => '5 menit', 'tip' => 'Aduk perlahan'],
                    ['step_number' => 12, 'instruction' => 'Sajikan grilled chicken dengan saus mushroom, kentang, dan baby buncis.', 'duration' => '2 menit', 'tip' => 'Sajikan selagi hangat'],
                ]),
                'video_url' => 'https://youtu.be/TcYCcXxMLfU?si=x_aOHo85rGZSI-gG',
                'difficulty' => 'Sedang',
                'cooking_time' => 45,
                'servings' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'chef_name' => 'Chef Davina Hermawan',
                'chef_photo' => null,
                'recipe_name' => 'Salmon & Spinach Puff Pastry with Mushroom Sauce',
                'description' => 'Salmon berbumbu dibungkus puff pastry renyah dengan isian bayam, disajikan bersama saus jamur creamy. Hidangan kaya rasa yang menenangkan saat pikiran sedang stres.',
                'mood_category' => 'stres',
                'ingredients' => json_encode([
                    ['name' => 'Fillet salmon', 'quantity' => '500', 'unit' => 'gram'],
                    ['name' => 'Bayam rebus (diperas)', 'quantity' => '500', 'unit' => 'gram'],
                    ['name' => 'Puff pastry ukuran besar', 'quantity' => '3', 'unit' => 'lembar'],
                    ['name' => 'Butter', 'quantity' => '4', 'unit' => 'sdm'],
                    ['name' => 'Mustard', 'quantity' => '2', 'unit' => 'sdm'],
                    ['name' => 'Bawang putih', 'quantity' => '5–6', 'unit' => 'siung'],
                    ['name' => 'Bawang bombai', 'quantity' => '¼', 'unit' => 'buah'],
                    ['name' => 'Daun dill', 'quantity' => '1', 'unit' => 'genggam'],
                    ['name' => 'Daun basil', 'quantity' => '1', 'unit' => 'genggam'],
                    ['name' => 'Minyak goreng', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Telur', 'quantity' => '2', 'unit' => 'butir'],
                    ['name' => 'Tepung terigu', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Gula', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Garam', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Merica', 'quantity' => 'secukupnya', 'unit' => ''],

                    // Mushroom sauce
                    ['name' => 'Jamur shitake', 'quantity' => '100', 'unit' => 'gram'],
                    ['name' => 'Jamur champignon', 'quantity' => '100', 'unit' => 'gram'],
                    ['name' => 'Minyak goreng', 'quantity' => '5', 'unit' => 'sdm'],
                    ['name' => 'Bawang putih', 'quantity' => '3', 'unit' => 'siung'],
                    ['name' => 'Bawang bombai', 'quantity' => '¼', 'unit' => 'buah'],
                    ['name' => 'Butter', 'quantity' => '4–5', 'unit' => 'sdm'],
                    ['name' => 'Krim cair', 'quantity' => '250', 'unit' => 'ml'],
                    ['name' => 'Gula', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Garam', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Merica', 'quantity' => 'secukupnya', 'unit' => '']
                ]),
                'step_by_step' => json_encode([
                    ['step_number' => 1, 'instruction' => 'Potong salmon lalu bumbui dengan mustard, gula, garam, merica, dan minyak.', 'duration' => '10 menit', 'tip' => 'Diamkan sebentar agar bumbu meresap'],
                    ['step_number' => 2, 'instruction' => 'Cincang dill dan basil. Campur bawang putih, bawang bombai, herbs, garam, dan butter.', 'duration' => '8 menit', 'tip' => 'Gunakan butter suhu ruang'],
                    ['step_number' => 3, 'instruction' => 'Oles puff pastry dengan butter, letakkan salmon dan bayam, lalu tutup dan rapatkan. Oles telur.', 'duration' => '15 menit', 'tip' => 'Peras bayam agar tidak berair'],
                    ['step_number' => 4, 'instruction' => 'Panggang 200°C selama 35–45 menit hingga keemasan.', 'duration' => '45 menit', 'tip' => 'Sesuaikan dengan oven'],
                    ['step_number' => 5, 'instruction' => 'Saus: tumis jamur hingga kering, masukkan bawang, tuang krim, bumbui, tambahkan butter hingga mengental.', 'duration' => '18 menit', 'tip' => 'Aduk perlahan agar krim tidak pecah'],
                    ['step_number' => 6, 'instruction' => 'Sajikan puff pastry salmon bersama mushroom sauce.', 'duration' => '2 menit', 'tip' => 'Potong setelah agak dingin agar rapi'],
                ]),
                'video_url' => 'https://youtu.be/9LKrvgvDZkQ?si=fsH6yLTiOe1t2IZ0',
                'difficulty' => 'Sulit',
                'cooking_time' => 90,
                'servings' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'chef_name' => 'Chef Davina Hermawan',
                'chef_photo' => null,
                'recipe_name' => 'Bolu Kukus Ketan Cokelat Keju Lumer',
                'description' => 'Bolu kukus berbahan tepung ketan dengan rasa cokelat yang lembut dan isian keju lumer di tengah. Comfort food manis yang cocok untuk menemani suasana sedih.',
                'mood_category' => 'sedih',
                'ingredients' => json_encode([
                    ['name' => 'Telur', 'quantity' => '3', 'unit' => 'butir'],
                    ['name' => 'Gula pasir', 'quantity' => '120', 'unit' => 'gram'],
                    ['name' => 'Gula palem (opsional)', 'quantity' => '80', 'unit' => 'gram'],
                    ['name' => 'SP', 'quantity' => '½', 'unit' => 'sdt'],
                    ['name' => 'Perisa vanila', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Garam', 'quantity' => '⅓', 'unit' => 'sdt'],
                    ['name' => 'Susu cair', 'quantity' => '170', 'unit' => 'gram'],
                    ['name' => 'Mentega cair', 'quantity' => '120', 'unit' => 'gram'],
                    ['name' => 'Larutan kopi (opsional)', 'quantity' => '15', 'unit' => 'ml'],
                    ['name' => 'Baking powder (opsional)', 'quantity' => '½', 'unit' => 'sdt'],
                    ['name' => 'Cokelat bubuk', 'quantity' => '50', 'unit' => 'gram'],
                    ['name' => 'Tepung ketan', 'quantity' => '185', 'unit' => 'gram'],

                    ['name' => 'Keju oles', 'quantity' => '160', 'unit' => 'gram'],
                    ['name' => 'Kental manis putih', 'quantity' => '3', 'unit' => 'sdm'],
                    ['name' => 'Jeruk nipis', 'quantity' => '1–2', 'unit' => 'sdt']
                ]),
                'step_by_step' => json_encode([
                    ['step_number' => 1, 'instruction' => 'Mixer telur, gula, SP, vanila, dan garam hingga pucat mengembang.', 'duration' => '5 menit', 'tip' => 'Kecepatan tinggi'],
                    ['step_number' => 2, 'instruction' => 'Masukkan bahan kering sambil disaring. Tuang susu, mentega, dan kopi. Mixer sebentar hingga rata.', 'duration' => '3 menit', 'tip' => 'Jangan overmix'],
                    ['step_number' => 3, 'instruction' => 'Panaskan loyang yang sudah dioles minyak 2 menit. Tuang adonan ¾ loyang dan kukus 15–20 menit.', 'duration' => '20 menit', 'tip' => 'Lap tutup kukusan'],
                    ['step_number' => 4, 'instruction' => 'Campur keju oles, kental manis, dan jeruk nipis. Masukkan plastik segitiga.', 'duration' => '3 menit', 'tip' => 'Jeruk nipis bikin segar'],
                    ['step_number' => 5, 'instruction' => 'Lubangi tengah bolu, semprot isian, tuang sisa adonan, kukus lagi 15–20 menit.', 'duration' => '20 menit', 'tip' => 'Jangan tembus dasar'],
                    ['step_number' => 6, 'instruction' => 'Sajikan bolu selagi hangat.', 'duration' => '2 menit', 'tip' => 'Keju masih lumer'],
                ]),
                'video_url' => 'https://youtu.be/UqmN24qojH4?si=t-uSFBq7pGPFSDQL',
                'difficulty' => 'Sedang',
                'cooking_time' => 55,
                'servings' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'chef_name' => 'Chef Davina Hermawan',
                'chef_photo' => null,
                'recipe_name' => 'Ayam Iris Rebus Chili Oil',
                'description' => 'Ayam iris rebus yang lembut disajikan dengan chili oil pedas gurih. Menu ringan dan segar yang cocok untuk meningkatkan energi dan fokus.',
                // NOTE: kalau di DB kamu pakai "berenergi", ganti ini balik.
                'mood_category' => 'semangat',
                'ingredients' => json_encode([
                    ['name' => 'Dada ayam filet', 'quantity' => '300', 'unit' => 'gram'],
                    ['name' => 'Maizena', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Putih telur', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Kaldu ayam bubuk', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Merica', 'quantity' => '⅛', 'unit' => 'sdt'],
                    ['name' => 'Gula pasir', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Garam', 'quantity' => '½', 'unit' => 'sdt'],

                    ['name' => 'Minyak', 'quantity' => '2', 'unit' => 'sdm'],
                    ['name' => 'Bawang putih', 'quantity' => '5', 'unit' => 'siung'],
                    ['name' => 'Daun bawang', 'quantity' => '1', 'unit' => 'batang'],
                    ['name' => 'Cabai rawit merah', 'quantity' => '3', 'unit' => 'buah'],
                    ['name' => 'Cabai bubuk kasar', 'quantity' => '1½', 'unit' => 'sdm'],
                    ['name' => 'Kecap asin', 'quantity' => '2', 'unit' => 'sdt'],
                    ['name' => 'Saus tiram', 'quantity' => '2', 'unit' => 'sdt'],
                    ['name' => 'Gula pasir', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Air', 'quantity' => '25', 'unit' => 'ml'],
                    ['name' => 'Minyak wijen', 'quantity' => '½', 'unit' => 'sdt'],
                    ['name' => 'Jeruk nipis', 'quantity' => '1', 'unit' => 'iris'],

                    ['name' => 'Jahe', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Nasi putih', 'quantity' => 'secukupnya', 'unit' => ''],
                ]),
                'step_by_step' => json_encode([
                    ['step_number' => 1, 'instruction' => 'Iris tipis dada ayam lalu marinasi dengan putih telur, maizena, garam, merica, gula, dan kaldu ayam bubuk.', 'duration' => '5 menit', 'tip' => 'Iris tipis agar cepat matang'],
                    ['step_number' => 2, 'instruction' => 'Siapkan chili oil: iris daun bawang & cabai, cincang bawang putih.', 'duration' => '5 menit', 'tip' => ''],
                    ['step_number' => 3, 'instruction' => 'Tumis bawang putih hingga kekuningan, masukkan cabai bubuk, rawit, dan daun bawang hingga wangi.', 'duration' => '3 menit', 'tip' => 'Api sedang'],
                    ['step_number' => 4, 'instruction' => 'Masukkan kecap asin, saus tiram, gula, dan air. Matikan api, tambahkan minyak wijen.', 'duration' => '2 menit', 'tip' => 'Minyak wijen terakhir'],
                    ['step_number' => 5, 'instruction' => 'Rebus air hingga mendidih, masukkan jahe dan ayam, rebus 1–2 menit lalu tiriskan.', 'duration' => '2 menit', 'tip' => 'Jangan kelamaan'],
                    ['step_number' => 6, 'instruction' => 'Sajikan ayam dengan chili oil, nikmati dengan nasi hangat.', 'duration' => '2 menit', 'tip' => ''],
                ]),
                'video_url' => 'https://youtu.be/atchhnZpYV8?si=aHiuGecYTzvsAtKu',
                'difficulty' => 'Mudah',
                'cooking_time' => 25,
                'servings' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'chef_name' => 'Chef Davina Hermawan',
                'chef_photo' => null,
                'recipe_name' => 'Sambal Cumi Asin',
                'description' => 'Sambal cumi asin pedas gurih dengan aroma daun jeruk yang khas. Lauk sederhana namun bikin nagih, cocok sebagai comfort food saat suasana hati sedang sedih.',
                'mood_category' => 'sedih',
                'ingredients' => json_encode([
                    ['name' => 'Cumi asin', 'quantity' => '150', 'unit' => 'gram'],
                    ['name' => 'Bawang putih', 'quantity' => '8', 'unit' => 'siung'],
                    ['name' => 'Bawang merah', 'quantity' => '15', 'unit' => 'siung'],
                    ['name' => 'Cabai merah keriting', 'quantity' => '100', 'unit' => 'gram'],
                    ['name' => 'Cabai rawit merah', 'quantity' => '60', 'unit' => 'gram'],
                    ['name' => 'Daun jeruk', 'quantity' => '5', 'unit' => 'lembar'],
                    ['name' => 'Minyak goreng', 'quantity' => '200–250', 'unit' => 'ml'],
                    ['name' => 'Garam', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'MSG', 'quantity' => '1', 'unit' => 'sdt'],
                    ['name' => 'Gula pasir', 'quantity' => '1', 'unit' => 'sdm'],
                    ['name' => 'Nasi putih', 'quantity' => 'secukupnya', 'unit' => ''],
                    ['name' => 'Kerupuk', 'quantity' => 'secukupnya', 'unit' => ''],
                ]),
                'step_by_step' => json_encode([
                    ['step_number' => 1, 'instruction' => 'Bilas cumi asin dengan air panas, cuci bersih, keringkan, lalu potong-potong.', 'duration' => '5 menit', 'tip' => 'Kurangi asin berlebih'],
                    ['step_number' => 2, 'instruction' => 'Goreng cumi hingga sedikit kering lalu tiriskan.', 'duration' => '5 menit', 'tip' => 'Jangan terlalu lama agar tidak keras'],
                    ['step_number' => 3, 'instruction' => 'Tumis bawang putih, bawang merah, cabai, dan daun jeruk sebentar lalu haluskan.', 'duration' => '7 menit', 'tip' => 'Haluskan selagi panas'],
                    ['step_number' => 4, 'instruction' => 'Tumis kembali sambal, tambahkan minyak, garam, MSG, dan gula. Masak hingga tanak dan berminyak.', 'duration' => '10 menit', 'tip' => 'Agar sambal awet'],
                    ['step_number' => 5, 'instruction' => 'Masukkan cumi goreng, aduk dan masak sesaat.', 'duration' => '3 menit', 'tip' => 'Aduk pelan'],
                    ['step_number' => 6, 'instruction' => 'Sajikan dengan nasi putih dan kerupuk.', 'duration' => '2 menit', 'tip' => ''],
                ]),
                'video_url' => 'https://youtu.be/abn1xe3zR7c?si=NFXxpRaqB3tglRj3',
                'difficulty' => 'Sedang',
                'cooking_time' => 35,
                'servings' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('premium_recipes')->insert($recipes);

        $this->command->info('✅ Successfully seeded ' . count($recipes) . ' premium recipes!');
        $this->command->info('👨‍🍳 Chefs: Davina Hermawan');
        $this->command->info('🎯 Moods: bahagia, sedih, semangat, stres');
    }
}

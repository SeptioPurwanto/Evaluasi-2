<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $u = fn (string $id) => "https://images.unsplash.com/photo-{$id}?w=600&q=80";

        // Data diambil dari src/utils/data.js project React SneakerLab
        $shoes = [
            ['SneakerLab Swift 450', 450000, 5, 'Running', 'Sepatu lari ringan dengan bantalan responsif untuk jarak menengah.', '1542291026-7eec264c27ff'],
            ['Pro Marathon X', 650000, 3, 'Running', 'Dirancang untuk pelari maraton dengan sol stabil dan bantalan tebal.', '1606107557195-0e29a4b5b4aa'],
            ['AeroRun Blue', 520000, 7, 'Running', 'Upper mesh breathable, cocok untuk lari pagi harian.', '1571008887538-b36bb32f4571'],
            ['Tempo Grey Runner', 480000, 4, 'Running', 'Ringan dan fleksibel untuk latihan tempo dan interval.', '1562183241-b937e95585b6'],
            ['Court Master', 720000, 2, 'Sport', 'Cengkeraman kuat untuk lapangan basket indoor.', '1552346154-21d32810aba3'],
            ['Hoops Shadow', 680000, 6, 'Sport', 'Bantalan tumit empuk dan pergelangan stabil untuk basket.', '1605348532760-6753d2c43329'],
            ['Baseline Red', 590000, 3, 'Sport', 'Desain agresif dengan sol traksi tinggi untuk permainan cepat.', '1605523741177-cd660595c2cf'],
            ['Dunk Zone Pro', 750000, 8, 'Sport', 'Sepatu performa tinggi untuk latihan dan pertandingan.', '1595909336425-5bf541155dec'],
            ['Urban Street Low', 390000, 9, 'Casual', 'Siluet low-top putih-hitam bergaya urban, nyaman seharian.', '1587563871167-1ee9c731aefb'],
            ['Classic Canvas High', 340000, 0, 'Casual', 'Sneakers canvas high-top klasik untuk gaya santai.', '1612942910539-9ff28b2e00d3'],
            ['Daily Mono', 360000, 10, 'Casual', 'Warna monokrom yang mudah dipadukan dengan outfit apa pun.', '1641997465126-c73cc4070337'],
            ['Cloud White', 410000, 5, 'Casual', 'Sneakers putih bersih untuk kuliah, kerja, dan jalan-jalan.', '1620989928625-08536e746255'],
            ['Trail Hunter', 710000, 6, 'Outdoor', 'Sol bergerigi dan tahan air untuk medan trail.', '1575987116913-e96e7d490b8a'],
            ['Summit Boot', 830000, 4, 'Outdoor', 'Boots kulit untuk pendakian dengan perlindungan pergelangan.', '1600100315760-ac46b65f6fdd'],
            ['Rockside Black', 640000, 3, 'Outdoor', 'Sepatu hitam tangguh untuk jalur berbatu dan perjalanan jauh.', '1655976795374-6b519928f1e1'],
            ['Ridge Leather Lace', 780000, 5, 'Outdoor', 'Boots kulit tali klasik, awet untuk petualangan.', '1606036525923-525fa3b35465'],
        ];

        foreach ($shoes as [$name, $price, $stock, $category, $desc, $photo]) {
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $desc,
                'category_id' => Category::where('category', $category)->value('category_id'),
                'price' => $price,
                'stock' => $stock,
                'img' => $u($photo),
            ]);
        }
    }
}

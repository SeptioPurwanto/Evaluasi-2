<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Running', 'Sport', 'Casual', 'Outdoor'] as $name) {
            DB::table('categories')->insert([
                'category' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

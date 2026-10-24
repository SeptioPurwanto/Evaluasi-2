<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin untuk login back page dan REST API
        User::updateOrCreate(
            ['email' => 'admin@sneakerlab.test'],
            ['name' => 'Admin SneakerLab', 'password' => bcrypt('password')]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}

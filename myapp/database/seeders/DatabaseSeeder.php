<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::factory()->create([
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //     ]);
    // }

    // public function run(): void
    // {
    //     // Panggil seeder khusus untuk user default
    //     $this->call(InitialUsersSeeder::class);
    //     $this->call(SupplierSeeder::class);
    // }
    public function run(): void
    {
        $this->call([
            InitialUsersSeeder::class,
            SupplierSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }

}

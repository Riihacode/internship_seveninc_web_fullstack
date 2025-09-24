<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $categories = [
            ['name' => 'Elektronik', 'description' => 'Barang-barang elektronik rumah tangga & gadget'],
            ['name' => 'Furniture', 'description' => 'Perabot rumah dan kantor'],
            ['name' => 'Alat Tulis Kantor', 'description' => 'ATK untuk keperluan kantor & sekolah'],
            ['name' => 'Makanan & Minuman', 'description' => 'Produk konsumsi harian'],
            ['name' => 'Pakaian', 'description' => 'Busana pria, wanita, dan anak'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

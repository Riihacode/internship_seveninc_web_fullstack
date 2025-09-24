<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     //
    // }
    public function run(): void
    {
        $electronics = Category::where('name', 'Elektronik')->first();
        $furniture   = Category::where('name', 'Furniture')->first();

        $supplier1   = Supplier::where('name', 'PT Elektronik Jaya')->first();
        $supplier2   = Supplier::where('name', 'CV Maju Bersama')->first();

        $products = [
            [
                'category_id'   => $electronics->id,
                'supplier_id'   => $supplier1->id,
                'name'          => 'Smartphone XYZ',
                'sku'           => 'ELEC-001',
                'description'   => 'Smartphone terbaru dengan fitur lengkap',
                'purchase_price'=> 3000000,
                'selling_price' => 3500000,
                'minimum_stock' => 10,
                'current_stock' => 50,
            ],
            [
                'category_id'   => $furniture->id,
                'supplier_id'   => $supplier2->id,
                'name'          => 'Kursi Kantor Ergonomis',
                'sku'           => 'FURN-001',
                'description'   => 'Kursi kantor dengan sandaran ergonomis',
                'purchase_price'=> 500000,
                'selling_price' => 750000,
                'minimum_stock' => 5,
                'current_stock' => 20,
            ],
        ];

        // foreach ($products as $p) {
        //     Product::create($p);
        // }
        foreach ($products as $p) {
            Product::updateOrCreate(
                ['sku' => $p['sku']], // cek berdasarkan SKU
                $p                     // kalau belum ada → create, kalau sudah ada → update
            );
        }
    }
}

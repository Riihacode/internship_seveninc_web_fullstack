<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SupplierSeeder extends Seeder
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
        $suppliers = [
            [
                'name'    => 'PT Elektronik Jaya',
                'address' => 'Jl. Sudirman No. 10, Jakarta',
                'phone'   => '021-1234567',
                'email'   => 'elektronikjaya@example.com',
            ],
            [
                'name'    => 'CV Maju Bersama',
                'address' => 'Jl. Malioboro No. 5, Yogyakarta',
                'phone'   => '0274-987654',
                'email'   => 'maju@example.com',
            ],
            [
                'name'    => 'UD Sumber Rejeki',
                'address' => 'Jl. Diponegoro No. 25, Surabaya',
                'phone'   => '031-555555',
                'email'   => 'sumberrejeki@example.com',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}

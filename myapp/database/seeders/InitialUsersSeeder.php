<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InitialUsersSeeder extends Seeder
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
        User::updateOrCreate(
            ['email' => 'admin@stockify.local'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'Admin']
        );
        
        User::updateOrCreate(
            ['email' => 'manager@stockify.local'],
            ['name' => 'Manager', 'password' => Hash::make('password'), 'role' => 'Manajer Gudang']
        );

        User::updateOrCreate(
            ['email' => 'staff@stockify.local'],
            ['name' => 'Staff', 'password' => Hash::make('password'), 'role' => 'Staff Gudang']
        );
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
     /**
     * Hanya Admin & Manajer yang bisa melihat daftar produk.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']);
    }

    /**
     * Semua role bisa melihat detail produk.
     */
    public function view(User $user, Product $product): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']);
    }

    /**
     * Hanya Admin yang bisa menambah produk baru.
     */
    public function create(User $user): bool
    {
        // Log::info("Policy create dipanggil untuk user {$user->email} dengan role {$user->role}");
        // dd("Policy create dipanggil untuk {$user->email} dengan role {$user->role}");
        return $user->role === 'Admin';
    }

    /**
     * Hanya Admin & Manajer yang boleh edit produk.
     */
    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang']);
        // return in_array(strtolower(trim($user->role)), ['Admin', 'Manajer Gudang']);
    }

    /**
     * Hanya Admin yang bisa menghapus produk.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->role === 'Admin';
    }
}

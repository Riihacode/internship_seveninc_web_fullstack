<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{ 
    /**
     * Semua role boleh lihat daftar kategori.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']);
    }

    /**
     * Semua role boleh lihat detail kategori.
     */
    public function view(User $user, Category $category): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']);
    }

    /**
     * Hanya Admin boleh membuat kategori baru.
     */
    public function create(User $user): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Admin & Manajer boleh update kategori.
     */
    public function update(User $user, Category $category): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang']);
    }

    /**
     * Hanya Admin boleh hapus kategori.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Category $category): bool
    {
        return false;
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}

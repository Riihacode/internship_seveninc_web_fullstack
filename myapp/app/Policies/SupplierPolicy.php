<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
{
    /**
     * Semua role boleh lihat daftar supplier.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']);
    }

    /**
     * Semua role boleh lihat detail supplier.
     */
    public function view(User $user, Supplier $supplier): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']);
    }

    /**
     * Hanya Admin boleh menambah supplier.
     */
    public function create(User $user): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Admin & Manajer boleh update supplier.
     */
    public function update(User $user, Supplier $supplier): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang']);
    }

    /**
     * Hanya Admin boleh hapus supplier.
     */
    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return false;
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return false;
    }
}

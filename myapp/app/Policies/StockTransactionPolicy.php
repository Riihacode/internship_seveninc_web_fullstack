<?php

namespace App\Policies;

use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StockTransaction $stockTransaction): bool
    {
        // return false;
        if ($user->role === 'Staff Gudang') {
            // Staff hanya bisa lihat transaksi yang dia buat sendiri
            return $stockTransaction->user_id === $user->id;
        }

        return in_array($user->role, ['Admin', 'Manajer Gudang']);
    }

    /**
     * Determine whether the user can create models.
     */
    /**
     * Hanya Staff & Manajer & Admin boleh membuat transaksi.
     * (Admin jarang create, tapi kita izinkan untuk fleksibilitas).
     */
    public function create(User $user): bool
    {
        // return false;
        return in_array($user->role, ['Staff Gudang', 'Manajer Gudang', 'Admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    /**
     * Update di sini berarti: approve, reject, atau dispatch.
     * Hanya Manajer & Admin yang boleh.
     */
    // public function update(User $user, StockTransaction $stockTransaction): bool
    // {
    //     // return false;
    //     return in_array($user->role, ['Admin', 'Manajer Gudang']);
    // }
    public function update(User $user, StockTransaction $transaction): bool
    {
        // Staff boleh update status kalau transaksi ditugaskan ke dia
        if ($user->role === 'Staff Gudang' && $transaction->assigned_to === $user->id) {
            return true;
        }

        // Manager & Admin tetap punya akses penuh
        return in_array($user->role, ['Admin', 'Manajer Gudang']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    /**
     * Hanya Admin yang bisa menghapus transaksi
     * (misalnya kalau ada input salah atau fraud).
     */
    public function delete(User $user, StockTransaction $stockTransaction): bool
    {
        // return false;
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StockTransaction $stockTransaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StockTransaction $stockTransaction): bool
    {
        return false;
    }

    public function correct(User $user, StockTransaction $stockTransaction): bool
    {
        // Hanya Manager Gudang & Admin boleh melakukan koreksi
        return in_array($user->role, ['Admin', 'Manajer Gudang']);
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**  
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Manajer Gudang']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $target): bool
    {
        // return in_array($user->role, ['Admin', 'Manajer Gudang']);

        if ($user->role === 'Admin') {
            return true;
        }

        if ($user->role === 'Manajer Gudang') {
            return $target->role === 'Staff Gudang';
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $target): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $target): bool
    {
        return $user->role === 'Admin' && $user->id !== $target->id; // tidak bisa hapus diri sendiri
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $target): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $target): bool
    {
        return false;
    }
}

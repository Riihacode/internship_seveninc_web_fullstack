<?php

namespace App\Policies;

use App\Models\ProductAttribute;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductAttributePolicy
{
     /**
     * Semua aksi hanya boleh dilakukan oleh Admin.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function view(User $user, ProductAttribute $attribute): bool
    {
        return $user->role === 'Admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function update(User $user, ProductAttribute $attribute): bool
    {
        return $user->role === 'Admin';
    }

    public function delete(User $user, ProductAttribute $attribute): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, ProductAttribute $attribute): bool
    {
        return false;
    }

    public function forceDelete(User $user, ProductAttribute $attribute): bool
    {
        return false;
    }
}

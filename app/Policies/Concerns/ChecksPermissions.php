<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksPermissions
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole(['Super Admin', 'Admin'])) {
            return true;
        }

        return null;
    }

    protected function allows(User $user, string $permission): bool
    {
        return $user->can($permission);
    }
}

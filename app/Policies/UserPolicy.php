<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->isSuperAdmin();
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $authUser): bool
    {
        return $authUser->isSuperAdmin();
    }

    /**
     * Determine whether the user can assign a specific role.
     */
    public function assignRole(User $authUser, string $targetRole): bool
    {
        if ($targetRole === 'super_admin') {
            return $authUser->isRootSuperAdmin();
        }

        return $authUser->isSuperAdmin();
    }

    /**
     * Determine whether the user can edit/update the target user.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        // Non-Root Super Admin cannot edit a Super Admin user
        if ($targetUser->isSuperAdmin() && !$authUser->isRootSuperAdmin()) {
            return false;
        }

        return $authUser->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the target user.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        // Cannot delete self
        if ($authUser->id === $targetUser->id) {
            return false;
        }

        // Non-Root Super Admin cannot delete a Super Admin user
        if ($targetUser->isSuperAdmin() && !$authUser->isRootSuperAdmin()) {
            return false;
        }

        return $authUser->isSuperAdmin();
    }

    /**
     * Determine whether the user can reset password for target user.
     */
    public function resetPassword(User $authUser, User $targetUser): bool
    {
        // Non-Root Super Admin cannot reset password of a Super Admin user
        if ($targetUser->isSuperAdmin() && !$authUser->isRootSuperAdmin()) {
            return false;
        }

        return $authUser->isSuperAdmin();
    }
}

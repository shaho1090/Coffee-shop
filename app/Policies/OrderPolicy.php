<?php

namespace App\Policies;

use App\Models\OrderHeader;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\OrderHeader $orderHeader
     * @return bool
     */
    public function update(User $user, OrderHeader $orderHeader): bool
    {
        return $user->isManager() || $user->isOwnerOf($orderHeader);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\OrderHeader $orderHeader
     * @return bool
     */
    public function delete(User $user, OrderHeader $orderHeader): bool
    {
        return $user->isManager() || $user->isOwnerOf($orderHeader);
    }
}

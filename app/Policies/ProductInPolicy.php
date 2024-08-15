<?php

namespace App\Policies;

use App\Models\User;

class ProductInPolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user):bool
    {

        logger($user);
        return $user->role !== 1;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user):bool
    {
        return $user->role !== 1;
    }
}

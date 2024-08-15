<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    public  function create(User $user)
    {
        return $user->role != 1;
    }

    public function delete(User $user)
    {
        return $user->role === 3;
    }
}

<?php

namespace App\Policies;

use App\Models\Clients;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientsPolicy
{

    public function destroy (User $user)
    {

        return $user->role != 1 ?
            Response::allow() :
            Response::deny('No esta autorizado para realizar esta operacion');
    }

}

<?php

namespace App\Policies;

use App\Models\Credito;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CreditoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('credito_ver');
    }

    public function view(User $user, Credito $credito): bool
    {
        return $user->hasPermissionTo('credito_ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('credito_crear');
    }

    public function update(User $user, Credito $credito): bool
    {
        return $user->hasPermissionTo('credito_editar');
    }

    public function delete(User $user, Credito $credito): bool
    {
        return $user->hasPermissionTo('credito_eliminar');
    }
}

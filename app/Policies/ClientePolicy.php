<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('cliente_ver');
    }

    public function view(User $user, Cliente $cliente): bool
    {
        return $user->hasPermissionTo('cliente_ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('cliente_crear');
    }

    public function update(User $user, Cliente $cliente): bool
    {
        return $user->hasPermissionTo('cliente_editar');
    }

    public function delete(User $user, Cliente $cliente): bool
    {
        return $user->hasPermissionTo('cliente_eliminar');
    }
}

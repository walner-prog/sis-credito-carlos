<?php

namespace App\Policies;

use App\Models\Cartera;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarteraPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('cartera_ver');
    }

    public function view(User $user, Cartera $cartera): bool
    {
        return $user->hasPermissionTo('cartera_ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('cartera_crear');
    }

    public function update(User $user, Cartera $cartera): bool
    {
        return $user->hasPermissionTo('cartera_editar');
    }

    public function delete(User $user, Cartera $cartera): bool
    {
        return $user->hasPermissionTo('cartera_eliminar');
    }
}
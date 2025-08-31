<?php

namespace App\Policies;

use App\Models\Abono;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AbonoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('abono_ver');
    }

    public function view(User $user, Abono $abono): bool
    {
        return $user->hasPermissionTo('abono_ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('abono_crear');
    }

    public function update(User $user, Abono $abono): bool
    {
        return $user->hasPermissionTo('abono_editar');
    }

    public function delete(User $user, Abono $abono): bool
    {
        return $user->hasPermissionTo('abono_eliminar');
    }
}

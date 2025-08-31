<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole    = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $cobradorRole = Role::firstOrCreate(['name' => 'Cobrador', 'guard_name' => 'web']);

        // Crear usuario administrador si no existe
        $user = User::firstOrCreate(
            ['email' => 'ca140611@gmail.com'],
            [
                'name'     => 'Carlos Q.',
                'username' => 'carlos', // ← Nuevo campo username
                'password' => Hash::make('12345678'),
            ]
        );

        // Asegurar que el admin tenga solo el rol "Administrador"
        $user->syncRoles([$adminRole]);

        // Ejemplo: asignar rol Cobrador a otro usuario
        // $otherUser = User::firstOrCreate(
        //     ['email' => 'usuario@example.com'],
        //     [
        //         'name'     => 'Usuario Ejemplo',
        //         'username' => 'usuario', 
        //         'password' => Hash::make('12345678'),
        //     ]
        // );
        // $otherUser->syncRoles([$cobradorRole]);
    }
}

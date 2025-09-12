<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Obtener el rol Cobrador
        $cobradorRole = Role::where('name', 'Cobrador')->first();

        // Lista fija de usuarios
        $usuarios = [
            ['name' => 'José Martínez', 'username' => 'jose'],
            ['name' => 'Mario López', 'username' => 'mario'],
            ['name' => 'Ana Gómez', 'username' => 'ana'],
            ['name' => 'Luis Hernández', 'username' => 'luis'],
            ['name' => 'María Fernández', 'username' => 'maria'],
        ];

        foreach ($usuarios as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'username' => $data['username'],
                'email'    => null, // 🚨 Sin email
                'password' => Hash::make('12345678'),
            ]);

            // Asignar rol Cobrador
            if ($cobradorRole) {
                $user->assignRole($cobradorRole);
            }
        }
    }
}

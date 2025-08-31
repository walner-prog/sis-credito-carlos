<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Obtener el rol Cobrador
        $cobradorRole = Role::where('name', 'Cobrador')->first();

        // Crear una instancia de Faker en español
        $faker = Faker::create('es_ES');

        // Generar 5 usuarios
        for ($i = 0; $i < 5; $i++) {
            $name = $faker->name;

            // Crear un username único basado en el nombre
            $username = strtolower(preg_replace('/\s+/', '.', $name)) . $i; // ej: "carlos.alvarez0"

            $user = User::create([
                'name'     => $name,
                'username' => $username, // ← Nuevo campo username
                'email'    => $faker->unique()->safeEmail,
                'password' => Hash::make('12345678'),
            ]);

            // Asignar rol Cobrador
            if ($cobradorRole) {
                $user->assignRole($cobradorRole);
            }
        }
    }
}

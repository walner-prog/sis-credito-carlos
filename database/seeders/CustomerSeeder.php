<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES'); // Configura para generar datos en español

        // Generar 500 clientes
        for ($i = 0; $i < 500; $i++) {
            Cliente::create([
                'nombres' => $faker->firstName,  // Nombres del cliente
                'apellidos' => $faker->lastName,  // Apellidos del cliente
                'identificacion' => $faker->unique()->numerify('####-#######-###'),  // Identificación
                'telefono' => $faker->phoneNumber,  // Teléfono
                'direccion' => $this->generateAddress(),  // Dirección
                'km_referencia' => $faker->optional()->word, // KM referencia opcional
                'estado' => 'activo',  // Estado por defecto
            ]);
        }
    }

    private function generateAddress()
    {
        // Direcciones ficticias de Nicaragua, se pueden personalizar
        $streets = ['Calle Principal', 'Avenida Central', 'Calle Los Robles', 'Calle El Progreso', 'Avenida Bolívar'];
        $number = rand(1, 150);  // Número de casa aleatorio
        $street = $streets[array_rand($streets)];

        return "$street #$number";
    }
}

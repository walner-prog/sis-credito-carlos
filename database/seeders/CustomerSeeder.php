<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');

        for ($i = 0; $i < 500; $i++) {
            Cliente::create([
                'nombres'       => $faker->firstName,
                'apellidos'     => $faker->lastName,
                'identificacion'=> $faker->unique()->numerify('##########'), // 10 dígitos únicos
                'telefono'      => $faker->optional()->numerify('+505########'),
                'direccion'     => $faker->optional()->address,
                'km_referencia' => $faker->optional()->randomElement([
                    'KM 5 Carretera Vieja a León',
                    'KM 10 Carretera Norte',
                    'KM 12 Masaya',
                    'KM 20 León',
                    'KM 15 Granada'
                ]),
                'cartera_id'    => null, // se puede asignar después si es necesario
                'estado'        => $faker->randomElement(['activo']),
            ]);
        }
    }
}

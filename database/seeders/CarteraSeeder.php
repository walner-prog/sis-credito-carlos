<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cartera;

class CarteraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cartera::create([
            'nombre' => 'Cartera 1',
            'user_id' => 1, // ID del usuario asignado
            'estado' => 'activa', // Estado de la cartera
        ]);

        Cartera::create([
            'nombre' => 'Cartera 2',
            'user_id' => 1, // ID del usuario asignado
            'estado' => 'activa', // Estado de la cartera
        ]);
    }
}

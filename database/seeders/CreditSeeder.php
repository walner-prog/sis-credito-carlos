<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Credito;
use App\Models\Cliente;
use App\Models\Cartera;
use Faker\Factory as Faker;

class CreditSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');
        
        // Obtener clientes desde el cliente 20 en adelante (ya que los primeros 19 ya tienen crédito)
        $clientes = Cliente::skip(19)->take(500)->get(); // Obtiene clientes del 20 al 500

        // Obtener una cartera disponible para asignar el crédito
        $cartera = Cartera::first(); // Suponiendo que ya tienes carteras creadas

        foreach ($clientes as $cliente) {
            // Crear crédito para cada cliente
            Credito::create([
                'cliente_id' => $cliente->id,
                'cartera_id' => $cartera->id,
                'monto_total' => $faker->randomFloat(2, 1000, 10000), // Monto aleatorio entre 1000 y 10000
                'saldo_pendiente' => $faker->randomFloat(2, 1000, 10000), // Saldo pendiente aleatorio
                'tasa_interes' => 20.00, // Tasa de interés predeterminada 20%
                'plazo' => rand(6, 24), // Plazo aleatorio entre 6 y 24 meses
                'unidad_plazo' => 'meses', // Unidad de plazo: meses
                'estado' => 'activo', // Estado por defecto
                'fecha_inicio' => now(), // Fecha de inicio (hoy)
                'fecha_vencimiento' => now()->addMonths(rand(6, 24)), // Fecha de vencimiento aleatoria
            ]);
        }
    }
}

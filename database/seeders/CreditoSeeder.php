<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreditoSeeder extends Seeder
{
    public function run()
    {
        //
        // Obtener clientes activos
        $clientes = DB::table('clientes')->where('estado', 'activo')->get();

        // Obtener carteras ya creadas
        //  $carteras = DB::table('carteras')->pluck('id')->toArray();

        foreach ($clientes as $cliente) {
            // Monto exacto (múltiplos de 1000, entre 1000 y 5000)
            $montosPosibles = [1000, 2000, 3000, 4000, 5000];
            $monto_total = $montosPosibles[array_rand($montosPosibles)];

            // Plazo en cuotas (ejemplo: 5 cuotas de 24 días cada una)
            $num_cuotas = rand(3, 6); // número de cuotas (ajustable según cliente)
            $plazo = $num_cuotas * 24;
            $unidad_plazo = 'dias';

            $fecha_inicio = Carbon::now();
            $fecha_vencimiento = $fecha_inicio->copy()->addDays($plazo);




            // Insertar el crédito
            $credito_id = DB::table('creditos')->insertGetId([
                'cliente_id'      => $cliente->id,
                'monto_total'     => $monto_total,
                'saldo_pendiente' => $monto_total,
                'tasa_interes'    => 20.00,
                'plazo'           => $plazo,
                'unidad_plazo'    => $unidad_plazo,
                'estado'          => 'activo',
                'fecha_inicio'    => $fecha_inicio,
                'fecha_vencimiento' => $fecha_vencimiento,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 🔹 Generar abonos cada 24 días (sin domingos)
            $fecha_abono = $fecha_inicio->copy();
            $monto_cuota = $monto_total / $num_cuotas;

            for ($i = 1; $i <= $num_cuotas; $i++) {
                // Avanzar 24 días
                $fecha_abono->addDays(24);

                // Si cae en domingo, mover al lunes
                if ($fecha_abono->isSunday()) {
                    $fecha_abono->addDay();
                }

                DB::table('abonos')->insert([
                    'credito_id' => $credito_id,
                    'numero_cuota' => $i,
                    'monto_abono' => $monto_cuota,
                    'fecha_abono' => $fecha_abono,
                    'estado' => 'pendiente',
                    'comentarios' => 'Abono automático generado',
                    'user_id' => 1, // Asignar al usuario admin (ID 1)
                    'cliente_id' => $cliente->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

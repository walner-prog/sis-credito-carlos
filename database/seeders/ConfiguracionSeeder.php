<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionSeeder extends Seeder
{
    public function run()
    {
        DB::table('configuraciones')->insert([
            'logo'                     => null, // Si más adelante subes a imgbb, actualizas aquí
            'logo_delete_url'          => null,
            'nombre_sistema'           => 'CG Sistema',
            'ruc'                      => 'J031234567890',
            'direccion'                => 'Chinandega, Nicaragua',
            'telefono'                 => '+505 8888-9999',
            'propietario'              => 'Carlos Q',
            'tasa_interes_global'      => 20.00,
            'permite_multicredito'     => true,
            'cuota_frecuencia_default' => 'diaria',
            'unidad_plazo_default'     => 'dias',
            'dias_gracia_primera_cuota'=> 1,
            'dias_no_cobrables'        => json_encode(['domingo']),
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);
    }
}

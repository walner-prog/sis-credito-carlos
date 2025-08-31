<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Cartera;




class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientes = [
            ['nombres' => 'Carlos', 'apellidos' => 'Gutiérrez', 'identificacion' => '1214-5701-0175', 'telefono' => '8821-7654', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 10 Carretera a León', 'estado' => 'activo'],
            ['nombres' => 'María', 'apellidos' => 'López', 'identificacion' => '3215-4321-0865', 'telefono' => '8765-4321', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 5 Carretera Vieja', 'estado' => 'activo'],
            ['nombres' => 'José', 'apellidos' => 'Pérez', 'identificacion' => '1423-5510-2234', 'telefono' => '8923-2345', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 3 Carretera Norte', 'estado' => 'activo'],
            ['nombres' => 'Ana', 'apellidos' => 'Martínez', 'identificacion' => '5432-8765-0012', 'telefono' => '8321-6543', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 12 Carretera a Chinandega', 'estado' => 'activo'],
            ['nombres' => 'Luis', 'apellidos' => 'Rodríguez', 'identificacion' => '9876-5432-0178', 'telefono' => '8632-6578', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 7 Carretera León', 'estado' => 'activo'],
            ['nombres' => 'Laura', 'apellidos' => 'Sánchez', 'identificacion' => '6543-1325-0901', 'telefono' => '8723-8436', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 8 Carretera Nueva', 'estado' => 'activo'],

            
            ['nombres' => 'Pedro', 'apellidos' => 'González', 'identificacion' => '2345-5678-0146', 'telefono' => '8756-2345', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 4 Carretera Sur', 'estado' => 'activo'],
            ['nombres' => 'Sandra', 'apellidos' => 'Fernández', 'identificacion' => '6732-1254-3209', 'telefono' => '8567-9482', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 6 Carretera a El Viejo', 'estado' => 'activo'],
            ['nombres' => 'Ricardo', 'apellidos' => 'Díaz', 'identificacion' => '3421-7889-0576', 'telefono' => '8347-6352', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 2 Carretera Norte', 'estado' => 'activo'],
            ['nombres' => 'Carmen', 'apellidos' => 'Ramírez', 'identificacion' => '9753-1923-0065', 'telefono' => '8563-7458', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 10 Carretera a El Viejo', 'estado' => 'activo'],
            ['nombres' => 'Juan', 'apellidos' => 'Vargas', 'identificacion' => '4312-9982-1147', 'telefono' => '8456-9487', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 5 Carretera León', 'estado' => 'activo'],
            ['nombres' => 'Patricia', 'apellidos' => 'Torres', 'identificacion' => '5612-8764-1289', 'telefono' => '8123-7635', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 9 Carretera Vieja', 'estado' => 'activo'],
            ['nombres' => 'Antonio', 'apellidos' => 'Morales', 'identificacion' => '1245-6789-2210', 'telefono' => '8775-6742', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 8 Carretera Norte', 'estado' => 'activo'],
            ['nombres' => 'Isabel', 'apellidos' => 'Mendoza', 'identificacion' => '2365-9834-0102', 'telefono' => '8392-3654', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 4 Carretera a Chinandega', 'estado' => 'activo'],
            ['nombres' => 'Jorge', 'apellidos' => 'Hernández', 'identificacion' => '6473-8490-0358', 'telefono' => '8745-9021', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 3 Carretera Sur', 'estado' => 'activo'],
            ['nombres' => 'Luisa', 'apellidos' => 'Castro', 'identificacion' => '1847-5923-0804', 'telefono' => '8423-6781', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 7 Carretera Nueva', 'estado' => 'activo'],
            ['nombres' => 'Rafael', 'apellidos' => 'Paniagua', 'identificacion' => '7452-3921-0431', 'telefono' => '8234-5639', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 9 Carretera a Chinandega', 'estado' => 'activo'],
            ['nombres' => 'Marta', 'apellidos' => 'García', 'identificacion' => '2945-8764-2093', 'telefono' => '8123-4691', 'direccion' => 'Chinandega, Nicaragua', 'km_referencia' => 'KM 5 Carretera Vieja', 'estado' => 'activo'],
            ['nombres' => 'Carlos', 'apellidos' => 'Cordero', 'identificacion' => '3210-5498-0320', 'telefono' => '8327-7650', 'direccion' => 'León, Nicaragua', 'km_referencia' => 'KM 6 Carretera Norte', 'estado' => 'activo']

            
        ];

           $carteras = Cartera::all();
        foreach ($clientes as $cliente) {

            // Asignar una cartera aleatoria a cada cliente
            $cartera = $carteras->random();

           DB::table('clientes')->updateOrInsert(
    ['identificacion' => $cliente['identificacion']], // clave única
    [
        'nombres'       => $cliente['nombres'],
        'apellidos'     => $cliente['apellidos'],
        'telefono'      => $cliente['telefono'],
        'direccion'     => $cliente['direccion'],
        'km_referencia' => $cliente['km_referencia'],
        'cartera_id'    => $cartera->id,
        'estado'        => $cliente['estado'],
        'updated_at'    => now(),
    ]
);

        }
    }
}

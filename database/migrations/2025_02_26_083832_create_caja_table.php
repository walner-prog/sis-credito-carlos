<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajaTable extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->id(); // id INT (PK)
            $table->decimal('saldo_actual', 10, 2); // saldo_actual DECIMAL(10,2)
            $table->timestamp('fecha_actualizacion')->useCurrent(); // fecha_actualizacion TIMESTAMP (Última actualización)
            $table->timestamps(); // created_at y updated_at TIMESTAMP

            // Agregar el campo para el eliminado lógico
            $table->softDeletes(); // Elimina los registros lógicamente
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caja');
    }
}


/*

// Método para actualizar el saldo de caja
public function actualizarSaldoCaja($monto, $tipoMovimiento)
{
    $caja = \App\Models\Caja::latest()->first(); // Obtener el último registro de caja
    $nuevoSaldo = $caja->saldo_actual;

    // Si es un ingreso, se suma al saldo actual
    if ($tipoMovimiento === 'ingreso') {
        $nuevoSaldo += $monto;
    }
    // Si es un egreso, se resta del saldo actual
    elseif ($tipoMovimiento === 'egreso') {
        $nuevoSaldo -= $monto;
    }

    // Actualizar el saldo en la tabla 'caja'
    $caja->update([
        'saldo_actual' => $nuevoSaldo,
        'fecha_actualizacion' => now(), // Actualizar la fecha de la última actualización
    ]);
}



*/
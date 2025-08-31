<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonosTable extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos', function (Blueprint $table) {
            $table->id(); // id INT (PK)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Usuario que registro el cobro');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->unsignedBigInteger('credito_id'); // credito_id INT (FK)
             $table->integer('numero_cuota'); 
            $table->decimal('monto_abono', 10, 2); // monto_abono DECIMAL(10,2)
            $table->date('fecha_abono'); // fecha_abono DATE
             $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');
            $table->text('comentarios')->nullable()->comment('Comentarios adicionales sobre el abono');
            $table->timestamps(); // created_at y updated_at TIMESTAMP

            // Agregar el campo para el eliminado lógico
            $table->softDeletes(); // Elimina los registros lógicamente

            
            // Relación con la tabla creditos
            $table->foreign('credito_id')->references('id')->on('creditos')->onDelete('cascade');
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abonos');
    }
}


/*

// Obtener abonos del día
$abonosHoy = \App\Models\Abono::whereDate('fecha_abono', today())->get();

// Clientes que pagaron hoy
$clientesQuePagaronHoy = $abonosHoy->pluck('credito_id');

// Obtener créditos que no han pagado hoy
$creditosNoPagadosHoy = \App\Models\Credito::whereNotIn('id', $clientesQuePagaronHoy)->get();


*/
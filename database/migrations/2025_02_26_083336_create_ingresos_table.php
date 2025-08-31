<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresosTable extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id(); // id INT (PK)
           // $table->unsignedBigInteger('cliente_id'); // cliente_id INT (FK)
           // $table->unsignedBigInteger('credito_id')->nullable(); // credito_id INT (FK, NULL)
            $table->decimal('monto', 10, 2); // monto DECIMAL(10,2)
            $table->string('concepto', 255); // concepto VARCHAR(255)
            $table->date('fecha'); // fecha DATE
           // $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta']); // metodo_pago ENUM
            $table->timestamps(); // created_at y updated_at TIMESTAMP

            // Agregar el campo para el eliminado lógico
            $table->softDeletes(); // Elimina los registros lógicamente

            // Relación con la tabla clientes
           // $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');

            // Relación con la tabla creditos (si aplica)
           // $table->foreign('credito_id')->references('id')->on('creditos')->onDelete('set null');
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresos');
    }
}

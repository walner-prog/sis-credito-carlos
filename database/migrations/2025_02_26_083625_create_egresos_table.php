<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEgresosTable extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egresos', function (Blueprint $table) {
            $table->id(); // id INT (PK)
            $table->decimal('monto', 10, 2); // monto DECIMAL(10,2)
            $table->string('concepto', 255); // concepto VARCHAR(255)
            $table->date('fecha'); // fecha DATE
            $table->string('proveedor', 255)->nullable(); // proveedor VARCHAR(255) (nullable)
           // $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta'])->nullable(); // metodo_pago ENUM (nullable)
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
        Schema::dropIfExists('egresos');
    }
}

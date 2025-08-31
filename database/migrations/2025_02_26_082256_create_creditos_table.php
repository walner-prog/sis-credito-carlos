<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditosTable extends Migration
{
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Usuario que otorga el crédito');

            $table->unsignedBigInteger('cliente_id');

            // NUEVO: monto solicitado y monto con intereses
            $table->decimal('monto_solicitado', 10, 2)->comment('Monto que pidió el cliente');
            $table->decimal('monto_total', 10, 2)->comment('Monto con intereses incluidos');

            // saldo pendiente siempre arranca = monto_total
            $table->decimal('saldo_pendiente', 10, 2);

            $table->decimal('tasa_interes', 5, 2)->default(20.00);

            $table->integer('plazo')->comment('Plazo en días o meses');
            $table->enum('unidad_plazo', ['dias', 'meses'])->default('dias');
            $table->enum('cuota_frecuencia', ['diaria','semanal','quincenal','mensual'])->default('diaria');


            // NUEVO: cuota diaria
            $table->decimal('cuota', 10, 2)->comment('Abono diario según unidad_plazo');
            $table->integer('num_cuotas')->default(0)->comment('Número total de cuotas del crédito');

            $table->enum('estado', ['activo', 'cancelado', 'moroso'])->default('activo');

            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cliente_id')
                ->references('id')
                ->on('clientes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('creditos');
    }
}

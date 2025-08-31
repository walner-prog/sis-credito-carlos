<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditoCuotasTable extends Migration
{
    public function up()
    {
        Schema::create('credito_cuotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('credito_id')
                ->constrained('creditos')
                ->onDelete('cascade');

            $table->integer('numero_cuota')->comment('Número de la cuota dentro del crédito');
            $table->decimal('monto', 10, 2)->comment('Monto de la cuota');
            $table->date('fecha_vencimiento')->comment('Fecha de vencimiento de la cuota');
            $table->enum('estado', ['pendiente', 'pagada', 'atrasada','parcial'])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('credito_cuotas');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            // Identidad de la empresa
            $table->string('logo')->nullable();            // ruta del logo (storage/public)
            $table->string('nombre_sistema')->nullable();
            $table->string('ruc')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('propietario')->nullable();

            // Parámetros de crédito globales
            $table->decimal('tasa_interes_global', 8, 2)->default(20.00);
            $table->boolean('permite_multicredito')->default(false);
            $table->enum('cuota_frecuencia_default', ['diaria','semanal','quincenal','mensual'])->default('diaria');
            $table->enum('unidad_plazo_default', ['dias','meses'])->default('dias');
            $table->integer('dias_gracia_primera_cuota')->default(1); // número de días después de otorgar el crédito
            $table->json('dias_no_cobrables')->nullable(); // almacenar días no cobrables como ["domingo"]
          

            $table->timestamps();
            $table->softDeletes(); // si quieres consistencia con Carteras
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};

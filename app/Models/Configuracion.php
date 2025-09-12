<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuracion extends Model
{
    use SoftDeletes;

    protected $table = 'configuraciones';

    protected $fillable = [
        'logo',
        'nombre_sistema',
        'ruc',
        'direccion',
        'telefono',
        'propietario',
        'tasa_interes_global',
        'permite_multicredito',
        'cuota_frecuencia_default',
        'unidad_plazo_default',
        'dias_gracia_primera_cuota',
        'dias_no_cobrables',
        'logo_delete_url',
    ];

    protected $casts = [
        'permite_multicredito'      => 'boolean',
        'tasa_interes_global'       => 'decimal:2',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditoCuota extends Model
{
    protected $fillable = [
        'credito_id',
        'numero_cuota',
        'monto',
        'fecha_vencimiento',
        'estado', // pendiente, pagada, atrasada
    ];

    // Relación con el crédito
    public function credito()
    {
        return $this->belongsTo(Credito::class);
    }
}

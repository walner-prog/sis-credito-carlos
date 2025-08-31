<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Credito;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;
 
use App\Models\User;

class Abono extends Model
{
    use HasFactory, SoftDeletes; // Habilita SoftDeletes

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'user_id',
        'credito_id',
        'cliente_id',
        'monto_abono',
        'fecha_abono',
        'comentarios',
        'numero_cuota'
    ];

    // Relación con el Usuario que registró el abono
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el Crédito al que pertenece el abono
    public function credito()
    {
        return $this->belongsTo(Credito::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

  
}



/*


  /**
     * Event que ocurre al guardar un abono.
     * Actualiza el saldo pendiente y cambia el estado del crédito si es necesario.
     *//*
    protected static function booted()
    {
       
        static::created(function ($abono) {
            $credito = $abono->credito;
    
            // Validar que el monto del abono sea positivo
            if ($abono->monto_abono <= 0) {
                throw ValidationException::withMessages(['error' => 'El monto del abono debe ser un valor positivo.']);
            }
    
            // Validar que la fecha del abono no sea en el futuro
            if ($abono->fecha_abono > now()) {
                throw ValidationException::withMessages(['error' => 'La fecha de abono no puede ser en el futuro.']);
            }
    
            // Validar que el crédito esté activo
            if ($credito->estado !== 'activo') {
                throw ValidationException::withMessages(['error' => 'El crédito no está activo. No se puede realizar el abono.']);
            }
    
            // Validar si el crédito ya está completamente pagado
            if ($credito->saldo_pendiente <= 0) {
                throw ValidationException::withMessages(['error' => 'El crédito ya ha sido completamente pagado.']);
            }
    
            // Calcular el nuevo saldo después del abono
            $nuevoSaldo = $credito->saldo_pendiente - $abono->monto_abono;
    
            // Validar que el abono no sea mayor al saldo pendiente
            if ($nuevoSaldo < 0) {
                throw ValidationException::withMessages(['error' => 'El abono no puede ser mayor al saldo pendiente.']);
            }
    
            // Actualizar el saldo pendiente del crédito
            $credito->saldo_pendiente = $nuevoSaldo;
    
            // Si el saldo llega a 0, marcar el crédito como cancelado
            if ($nuevoSaldo == 0) {
                $credito->estado = 'cancelado';
            }
    
            // Guardar los cambios en el crédito
            $credito->save();

           
        
    
    });
    
      
    
        
         // Evento para eliminar un abono
        static::deleting(function ($abono) {
            $credito = $abono->credito;
    
            // Recalcular el saldo pendiente después de eliminar el abono
            $nuevoSaldo = $credito->saldo_pendiente + $abono->monto_abono;
    
            // Validar que el saldo pendiente no sea negativo
            if ($nuevoSaldo < 0) {
                throw ValidationException::withMessages(['error' => 'El saldo pendiente no puede ser menor a 0 después de eliminar el abono.']);
            }
    
            // Actualizar el saldo pendiente del crédito
            $credito->saldo_pendiente = $nuevoSaldo;
    
            // Si el saldo pendiente es mayor que 0, aseguramos que el crédito sea "activo"
            if ($nuevoSaldo > 0) {
                $credito->estado = 'activo';
            }
    
            // Si el saldo llega a 0, marcar el crédito como "cancelado"
            if ($nuevoSaldo == 0) {
                $credito->estado = 'cancelado';
            }
    
            // Guardar los cambios en el crédito
            $credito->save();
        });
    
        // Evento para actualizar un abono
        static::updating(function ($abono) {
            // Obtener el crédito relacionado con el abono
            $credito = $abono->credito;
    
            // Obtener el monto del abono anterior antes de la actualización
            $montoAbonoAnterior = $abono->getOriginal('monto_abono');
    
            // Calcular la diferencia entre el monto nuevo y el monto anterior
            $diferencia = $abono->monto_abono - $montoAbonoAnterior;
    
            // Validar que la diferencia no sea negativa
            if ($diferencia < 0) {
                // Si la diferencia es negativa (estamos reduciendo el abono), se debe incrementar el saldo pendiente
                $nuevoSaldo = $credito->saldo_pendiente + abs($diferencia);
            } else {
                // Si la diferencia es positiva (estamos aumentando el abono), se debe reducir el saldo pendiente
                $nuevoSaldo = $credito->saldo_pendiente - $diferencia;
            }
    
            // Validar que el abono no sea mayor al saldo pendiente
            if ($nuevoSaldo < 0) {
                throw ValidationException::withMessages(['error' => 'El abono no puede ser mayor al saldo pendiente.']);
            }
    
            // Actualizar el saldo pendiente del crédito
            $credito->saldo_pendiente = $nuevoSaldo;
    
            // Si el saldo llega a 0, marcar el crédito como cancelado
            if ($nuevoSaldo == 0) {
                $credito->estado = 'cancelado';
            } elseif ($nuevoSaldo > 0 && $credito->estado === 'cancelado') {
                // Si el saldo vuelve a ser mayor a 0, el crédito debe ser "activo"
                $credito->estado = 'activo';
            }
    
            // Guardar los cambios en el crédito
            $credito->save();
        });
    }
    

*/
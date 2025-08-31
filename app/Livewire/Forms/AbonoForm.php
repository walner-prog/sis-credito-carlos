<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Abono;
use App\Models\Credito;
use Illuminate\Support\Facades\DB;

class AbonoForm extends Form
{
    public $credito_id = null;
    public $monto_abono = '';
    public $fecha_abono;
    public $comentarios = '';

    public ?Abono $abono = null;

    public function rules(): array
    {
        $credito = Credito::find($this->credito_id);
        $maxMonto = $credito ? $credito->saldo_pendiente : 0;

        return [
            'credito_id'   => ['required', 'exists:creditos,id'],
            'monto_abono'  => ['required', 'numeric', 'min:0.01', 'max:' . $maxMonto],
            'fecha_abono'  => ['required', 'date'],
            'comentarios'  => ['nullable', 'string', 'max:255'],
        ];
    }

      public function messages(): array
{
    return [
        'monto_abono.required' => '⚠️ Debe ingresar un monto antes de registrar el abono.',
        'monto_abono.numeric'  => '⚠️ El monto debe ser un número válido.',
        'monto_abono.min'      => '⚠️ El monto debe ser mayor a 0.',
        'monto_abono.max'      => '⚠️ El monto no puede ser mayor al saldo pendiente.',
    ];
}

    public function setAbono(Abono $abono): void
    {
        $this->abono = $abono;
        $this->credito_id = $abono->credito_id;
        $this->monto_abono = $abono->monto_abono;
        $this->fecha_abono = $abono->fecha_abono;
        $this->comentarios = $abono->comentarios;
    }

    /**
     * Marca automáticamente como 'atrasada' las cuotas vencidas que aún estén pendientes.
     */
    private function actualizarCuotasAtrasadas(Credito $credito): void
    {
        $hoy = now()->startOfDay();

        foreach ($credito->cuotas()->where('estado', 'pendiente')->get() as $cuota) {
            if (\Carbon\Carbon::parse($cuota->fecha_vencimiento)->lt($hoy)) {
                $cuota->estado = 'atrasada';
                $cuota->save();
            }
        }
    }

    /**
     * Recalcula saldo pendiente y estado del crédito en base a sus cuotas.
     */
    private function recalcularCredito(Credito $credito): void
    {
        $credito->saldo_pendiente = $credito
            ->cuotas()
            ->whereIn('estado', ['pendiente','parcial','atrasada'])
            ->sum('monto');

        $credito->estado = $credito->saldo_pendiente <= 0 ? 'cancelado' : 'activo';
        $credito->save();
    }

    /**
     * Crear un abono y aplicar a las cuotas pendientes.
     */
    public function store(): void
    {

        // Si el usuario dejó vacío el input, forzar a null en lugar de 0
    if (trim($this->monto_abono) === '' || $this->monto_abono === null) {
        session()->flash('error', '⚠️ Debe ingresar un monto válido.');
        return;
    }
    
        $this->validate();

         if ($this->monto_abono <= 0) {
        session()->flash('error', '⚠️ Debe ingresar un monto mayor a 0 antes de registrar el abono.');
        return;
    }


        DB::transaction(function () {
            $credito = Credito::with('cuotas')->findOrFail($this->credito_id);

            // Primero, actualizar cuotas atrasadas
            $this->actualizarCuotasAtrasadas($credito);

            $cliente_id = $credito->cliente_id;
            $montoRestante = $this->monto_abono;

            // Buscar la próxima cuota a afectar (incluyendo atrasadas)
            $proximaCuota = $credito->cuotas()
                ->whereIn('estado', ['pendiente','atrasada'])
                ->orderBy('numero_cuota')
                ->first();

            $numeroCuota = $proximaCuota ? $proximaCuota->numero_cuota : 1;

            // Crear abono
            $abono = Abono::create([
                'credito_id'   => $this->credito_id,
                'cliente_id'   => $cliente_id,
                'user_id'      => auth()->id(),
                'monto_abono'  => $this->monto_abono,
                'fecha_abono'  => $this->fecha_abono,
                'comentarios'  => $this->comentarios,
                'numero_cuota' => $numeroCuota,
            ]);

            // Aplicar abono a cuotas (pendientes o atrasadas)
            foreach ($credito->cuotas()->whereIn('estado',['pendiente','atrasada'])->orderBy('numero_cuota')->get() as $cuota) {
                if ($montoRestante <= 0) break;

                if ($montoRestante >= $cuota->monto) {
                    $montoRestante -= $cuota->monto;
                    $cuota->estado = 'pagada';
                    $cuota->save();
                } else {
                    $cuota->monto -= $montoRestante;
                    $montoRestante = 0;
                    $cuota->estado = 'parcial';
                    $cuota->save();
                }
            }

            // Recalcular saldo y estado
            $this->recalcularCredito($credito);
        });
    }

    /**
     * Actualizar un abono existente y reajustar cuotas.
     */
    public function update(): void
    {
        if (!$this->abono) {
            throw new \RuntimeException('No hay abono para actualizar.');
        }

        $this->validate();

        DB::transaction(function () {
            $abonoOriginal = Abono::findOrFail($this->abono->id);
            $credito = $abonoOriginal->credito;

            // Actualizar cuotas atrasadas primero
            $this->actualizarCuotasAtrasadas($credito);

            $montoOriginal = $abonoOriginal->monto_abono;

            // Revertir cuotas afectadas por el abono original
            foreach ($credito->cuotas()->where('numero_cuota', '>=', $abonoOriginal->numero_cuota)->orderBy('numero_cuota')->get() as $cuota) {
                if ($cuota->estado == 'pagada') {
                    $cuota->estado = 'pendiente';
                    $cuota->save();
                } elseif ($cuota->estado == 'parcial') {
                    $cuota->monto += $montoOriginal;
                    $cuota->estado = 'pendiente';
                    $cuota->save();
                }
            }

            // Actualizar datos del abono
            $abonoOriginal->update([
                'monto_abono' => $this->monto_abono,
                'fecha_abono' => $this->fecha_abono,
                'comentarios' => $this->comentarios,
            ]);

            // Aplicar el nuevo monto del abono
            $montoRestante = $this->monto_abono;

            foreach ($credito->cuotas()->whereIn('estado',['pendiente','atrasada'])->orderBy('numero_cuota')->get() as $cuota) {
                if ($montoRestante <= 0) break;

                if ($montoRestante >= $cuota->monto) {
                    $montoRestante -= $cuota->monto;
                    $cuota->estado = 'pagada';
                    $cuota->save();
                } else {
                    $cuota->monto -= $montoRestante;
                    $montoRestante = 0;
                    $cuota->estado = 'parcial';
                    $cuota->save();
                }
            }

            // Recalcular saldo y estado
            $this->recalcularCredito($credito);
        });
    }

    /**
     * Eliminar un abono y revertir las cuotas.
     */
    public function deleteAbono(Abono $abono): void
    {
        DB::transaction(function () use ($abono) {
            $credito = $abono->credito;

            // Primero, actualizar cuotas atrasadas
            $this->actualizarCuotasAtrasadas($credito);

            $montoAbono = $abono->monto_abono;

            foreach ($credito->cuotas()->where('numero_cuota', '>=', $abono->numero_cuota)->orderBy('numero_cuota')->get() as $cuota) {
                if ($cuota->estado == 'pagada') {
                    $cuota->estado = 'pendiente';
                    $cuota->save();
                } elseif ($cuota->estado == 'parcial') {
                    $cuota->monto += $montoAbono;
                    $cuota->estado = 'pendiente';
                    $cuota->save();
                }
            }

            // Eliminar el abono
            $abono->delete();

            // Recalcular saldo y estado
            $this->recalcularCredito($credito);
        });
    }
}

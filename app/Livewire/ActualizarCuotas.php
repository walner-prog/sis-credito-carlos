<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Credito;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActualizarCuotas extends Component
{
    public $mensaje = '';

  public function actualizarCuotas(): ?string
{
    $hoy = now()->startOfDay();
    $actualizadas = 0;

    DB::transaction(function () use ($hoy, &$actualizadas) {
        $creditos = Credito::with('cuotas')
            ->whereHas('cuotas', function ($q) {
                $q->where('estado', 'pendiente');
            })->get();

        foreach ($creditos as $credito) {
            foreach ($credito->cuotas as $cuota) {
                if ($cuota->estado === 'pendiente' && Carbon::parse($cuota->fecha_vencimiento)->lt($hoy)) {
                    $cuota->estado = 'atrasada';
                    $cuota->save();
                    $actualizadas++;
                }
            }
        }
    });

    // Solo devolver mensaje si se actualizaron cuotas
    return $actualizadas > 0 
        ? "✅ Se actualizaron {$actualizadas} cuota(s) a estado atrasada."
        : null;
}



    public function render()
    {
        return view('livewire.actualizar-cuotas');
    }
}
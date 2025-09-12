<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Credito;
use App\Models\Abono;
use Carbon\Carbon;

class GraficasDashboard extends Component
{
    public $graficoPagos = [];
    public $estadoCreditos = [];

    public function mount()
    {
        $this->generarDatosGrafico();
        $this->generarDatosCreditos();
    }

    private function generarDatosGrafico()
    {
        $dias = collect();
        $hoy = Carbon::today();

        // Créditos activos con cuota diaria
        $creditosActivos = Credito::where('estado', 'activo')
            ->where('cuota_frecuencia', 'diaria')
            ->count();

        for ($i = 6; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subDays($i);

            $abonosPagados = Abono::whereDate('fecha_abono', $fecha)
                ->where('estado', 'pagado')
                ->count();

            $dias->push([
                'fecha'     => $fecha->format('d/m'),
                'pagados'   => $abonosPagados,
                'esperados' => $creditosActivos,
            ]);
        }

        $this->graficoPagos = $dias;
    }

    private function generarDatosCreditos()
    {
        $activos = Credito::where('estado', 'activo')->count();
        $pagados = Credito::where('estado', 'pagado')->count();
        $mora    = Credito::where('estado', 'moroso')->count();

        $this->estadoCreditos = [
            'Activos' => $activos,
            'Pagados' => $pagados,
            'En mora' => $mora,
        ];
    }

    public function render()
    {
        return view('livewire.graficas-dashboard');
    }
}

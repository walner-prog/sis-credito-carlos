<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use Carbon\Carbon;

class GraficosClientes extends Component
{
    public $graficoClientes = [];

    public function mount()
    {
        $this->generarGraficoClientes();
    }

    private function generarGraficoClientes()
    {
        // Agrupar clientes creados por mes en el año actual
        $clientes = Cliente::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Etiquetas en español (Ene, Feb, Mar...)
        $labels = $clientes->keys()->map(fn($m) =>
            Carbon::create()->month($m)->locale('es')->translatedFormat('M')
        );

        $data   = $clientes->values();

        $this->graficoClientes = [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    public function render()
    {
        return view('livewire.graficos-clientes');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;
use App\Models\Cartera;
use App\Models\Credito;
use App\Models\Abono;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class Dashboard extends Component
{
    use WithPagination;

    // Indicadores principales
    public $creditosCount;       // Créditos activos
    public $abonosTotal;         // Total de abonos pagados
    public $creditosMoraTotal;   // Créditos en mora

    // Indicadores secundarios
    public $clientesCount;
    public $carterasCount;
    public $abonosPendientes;
    public $usuariosCount;
    public $rolesCount;

    // Otros datos para widgets/tablas
    public $proximoCredito;
    public $creditosPendientes;
    public $abonosRecientes;
    public $creditosPorVencer;
    public $creditosMora;

    // Modal control
    public $openModalMora = false;
    public $openModalVencer = false;

    public function mount()
    {
        // Indicadores principales
        $this->creditosCount      = Credito::where('estado', 'activo')->count();
        $this->abonosTotal        = Abono::where('estado', 'pagado')->sum('monto_abono');
        $this->creditosMoraTotal  = Credito::where('estado', 'moroso')->count();

        // Secundarios
        $this->clientesCount      = Cliente::where('estado', 'activo')->count();
        $this->carterasCount      = Cartera::count();
        $this->abonosPendientes   = Abono::where('estado', 'pendiente')->sum('monto_abono');
        $this->usuariosCount      = User::count();
        $this->rolesCount         = Role::count();

        // Próximo crédito
        $this->proximoCredito = Credito::where('estado', 'activo')
            ->orderBy('fecha_vencimiento', 'asc')
            ->with('cliente')
            ->first();

        // Créditos pendientes
        $this->creditosPendientes = Credito::where('estado', 'activo')
            ->with('cliente')
            ->take(5)
            ->get();

        // Últimos abonos
        $this->abonosRecientes = Abono::with('cliente')
            ->latest()
            ->take(5)
            ->get();

      // Créditos por vencer (2 días próximos, solo 5)
$this->creditosPorVencer = Credito::where('estado', 'activo')
    ->whereBetween('fecha_vencimiento', [Carbon::today(), Carbon::today()->addDays(2)])
    ->with('cliente')
    ->take(5)
    ->get();

        // Créditos en mora
        $this->creditosMora = Credito::where('estado', 'moroso')
            ->with('cliente')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'creditosMoraPaginated' => Credito::where('estado', 'moroso')
                ->with('cliente')
                ->orderBy('fecha_vencimiento', 'asc')
                ->paginate(20),

            'creditosPorVencerPaginated' => Credito::where('estado', 'activo')
                ->whereBetween('fecha_vencimiento', [Carbon::today(), Carbon::today()->addDays(2)])
                ->with('cliente')
                ->orderBy('fecha_vencimiento', 'asc')
                ->paginate(20),
        ]);
    }
}

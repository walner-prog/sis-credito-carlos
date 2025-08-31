<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Cartera;
use App\Models\Credito;
use App\Models\Abono;
use App\Models\Role;
use App\Models\User;

class Dashboard extends Component
{
    public $clientesCount;
    public $carterasCount;
    public $creditosCount;
    public $abonosTotal;
    public $abonosPendientes;
    public $usuariosCount;
    public $rolesCount;

    public $proximoCredito;
    public $creditosPendientes;
    public $abonosRecientes;


    public function mount()
    {
        // Totales
        $this->clientesCount   = Cliente::where('estado', 'activo')->count();
        $this->carterasCount   = Cartera::count();
        $this->creditosCount   = Credito::where('estado', 'activo')->count();
        // Total de abonos pagados
        $this->abonosTotal = Abono::where('estado', 'pagado')->sum('monto_abono');

        // Total de abonos pendientes
        $this->abonosPendientes = Abono::where('estado', 'pendiente')->sum('monto_abono');

        $this->usuariosCount   = User::count();
        $this->rolesCount   = Role::count();

        // Próximo crédito (el más cercano en fecha)
        $this->proximoCredito = Credito::where('estado', 'activo')
            ->orderBy('fecha_vencimiento', 'asc')
            ->with('cliente')
            ->first();

        // Créditos pendientes (ejemplo: vencidos o en mora)
        $this->creditosPendientes = Credito::where('estado', 'activo')
            ->with('cliente')
            ->take(5)
            ->get();

        // Últimos abonos realizados
        $this->abonosRecientes = Abono::with('cliente')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

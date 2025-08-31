<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Cartera;
use App\Models\Cliente;
use Carbon\Carbon;

class AbonosReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = ''; // Agregado para el buscador
    public $filtroDia = null; // "abonaron", "no_abonaron"
    public $filtroEstadoCredito = null; // 'activo', 'moroso', null
    public $carteraId = null; // cartera seleccionada
    public $carteras = [];

    public function updatingFiltroDia() { $this->resetPage(); }
    public function updatingCarteraId() { $this->resetPage(); }
    public function updatingFiltroEstadoCredito() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); } // Agregado para resetear la paginación al buscar

    public function mount()
    {
        $this->carteras = Cartera::orderBy('nombre')->get();
    }
    
    // Método para resetear la búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    #[Computed]
    public function clientesFiltrados()
    {
        $hoy = Carbon::today('America/Managua');

        $query = Cliente::query()
            ->with(['creditos.abonos.user', 'cartera']);
            
        // Filtro por buscador inteligente
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombres', 'like', '%' . $this->search . '%')
                  ->orWhere('apellidos', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por estado del crédito
        if ($this->filtroEstadoCredito) {
            $query->whereHas('creditos', fn($q) => $q->where('estado', $this->filtroEstadoCredito));
        } else {
            // Si el filtro de estado está en 'todos', mostramos clientes con créditos activos o morosos
            $query->whereHas('creditos', fn($q) => $q->whereIn('estado', ['activo', 'moroso']));
        }
        
        // Filtro por cartera
        if ($this->carteraId) {
            $query->where('cartera_id', $this->carteraId);
        }

        // Filtro por abono del día
        if ($this->filtroDia === 'abonaron') {
            $query->whereHas('creditos.abonos', fn($q) => $q->whereDate('fecha_abono', $hoy));
        } elseif ($this->filtroDia === 'no_abonaron') {
            $query->whereDoesntHave('creditos.abonos', fn($q) => $q->whereDate('fecha_abono', $hoy));
        }

        $clientes = $query->paginate(10);

        $totalAbonado = 0;
        $clientesAbonaron = 0;
        $clientesNoAbonaron = 0;

        $clientes->getCollection()->transform(function ($cliente) use ($hoy, &$totalAbonado, &$clientesAbonaron, &$clientesNoAbonaron) {
            $detalleAbonos = [];
            $abonaronHoyCliente = false;

            foreach ($cliente->creditos as $credito) {
                // Solo consideramos créditos activos o morosos para este reporte
                if ($credito->estado !== 'activo' && $credito->estado !== 'moroso') {
                    continue;
                }
                
                $abonosHoy = $credito->abonos()->whereDate('fecha_abono', $hoy)->get();
                $tieneAbonoHoy = $abonosHoy->isNotEmpty();

                // Creamos un objeto de detalle para la vista
                $detalleAbono = (object)[
                    'user' => $tieneAbonoHoy ? $abonosHoy->first()->user?->name : null,
                    'cliente' => $cliente->nombres . ' ' . $cliente->apellidos,
                    'monto_credito' => $credito->monto_total,
                    'monto_abono' => $tieneAbonoHoy ? $abonosHoy->sum('monto_abono') : 0,
                    'estado' => $tieneAbonoHoy ? 'Pagó Hoy' : 'No Pagó Hoy',
                    'fecha_abono' => $tieneAbonoHoy ? $abonosHoy->first()->fecha_abono : null,
                    'comentarios' => $tieneAbonoHoy ? $abonosHoy->first()->comentarios : null,
                    'estado_credito' => $credito->estado // Añadimos el estado del crédito para la vista
                ];
                $detalleAbonos[] = $detalleAbono;

                if ($tieneAbonoHoy) {
                    $abonaronHoyCliente = true;
                    $totalAbonado += $detalleAbono->monto_abono;
                }
            }

            if ($abonaronHoyCliente) {
                $clientesAbonaron++;
            } else {
                $clientesNoAbonaron++;
            }

            $cliente->detalle_abonos = $detalleAbonos;
            return $cliente;
        });

        return [
            'clientes' => $clientes,
            'totalAbonado' => $totalAbonado,
            'clientesAbonaron' => $clientesAbonaron,
            'clientesNoAbonaron' => $clientesNoAbonaron,
        ];
    }

    public function render()
    {
        return view('livewire.abonos-report');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Cliente;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MisClientesReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $filtroDia = null; // "abonaron", "no_abonaron"
    public $filtroEstadoCredito = null; // 'activo', 'moroso', null

    public function updatingFiltroDia() { $this->resetPage(); }
    public function updatingFiltroEstadoCredito() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    #[Computed]
    public function clientesFiltrados()
    {
        $hoy = Carbon::today('America/Managua');
        $user = auth()->user();

        $query = Cliente::query()
            ->with(['creditos.abonos.user', 'cartera'])
            ->whereHas('cartera', function ($q) use ($user) {
                $q->where('user_id', $user->id); // 🔑 Solo clientes de las carteras del usuario
            });

        // 🔍 Buscador
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombres', 'like', '%' . $this->search . '%')
                  ->orWhere('apellidos', 'like', '%' . $this->search . '%');
            });
        }

        // 📌 Filtro por estado del crédito
        if ($this->filtroEstadoCredito) {
            $query->whereHas('creditos', fn($q) => $q->where('estado', $this->filtroEstadoCredito));
        } else {
            $query->whereHas('creditos', fn($q) => $q->whereIn('estado', ['activo', 'moroso']));
        }

        // 📌 Filtro por abonos del día
        if ($this->filtroDia === 'abonaron') {
            $query->whereHas('creditos.abonos', fn($q) => $q->whereDate('fecha_abono', $hoy));
        } elseif ($this->filtroDia === 'no_abonaron') {
            $query->whereDoesntHave('creditos.abonos', fn($q) => $q->whereDate('fecha_abono', $hoy));
        }

        $clientes = $query->paginate(10);

        // 📊 Métricas rápidas
        $totalAbonado = 0;
        $clientesAbonaron = 0;
        $clientesNoAbonaron = 0;

        $clientes->getCollection()->transform(function ($cliente) use ($hoy, &$totalAbonado, &$clientesAbonaron, &$clientesNoAbonaron) {
            $detalleAbonos = [];
            $abonaronHoyCliente = false;

            foreach ($cliente->creditos as $credito) {
                if (!in_array($credito->estado, ['activo', 'moroso'])) {
                    continue;
                }

                $abonosHoy = $credito->abonos()->whereDate('fecha_abono', $hoy)->get();
                $tieneAbonoHoy = $abonosHoy->isNotEmpty();

                $detalleAbonos[] = (object)[
                    'user' => $tieneAbonoHoy ? $abonosHoy->first()->user?->name : null,
                    'cliente' => $cliente->nombres . ' ' . $cliente->apellidos,
                    'monto_credito' => $credito->monto_total,
                    'monto_abono' => $tieneAbonoHoy ? $abonosHoy->sum('monto_abono') : 0,
                    'estado' => $tieneAbonoHoy ? 'Pagó Hoy' : 'No Pagó Hoy',
                    'fecha_abono' => $tieneAbonoHoy ? $abonosHoy->first()->fecha_abono : null,
                    'comentarios' => $tieneAbonoHoy ? $abonosHoy->first()->comentarios : null,
                    'estado_credito' => $credito->estado,
                ];

                if ($tieneAbonoHoy) {
                    $abonaronHoyCliente = true;
                    $totalAbonado += $abonosHoy->sum('monto_abono');
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

    public function downloadPDF()
    {
        $data = $this->clientesFiltrados;

        $pdf = Pdf::loadView('livewire.mis-clientes-report-pdf', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'mis_clientes_abonos_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.mis-clientes-report');
    }
}

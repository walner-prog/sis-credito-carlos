<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Credito;
use App\Models\Cliente;
use Livewire\WithPagination;
use App\Livewire\Forms\CreditoForm;
use Illuminate\Support\Facades\Auth;
use App\Models\Cartera;
use Barryvdh\DomPDF\Facade\Pdf;


class CreditosList extends Component
{
    use WithPagination;

    public CreditoForm $form;
    public $isOpen = false;
    public $modo = 'crear';
    public $search = '';
    public $creditoVer = null;
    public $verModal = false;
    public $cuotasCredito = [];
    public $mensajeEliminarCredito = '';

    // buscador inteligente
    public $clienteSearch = '';
    public $clientesFiltrados = [];

    public $modalConfirmar = false;
    public $creditoIdAEliminar = null;
    protected $paginationTheme = 'tailwind';
    public $openDetalles = [];      // controla qué tarjeta tiene los detalles abiertos
    public $openAcciones = [];      // controla qué menú de acciones está abierto


    public $buscarAbierto = false;

    public function toggleBuscar()
    {
        $this->buscarAbierto = !$this->buscarAbierto;
    }

    public function cerrarBuscar()
    {
        $this->buscarAbierto = false;
        $this->search = '';
    }

    public function toggleDetalles($id)
    {
        $this->openDetalles[$id] = !($this->openDetalles[$id] ?? false);
    }

    public function toggleAcciones($id)
    {
        $this->openAcciones[$id] = !($this->openAcciones[$id] ?? false);
    }

    public function cerrarAcciones($id)
    {
        $this->openAcciones[$id] = false;
    }




    public function updatingSearch()
    {
        $this->resetPage();
    }

    /* LISTA INICIAL (cuando está vacío el buscador) */
    protected function clientesIniciales()
    {
        return collect();
    }


    public function abrirModalCrear()
    {
        // Se impide la acción si el usuario es administrador
        if (Auth::user()->rol === 'admin') {
            return;
        }

        $this->resetForm();
        $this->modo = 'crear';
        $this->form->cargarConfiguracionGlobal();
        $this->clienteSearch = '';
        $this->clientesFiltrados = $this->clientesIniciales();
        $this->isOpen = true;
    }


    public function abrirModalEditar($id)
    {
        $credito = Credito::with(['cliente', 'cuotas'])->findOrFail($id);

        // Bloquear si ya existe alguna cuota que no esté pendiente
        if ($credito->cuotas()->whereIn('estado', ['pagada', 'parcial'])->exists()) {
            session()->flash('error', '❌ No puedes editar este crédito porque ya tiene abonos registrados.');
            return;
        }

        $this->form->setCredito($credito);

        $this->clienteSearch = optional($credito->cliente)->nombres . ' ' . optional($credito->cliente)->apellidos;
        $this->clientesFiltrados = $this->clientesIniciales();

        $this->modo = 'editar';
        $this->isOpen = true;
    }

    public function abrirModalVer($id)
    {
        $this->creditoVer = Credito::with(['cliente', 'cuotas'])->findOrFail($id);

        $this->cuotasCredito = $this->creditoVer->cuotas
            ->sortBy('numero_cuota')
            ->map(function ($cuota) {
                return [
                    'numero' => $cuota->numero_cuota,
                    'monto_original' => $cuota->monto_original,
                    'monto' => $cuota->monto,
                    'fecha' => $cuota->fecha_vencimiento,
                    'estado' => $cuota->estado,
                ];
            })->toArray();

        $this->verModal = true;
    }


    public function cerrarModalVer()
    {
        $this->creditoVer = null;
        $this->verModal = false;
    }

    public function guardar()
    {
        if ($this->modo === 'crear') {
            $this->form->store();
            session()->flash('create', 'Crédito creado correctamente.');
        } else {
            $this->form->update();
            session()->flash('update', 'Crédito actualizado correctamente.');
        }

        $this->resetForm();
        $this->isOpen = false;
    }


    public function confirmarEliminar($id)
    {
        $this->creditoIdAEliminar = $id;
        $this->mensajeEliminarCredito = ''; // Resetea mensaje previo
        $this->modalConfirmar = true;
    }

    // Nuevo método para abrir el modal de confirmación
   public function eliminarCredito()
{
    $credito = Credito::with(['abonos', 'cuotas'])->findOrFail($this->creditoIdAEliminar);

    $cantidadAbonos = $credito->abonos->count();
    $cantidadCuotas = $credito->cuotas->count();
    $cuotasPagadas = $credito->cuotas->whereIn('estado', ['pagada', 'parcial'])->count();

    // Mostrar advertencia solo si hay abonos o cuotas
    if (($cantidadAbonos > 0 || $cantidadCuotas > 0) && !$this->mensajeEliminarCredito) {
        $mensaje = "⚠️ Este crédito tiene";

        $partes = [];
        if ($cantidadAbonos > 0) {
            $partes[] = "$cantidadAbonos abono(s) realizados";
        }
        if ($cantidadCuotas > 0) {
            $partes[] = "$cantidadCuotas cuota(s) generadas";
        }
        if ($cuotasPagadas > 0) {
            $partes[] = "$cuotasPagadas cuota(s) ya pagadas";
        }

        $mensaje .= ' ' . implode(', ', $partes) . ". Si eliminas este crédito, también se eliminarán estos registros. ¿Deseas continuar?";

        $this->mensajeEliminarCredito = $mensaje;
        $this->modalConfirmar = true;
        return;
    }

    // Eliminar abonos y cuotas asociados
    $credito->abonos()->delete();
    $credito->cuotas()->delete();
    $credito->delete();

    session()->flash('delete', 'Crédito y sus registros relacionados eliminados correctamente.');

    $this->modalConfirmar = false;
    $this->creditoIdAEliminar = null;
    $this->mensajeEliminarCredito = '';
}




    public function resetForm()
    {
        $this->form->reset();
        $this->form->credito = null;
        $this->form->cliente_id = null;
        $this->clienteSearch = '';
        $this->clientesFiltrados = [];
    }

    /* Se dispara al teclear (usaremos wire:model.live en la vista) */
    public function updatedClienteSearch()
    {
        $term = trim($this->clienteSearch);

        if ($term === '') {
            $this->clientesFiltrados = [];
            return;
        }

        $user = Auth::user();

        $query = Cliente::query()
            ->when($user->hasRole('Cobrador'), function ($q) use ($user) {
                $cartera = Cartera::where('user_id', $user->id)->first();
                if ($cartera) {
                    return $q->where('cartera_id', $cartera->id);
                }
                return $q->whereRaw('1=0'); // Retorna una consulta vacía si no hay cartera
            })
            ->where(function ($q) use ($term) {
                $q->where('nombres', 'like', "%{$term}%")
                    ->orWhere('apellidos', 'like', "%{$term}%")
                    ->orWhere('identificacion', 'like', "%{$term}%")
                    ->orWhere('telefono', 'like', "%{$term}%");
            });

        $this->clientesFiltrados = $query->orderBy('nombres')->limit(10)->get();
    }


    public function seleccionarCliente($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $this->form->cliente_id = $cliente->id;
            $this->clienteSearch = $cliente->nombres . ' ' . $cliente->apellidos;
            $this->clientesFiltrados = [];
        }
    }


    public function downloadPDF()
    {
        $user = Auth::user();

        $creditos = Credito::query()
            ->when($user->hasRole('Cobrador'), function ($query) use ($user) {
                $cartera = Cartera::where('user_id', $user->id)->first();
                if (!$cartera) {
                    return $query->whereRaw('1=0');
                }
                return $query->whereHas('cliente', function ($q) use ($cartera) {
                    $q->where('cartera_id', $cartera->id);
                });
            })
            ->when($this->search, function ($query) {
                return $query->whereHas('cliente', function ($q) {
                    $q->where('nombres', 'like', "%{$this->search}%")
                        ->orWhere('apellidos', 'like', "%{$this->search}%");
                });
            })
            ->with('cliente')
            ->get();

        $pdf = Pdf::loadView('livewire.creditos-report-pdf', [
            'creditos' => $creditos,
            'fecha'    => now()->format('d/m/Y H:i'),
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'reporte_creditos_' . now()->format('Ymd_His') . '.pdf'
        );
    }


    public function render()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        $creditos = Credito::query()
            ->when($user->hasRole('Cobrador'), function ($query) use ($user) {
                $cartera = Cartera::where('user_id', $user->id)->first();
                if (!$cartera) {
                    return $query->whereRaw('1=0');
                }
                return $query->whereHas('cliente', function ($q) use ($cartera) {
                    $q->where('cartera_id', $cartera->id);
                });
            })
            ->when($this->search, function ($query) {
                return $query->whereHas('cliente', function ($q) {
                    $q->where('nombres', 'like', "%{$this->search}%")
                        ->orWhere('apellidos', 'like', "%{$this->search}%");
                });
            })
            ->whereNull('deleted_at')
            ->with('cliente')
            ->latest()
            ->paginate(5);

        return view('livewire.creditos-list', compact('creditos'));
    }
}

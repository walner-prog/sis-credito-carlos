<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use Livewire\WithPagination;
use App\Livewire\Forms\ClienteForm;
use App\Models\Cartera;
use App\Models\Credito;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Importar la clase Auth

class ClientesList extends Component
{
    use WithPagination;

    public ClienteForm $form;
    public $isOpen = false;
    public $vistaCliente = null;
    public $modo = 'crear'; // crear | editar 
    public $search = '';
    public $clienteVer = null; // cliente que vamos a mostrar
    public $verModal = false;
    public $modalConfirmar = false;
    public $clienteIdAEliminar = null;
    public $creditoSeleccionado = null; // Crédito seleccionado para el abono

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModalCrear()
    {
        $this->resetForm();
        // 🚨 CAMBIO: Pasar solo la cartera del usuario al formulario
        $this->form->carteras = $this->getCarteraDelUsuario();
        $this->modo = 'crear';
        $this->isOpen = true;
    }

    public function abrirModalEditar($id)
    {
        $cliente = Cliente::findOrFail($id);
        $this->form->setCliente($cliente);
        // 🚨 CAMBIO: Pasar solo la cartera del usuario al formulario
        $this->form->carteras = $this->getCarteraDelUsuario();
        $this->modo = 'editar';
        $this->isOpen = true;
    }

    public function abrirModalVer($id)
    {
        $this->clienteVer = Cliente::with(['creditos.abonos', 'creditos.cartera'])->findOrFail($id);
        $this->verModal = true;
    }

    public function cerrarModalVer()
    {
        $this->clienteVer = null;
        $this->verModal = false;
    }

    public function guardar()
    {
        if ($this->modo === 'crear') {
            $this->form->store();
            session()->flash('create', 'Cliente creado correctamente.');
        } else {
            $this->form->update();
            session()->flash('update', 'Cliente actualizado correctamente.');
        }

        $this->resetForm();
        $this->isOpen = false;
    }

    public function confirmarEliminar($id)
    {
        $this->clienteIdAEliminar = $id;
        $this->modalConfirmar = true;
    }

    public function eliminarConfirmado()
    {
        $cliente = Cliente::findOrFail($this->clienteIdAEliminar);
        $cliente->delete();
        session()->flash('delete', 'Cliente eliminado correctamente.');
        $this->resetPage();
        $this->modalConfirmar = false;
        $this->clienteIdAEliminar = null;
    }

    public function resetForm()
    {
        $this->form->reset();
        $this->form->cliente = null;
        $this->creditoSeleccionado = null;
    }

    public function actualizarCuotasDiariamente(): void
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

        if ($actualizadas > 0) {
            session()->flash('message', "✅ Se actualizaron {$actualizadas} cuota(s) a estado atrasada.");
        } else {
            session()->flash('message', "ℹ️ No había cuotas vencidas para actualizar.");
        }
    }

    public function render()
    {
        $user = Auth::user();
        $query = Cliente::query()
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->where('nombres', 'like', "%{$this->search}%")
                    ->orWhere('apellidos', 'like', "%{$this->search}%")
                    ->orWhere('identificacion', 'like', "%{$this->search}%");
            });

        // 🚨 MANTENER ESTA LÓGICA: FILTRAR SI EL USUARIO ES COBRADOR
        if ($user && $user->hasRole('Cobrador')) {
            $cartera = Cartera::where('user_id', $user->id)->first();
            if ($cartera) {
                $query->where('cartera_id', $cartera->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $clientes = $query->latest()->paginate(5);

        return view('livewire.clientes-list', compact('clientes'));
    }

    /**
     * Método auxiliar para obtener la cartera del usuario autenticado.
     */
    private function getCarteraDelUsuario()
    {
        $user = Auth::user();
        if ($user) {
            return Cartera::where('user_id', $user->id)->get();
        }
        return collect();
    }
}

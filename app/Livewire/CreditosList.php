<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Credito;
use App\Models\Cliente;
use Livewire\WithPagination;
use App\Livewire\Forms\CreditoForm;
use Illuminate\Support\Facades\Auth;
use App\Models\Cartera;

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

    // buscador inteligente
    public $clienteSearch = '';
    public $clientesFiltrados = [];

    public $modalConfirmar = false;
    public $creditoIdAEliminar = null;
    protected $paginationTheme = 'tailwind';
    

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
        // Se impide la acción si el usuario es administrador
        if (Auth::user()->rol === 'admin') {
            return;
        }

        $credito = Credito::with('cliente')->findOrFail($id);
        $this->form->setCredito($credito);

        $this->clienteSearch = optional($credito->cliente)->nombres.' '.optional($credito->cliente)->apellidos;
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

    // Nuevo método para abrir el modal de confirmación
    public function confirmarEliminar($id)
    {
        // Se impide la acción si el usuario es administrador
        if (Auth::user()->rol === 'admin') {
            return;
        }

        $this->creditoIdAEliminar = $id;
        $this->modalConfirmar = true;
    }

    // Nuevo método para ejecutar la eliminación
    public function eliminarConfirmado()
    {
        // Se impide la acción si el usuario es administrador
        if (Auth::user()->rol === 'admin') {
            return;
        }
        
        $credito = Credito::findOrFail($this->creditoIdAEliminar);
        $credito->delete();
        session()->flash('delete', 'Crédito eliminado correctamente.');
        
        $this->modalConfirmar = false;
        $this->creditoIdAEliminar = null;
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
        ->where(function($q) use ($term) {
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
            $this->clienteSearch = $cliente->nombres.' '.$cliente->apellidos;
            $this->clientesFiltrados = [];
        }
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

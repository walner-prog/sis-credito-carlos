<?php


namespace App\Livewire;

use Livewire\Component;
use App\Models\Abono;
use App\Models\Credito;
use Livewire\WithPagination;
use App\Livewire\Forms\AbonoForm;
use App\Models\Cartera;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbonosList extends Component
{
    use WithPagination;

    public AbonoForm $form;
    public $isOpen = false;
    public $modo = 'crear';
    public $search = '';
    public $abonoVer = null;
    public $verModal = false;
    public $cuotasCredito = [];
    public $mostrarDetalleCuotas = false;
    public $proximaCuota = null;
    public $modalConfirmar = false;
    public $abonoAEliminar = null;
    // Propiedad para controlar si el buscador está abierto en móvil
     public $buscarAbierto = false;



    // Buscador
    public $creditoSearch = '';
    public $creditosFiltrados = [];

    protected $paginationTheme = 'tailwind';

    // Propiedades para la vista
    public $creditoSeleccionado = null;

    public $openAcciones = []; // control de menús desplegables por abono

    // Nueva propiedad
public $modalReporte = false;

// Abrir modal reporte
public function abrirModalReporte()
{
    $this->modalReporte = true;
}

// Cerrar modal reporte
public function cerrarModalReporte()
{
    $this->modalReporte = false;
}



public function toggleAcciones($id)
{
    // Cerrar todos antes de abrir uno
    foreach ($this->openAcciones as $key => $estado) {
        $this->openAcciones[$key] = false;
    }

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

    protected function creditosIniciales()
    {
        return collect();
    }

  


    public function abrirModalCrear()
    {
        $this->resetForm();
        $this->creditoSearch = '';
        $this->creditosFiltrados = $this->creditosIniciales();
        $this->modo = 'crear';
        $this->isOpen = true;
    }

    public function abrirModalEditar($id)
    {
        $abono = Abono::with('credito')->findOrFail($id);
        $this->form->setAbono($abono);

        $credito = $abono->credito;
        $this->creditoSearch = 'Crédito #' . $credito->id . ' - Cliente: ' . $credito->cliente->nombres;
        $this->creditosFiltrados = $this->creditosIniciales();

        $this->modo = 'editar';
        $this->isOpen = true;
    }

    public function abrirModalVer($id)
    {
        $this->abonoVer = Abono::with(['credito.cliente'])->findOrFail($id);
        $this->verModal = true;
    }

    public function cerrarModalVer()
    {
        $this->abonoVer = null;
        $this->verModal = false;
    }


   public function guardar()
{
    if ($this->modo === 'crear') {
        $this->form->store();
        session()->flash('create', 'Abono registrado correctamente.');
    } else {
        $this->form->update();
        session()->flash('update', 'Abono actualizado correctamente.');
    }

    $this->resetForm();
    $this->isOpen = false;
}

public function confirmarEliminar($id)
{
    $this->abonoAEliminar = $id;
    $this->modalConfirmar = true;
}


public function eliminarConfirmado()
{
    if ($this->abonoAEliminar) {
        $abono = Abono::findOrFail($this->abonoAEliminar);
        $this->form->deleteAbono($abono);

        session()->flash('delete', '✅ Abono eliminado correctamente.');
    }

    $this->abonoAEliminar = null;
    $this->modalConfirmar = false;
}


    

    public function resetForm()
    {
        $this->form->reset();
        $this->creditoSearch = '';
        $this->creditosFiltrados = [];
        $this->creditoSeleccionado = null;
    }


 

    /* Lógica del buscador de créditos */
 public function updatedCreditoSearch()
{
    $term = trim($this->creditoSearch);
    $user = Auth::user();

    if ($term === '') {
        $this->creditosFiltrados = [];
        return;
    }

    $query = Credito::query()
        ->with('cliente')
        ->where(function ($q) use ($term) {
            $q->where('id', 'like', "%{$term}%")
                ->orWhereHas('cliente', function ($q2) use ($term) {
                    $q2->where('nombres', 'like', "%{$term}%")
                        ->orWhere('apellidos', 'like', "%{$term}%");
                });
        })
        ->whereIn('estado', ['activo', 'moroso'])
        ->whereNull('deleted_at');

    // 🚨 Restricción de cartera en el buscador de créditos - CORREGIDA
    if ($user && $user->hasRole('Cobrador')) {
        $cartera = Cartera::where('user_id', $user->id)->first();
        if ($cartera) {
            // Filtra los créditos que pertenecen a un cliente de la cartera del cobrador
            $query->whereHas('cliente', function ($q) use ($cartera) {
                $q->where('cartera_id', $cartera->id);
            });
        } else {
            // Si el cobrador no tiene cartera, el buscador no encuentra nada
            $query->whereRaw('1 = 0');
        }
    }

    $this->creditosFiltrados = $query->orderBy('id', 'desc')->limit(10)->get();
}


  

public function toggleDetalleCuotas()
{
    $this->mostrarDetalleCuotas = !$this->mostrarDetalleCuotas;
}




public function seleccionarCredito($id)
{
    $credito = Credito::with(['cliente', 'cuotas'])->find($id);
    if ($credito) {
        $this->form->credito_id = $credito->id;
        $this->creditoSearch = 'Crédito #' . $credito->id . ' - Cliente: ' 
            . optional($credito->cliente)->nombres . ' ' 
            . optional($credito->cliente)->apellidos;

        $this->creditosFiltrados = [];
        $this->creditoSeleccionado = $credito;

        // Sugerir saldo total pendiente
       // $this->form->monto_abono = $credito->saldo_pendiente;
        $this->form->monto_abono = optional($this->proximaCuota)->monto ?? null;


        // Guardar cuotas asociadas
        $this->cuotasCredito = $credito->cuotas()
            ->orderBy('numero_cuota', 'asc')
            ->get()
            ->map(function ($cuota) {
                return [
                    'numero' => $cuota->numero_cuota,
                    'monto_original' => $cuota->monto_original,
                    'monto' => $cuota->monto,
                    'fecha' => $cuota->fecha_vencimiento,
                    'estado' => $cuota->estado,
                ];
            })->toArray();

            $this->proximaCuota = $credito->cuotas()
    ->where('estado', 'pendiente')
    ->orderBy('numero_cuota', 'asc')
    ->first();

    }
}


    public function render()
{
    // Obtener el usuario autenticado
    $user = Auth::user();

    $query = Abono::query()
        ->with(['credito.cliente'])
        ->whereNull('deleted_at')
        ->when($this->search, function ($query) {
            $query->whereHas('credito', function ($q) {
                $q->where('id', 'like', "%{$this->search}%");
            })
            ->orWhereHas('credito.cliente', function ($q) {
                $q->where('nombres', 'like', "%{$this->search}%")
                    ->orWhere('apellidos', 'like', "%{$this->search}%");
            });
        });

    // 🚨 Lógica de restricción de acceso por rol - CORREGIDA
    if ($user && $user->hasRole('Cobrador')) {
        $cartera = Cartera::where('user_id', $user->id)->first();
        if ($cartera) {
            // Filtra los abonos a través del crédito y luego a través del cliente
            $query->whereHas('credito.cliente', function ($q) use ($cartera) {
                $q->where('cartera_id', $cartera->id);
            });
        } else {
            // Si el cobrador no tiene cartera, no muestra ningún abono
            $query->whereRaw('1 = 0');
        }
    }

    $abonos = $query->latest()->paginate(5);

    return view('livewire.abonos-list', compact('abonos'));
}
}

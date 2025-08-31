<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cartera;
use Livewire\WithPagination;
use App\Livewire\Forms\CarteraForm;

class CarterasList extends Component
{
    use WithPagination;

    public CarteraForm $form;
    public $isOpen = false;
    public $modo = 'crear'; // crear | editar
    public $search = '';
    public $carteraVer = null;
    public $verModal = false;
      public $modalConfirmar = false;
    public $carteraIdAEliminar = null;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

 public function abrirModalCrear()
{
    $this->resetForm(); // primero reseteas
    $this->form->usuarios = \App\Models\User::orderBy('name')->get(); // luego asignas usuarios
    $this->modo = 'crear';
    $this->isOpen = true;
}

public function abrirModalEditar($id)
{
    $this->form->usuarios = \App\Models\User::orderBy('name')->get(); // <--- PASA LOS USUARIOS AQUÍ
    $cartera = Cartera::findOrFail($id);
    $this->form->setCartera($cartera);
    $this->modo = 'editar';
    $this->isOpen = true;
}

    public function abrirModalVer($id)
{
    // Carga la cartera y sus clientes, y dentro de los clientes, los créditos.
    $this->carteraVer = Cartera::with('clientes.creditos')->findOrFail($id);
    $this->verModal = true;
}

    public function cerrarModalVer()
    {
        $this->carteraVer = null;
        $this->verModal = false;
    }

    public function guardar()
    {
        if ($this->modo === 'crear') {
            $this->form->store();
            session()->flash('create', 'Cartera creada correctamente.');
        } else {
            $this->form->update();
            session()->flash('update', 'Cartera actualizada correctamente.');
        }

        $this->resetForm();
        $this->isOpen = false;
    }

  // Nuevo método para abrir el modal de confirmación
    public function confirmarEliminar($id)
    {
        $this->carteraIdAEliminar = $id;
        $this->modalConfirmar = true;
    }

    // Nuevo método para ejecutar la eliminación
    public function eliminarConfirmado()
    {
        $cartera = Cartera::findOrFail($this->carteraIdAEliminar);
        $cartera->delete(); // soft delete
        session()->flash('delete', 'Cartera eliminada correctamente.');
        
        $this->modalConfirmar = false; // Cierra el modal
        $this->carteraIdAEliminar = null; // Resetea la propiedad
    }


    public function resetForm()
    {
        $this->form->reset();
        $this->form->cartera = null;
    }

    public function render()
    {
        $carteras = Cartera::query()
            ->whereNull('deleted_at')
            ->where('nombre', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(5);

        return view('livewire.carteras-list', compact('carteras'));
    }
}


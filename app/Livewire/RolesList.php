<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Livewire\Forms\RoleForm;

class RolesList extends Component
{
    use WithPagination;

    public RoleForm $form;
    public $isOpen = false;
    public $modo = 'crear'; // crear | editar
    public $search = '';
    public $rolVer = null;
    public $verModal = false;

    // Nuevas propiedades para el modal de confirmación
    public $modalConfirmar = false;
    public $rolIdAEliminar = null;

    public $menuAccionId = null;
    public $buscarAbierto = false;




    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModalCrear()
    {
        $this->resetForm();
        $this->form->allPermissions = Permission::orderBy('name')->get();
        $this->form->permissions = [];
        $this->modo = 'crear';
        $this->isOpen = true;
    }

    public function abrirModalEditar($id)
    {
        $rol = Role::findOrFail($id);
        $this->form->setRole($rol);
        $this->modo = 'editar';
        $this->isOpen = true;
    }

    public function abrirModalVer($id)
    {
        $this->rolVer = Role::with('permissions')->findOrFail($id);
        $this->verModal = true;
    }

    public function cerrarModalVer()
    {
        $this->rolVer = null;
        $this->verModal = false;
    }

    public function guardar()
    {
        if ($this->modo === 'crear') {
            $this->form->store();
            session()->flash('create', 'Rol creado correctamente.');
        } else {
            $this->form->update();
            session()->flash('update', 'Rol actualizado correctamente.');
        }

        $this->resetForm();
        $this->isOpen = false;
    }

    // Nuevo método para abrir el modal de confirmación
    public function confirmarEliminar($id)
    {
        $this->rolIdAEliminar = $id;
        $this->modalConfirmar = true;
    }
// Nuevo método para ejecutar la eliminación
public function eliminarConfirmado()
{
    $role = Role::findOrFail($this->rolIdAEliminar);

    // Evitar eliminar roles críticos
    if (in_array($role->name, ['Administrador', 'Cobrador'])) {
        session()->flash('error', "El rol {$role->name} no se puede eliminar.");
        $this->modalConfirmar = false; // Cierra el modal
        $this->rolIdAEliminar = null;  // Resetea el ID
        return;
    }

    $role->delete();
    session()->flash('delete', 'Rol eliminado correctamente.');
    $this->resetPage();

    $this->modalConfirmar = false; // Cierra el modal
    $this->rolIdAEliminar = null;  // Resetea el ID
}

public function toggleMenu($id)
{
    $this->menuAccionId = $this->menuAccionId === $id ? null : $id;
}



public function toggleBuscar()
{
    $this->buscarAbierto = !$this->buscarAbierto;
}

public function cerrarBuscar()
{
    $this->buscarAbierto = false;
    $this->search = '';
}



    public function resetForm()
    {
        $this->form->reset();
        $this->form->role = null;
    }

    public function render()
    {
        $roles = Role::query()
            ->where('name', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(5);

        return view('livewire.roles-list', compact('roles'));
    }
}
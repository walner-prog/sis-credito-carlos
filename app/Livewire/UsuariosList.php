<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\User;
use App\Livewire\Forms\UsuarioForm;
use Livewire\WithPagination;

class UsuariosList extends Component
{
    use WithPagination, WithFileUploads;

    public UsuarioForm $form;
    public $isOpen = false;
    public $modo = 'crear'; // crear | editar
    public $search = '';
    public $verModal = false;
    public $usuarioVer = null;
    public $modalConfirmar = false;
    public $usuarioIdAEliminar = null;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // -------------------------------
    // 📌 CRUD
    // -------------------------------
    public function abrirModalCrear()
    {
        $this->resetForm();
        $this->form->roles = \App\Models\Role::all();
        $this->form->carteras = \App\Models\Cartera::all();
        $this->modo = 'crear';
        $this->isOpen = true;
    }

    public function abrirModalEditar($id)
    {
        $this->resetForm();
        $usuario = User::findOrFail($id);
        $this->form->setUsuario($usuario);
        $this->form->carteras = \App\Models\Cartera::all();
        $this->modo = 'editar';
        $this->isOpen = true;
    }

    public function cerrarModal()
    {
        $this->isOpen = false;
    }

    public function guardar()
    {
        if ($this->modo === 'crear') {
            $this->form->store();
            session()->flash('create', '✅ Usuario creado correctamente.');
        } else {
            $this->form->update();
            session()->flash('update', '✏️ Usuario actualizado correctamente.');
        }

        $this->resetForm();
        $this->isOpen = false;
    }

    // Nuevo método para abrir el modal de confirmación
    public function confirmarEliminar($id)
    {
        $this->usuarioIdAEliminar = $id;
        $this->modalConfirmar = true;
    }

    // Nuevo método para ejecutar la eliminación
    public function eliminarConfirmado()
    {
        $usuario = User::findOrFail($this->usuarioIdAEliminar);

        if ($usuario->email === 'admin@tusitio.com' || $usuario->email === 'ca140611@gmail.com') {
            session()->flash('error', '⚠️ No puedes eliminar al usuario administrador.');
            $this->modalConfirmar = false;
            $this->usuarioIdAEliminar = null;
            return;
        }

        $usuario->delete();
        session()->flash('delete', '🗑️ Usuario eliminado correctamente.');
        $this->resetPage();

        $this->modalConfirmar = false;
        $this->usuarioIdAEliminar = null;
    }

    public function resetForm()
    {
        $this->form->reset();
        $this->form->usuario = null;
    }

    // -------------------------------
    // 📌 Ver Detalles
    // -------------------------------
    public function abrirModalVer($id)
    {
        $this->usuarioVer = User::findOrFail($id);
        $this->verModal = true;
    }

    public function cerrarModalVer()
    {
        $this->usuarioVer = null;
        $this->verModal = false;
    }

    // -------------------------------
    // 📌 Render
    // -------------------------------
    public function render()
    {
        $usuarios = User::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->paginate(5);

        return view('livewire.usuarios-list', compact('usuarios'));
    }
}
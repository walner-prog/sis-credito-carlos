<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class RoleForm extends Form
{
    public $name = '';
    public $permissions = []; // lista de permisos seleccionados
    public $allPermissions = []; // todos los permisos para el select
    public ?Role $role = null;

    public function mount()
    {
        $this->allPermissions = Permission::orderBy('name')->get();
        $this->permissions = [];
    }

    public function rules(): array
    {
        $rules = [
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ];

        // Solo validar nombre si no es Administrador
        if (! $this->role || $this->role->name !== 'Administrador') {
            $rules['name'] = [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->role?->id),
            ];
        }

        return $rules;
    }




    public function setRole(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->permissions = $role->permissions()->pluck('id')->toArray();
        $this->allPermissions = Permission::orderBy('name')->get();
    }

    protected function payload(): array
    {
        return [
            'name' => $this->name,
        ];
    }



public function store()
{
    $this->validate();
    $role = Role::create($this->payload());

    if ($this->permissions) {
        $perms = Permission::whereIn('id', $this->permissions)->pluck('name')->toArray();
        $role->syncPermissions($perms);
    }
}

public function update()
{
    if (!$this->role) return;

    // Evitar modificar el rol Administrador
    if ($this->role->name === 'Administrador') {
        // Solo actualizar permisos
        $perms = Permission::whereIn('id', $this->permissions ?? [])->pluck('name')->toArray();
        $this->role->syncPermissions($perms);
        return;
    }

    $this->validate();
    $this->role->update($this->payload());

    $perms = Permission::whereIn('id', $this->permissions ?? [])->pluck('name')->toArray();
    $this->role->syncPermissions($perms);
}

}

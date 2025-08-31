<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Cartera;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Closure; // 👈 Importa la clase Closure

class UsuarioForm extends Form
{
    use WithFileUploads;

    public $name = '';
    public $username = '';
    public $email = '';
    public $password = '';
    public $profile_photo;
    public $roles = [];
    public $role_id = null;
    public $cartera_id = null;
    public $carteras = [];
    public ?User $usuario = null;
    public ?Cartera $carteraAsignada = null;

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')->ignore($this->usuario?->id)->whereNull('deleted_at')
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($this->usuario?->id)->whereNull('deleted_at')
            ],
            'password' => [$this->usuario ? 'nullable' : 'required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'cartera_id' => [
                'nullable',
                'exists:carteras,id',
                // 🚨 NUEVA REGLA: Valida que la cartera no esté ya asignada a otro usuario
                function (string $attribute, mixed $value, Closure $fail) {
                    $selectedRole = Role::find($this->role_id);
                    // Solo aplica esta validación si el rol es 'Cobrador' y se ha seleccionado una cartera
                    if ($selectedRole && $selectedRole->name === 'Cobrador' && $value) {
                        $cartera = Cartera::find($value);
                        // Si la cartera está asignada a otro usuario, falla
                        if ($cartera && $cartera->user_id && $cartera->user_id != $this->usuario?->id) {
                            $fail("La cartera seleccionada ya está asignada a otro cobrador.");
                        }
                    }
                },
                // La regla original de 'requiredIf'
                Rule::requiredIf(function () {
                    $selectedRole = Role::find($this->role_id);
                    return $selectedRole && $selectedRole->name === 'Cobrador';
                })
            ],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'Debes seleccionar un rol.',
            'cartera_id.required' => 'La cartera es obligatoria para el rol de Cobrador.',
        ];
    }

    public function setUsuario(User $usuario)
    {
        $this->usuario = $usuario;
        $this->name = $usuario->name;
        $this->username = $usuario->username;
        $this->email = $usuario->email;
        $this->role_id = $usuario->roles()->pluck('id')->first();
        
        // Encuentra la cartera asignada a este usuario
        $carteraAsignada = Cartera::where('user_id', $usuario->id)->first();
        $this->cartera_id = $carteraAsignada ? $carteraAsignada->id : null;
        
        $this->roles = Role::all();
    }

    protected function payload(): array
    {
        $data = $this->only(['name', 'username', 'email']);
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->profile_photo) {
            if ($this->usuario && $this->usuario->profile_photo_path) {
                Storage::delete($this->usuario->profile_photo_path);
            }
            $path = $this->profile_photo->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        return $data;
    }

    public function store()
    {
        $this->validate();
        $usuario = User::withTrashed()->where('username', $this->username)->first();

        if ($usuario) {
            if ($usuario->trashed()) {
                $usuario->restore();
                $usuario->update($this->payload());
            } else {
                return;
            }
        } else {
            $usuario = User::create($this->payload());
        }

        if ($this->role_id) {
            $role = Role::find($this->role_id);
            if ($role) {
                $usuario->assignRole($role->name);
                // Si el rol es 'Cobrador' y se seleccionó una cartera
                if ($role->name === 'Cobrador' && $this->cartera_id) {
                    $cartera = Cartera::find($this->cartera_id);
                    if ($cartera) {
                        // Desvincula cualquier usuario anterior
                        Cartera::where('id', $this->cartera_id)->update(['user_id' => null]);
                        // Asigna la cartera al nuevo usuario
                        $cartera->update(['user_id' => $usuario->id]);
                    }
                }
            }
        }
    }

    public function update()
    {
        $this->validate();

        if (!$this->usuario) {
            return;
        }

        $this->usuario->update($this->payload());

        $role = Role::find($this->role_id);

        if ($this->role_id && !in_array($this->usuario->email, ['ca140611@gmail.com', 'admin@admin.com'])) {
            $this->usuario->syncRoles([$role->name]);
        }
        
        // 🚨 Lógica para manejar la asignación de la cartera
        // Si el rol es "Cobrador"...
        if ($role->name === 'Cobrador') {
            // Desvincula cualquier cartera que el usuario pudiera tener antes
            Cartera::where('user_id', $this->usuario->id)->update(['user_id' => null]);
            
            // Asigna la nueva cartera, si se seleccionó una
            if ($this->cartera_id) {
                $cartera = Cartera::find($this->cartera_id);
                if ($cartera) {
                    // Primero, desvincula cualquier usuario anterior de la nueva cartera
                    Cartera::where('id', $this->cartera_id)->update(['user_id' => null]);
                    // Luego, asigna la nueva cartera al usuario actual
                    $cartera->update(['user_id' => $this->usuario->id]);
                }
            }
        } 
        // Si el rol no es "Cobrador"
        else {
            // Desvincula cualquier cartera que el usuario pudiera tener
            Cartera::where('user_id', $this->usuario->id)->update(['user_id' => null]);
        }
    }
}

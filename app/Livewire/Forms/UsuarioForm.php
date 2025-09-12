<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Cartera;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Closure;

class UsuarioForm extends Form
{
    use WithFileUploads;

    public $name = '';
    public $username = '';
    public $email = '';
    public $password = '';
    public $profile_photo_path;

    // Para manejo temporal de foto en Imgbb
    public $profilePhotoTemp;
    public $profilePhotoDeleteTempUrl;

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
                function (string $attribute, mixed $value, Closure $fail) {
                    $selectedRole = Role::find($this->role_id);
                    if ($selectedRole && $selectedRole->name === 'Cobrador' && $value) {
                        $cartera = Cartera::find($value);
                        if ($cartera && $cartera->user_id && $cartera->user_id != $this->usuario?->id) {
                            $fail("La cartera seleccionada ya está asignada a otro cobrador.");
                        }
                    }
                },
                Rule::requiredIf(function () {
                    $selectedRole = Role::find($this->role_id);
                    return $selectedRole && $selectedRole->name === 'Cobrador';
                })
            ],
            'profile_photo_path' => ['nullable', 'image', 'max:2048'],
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

        $carteraAsignada = Cartera::where('user_id', $usuario->id)->first();
        $this->cartera_id = $carteraAsignada ? $carteraAsignada->id : null;

        $this->roles = Role::all();
    }

    public function updatedProfilePhotoPath()
    {
        if ($this->profilePhotoDeleteTempUrl) {
            Http::get($this->profilePhotoDeleteTempUrl);
            $this->profilePhotoTemp = null;
            $this->profilePhotoDeleteTempUrl = null;
        }

        $imageData = base64_encode(file_get_contents($this->profile_photo_path->getRealPath()));

        $prefijo = 'usuario_Empresa_carlosQ_';
        $extension = $this->profile_photo_path->getClientOriginalExtension();
        $nombreArchivo = $prefijo . time() . '.' . $extension;

        $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
            'key'   => '0ba2bdf79d7d4216d6f3a3efb37e9fc7',
            'image' => $imageData,
            'name'  => $nombreArchivo,
        ]);

        if ($response->successful() && $response->json('success')) {
            $this->profilePhotoTemp = $response->json('data.url');
            $this->profilePhotoDeleteTempUrl = $response->json('data.delete_url');
        } else {
            session()->flash('error', 'No se pudo subir la foto de perfil a Imgbb.');
        }
    }

  protected function payload(): array
{
    $data = $this->only(['name', 'username', 'email']);
    
    if ($this->password) {
        $data['password'] = Hash::make($this->password);
    }

    // Si hay foto temporal en Imgbb, la asignamos directamente
    if ($this->profilePhotoTemp) {
        // Eliminar foto anterior en Imgbb si existe
        if ($this->usuario && $this->usuario->delete_profile_photo_path) {
            Http::get($this->usuario->delete_profile_photo_path);
        }

        $data['profile_photo_path'] = $this->profilePhotoTemp;
        $data['delete_profile_photo_path'] = $this->profilePhotoDeleteTempUrl;

        // Limpiar temporales
        $this->profilePhotoTemp = null;
        $this->profilePhotoDeleteTempUrl = null;
        $this->profile_photo_path = null;
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
                if ($role->name === 'Cobrador' && $this->cartera_id) {
                    $cartera = Cartera::find($this->cartera_id);
                    if ($cartera) {
                        Cartera::where('id', $this->cartera_id)->update(['user_id' => null]);
                        $cartera->update(['user_id' => $usuario->id]);
                    }
                }
            }
        }
    }

    public function update()
    {
        $this->validate();
        if (!$this->usuario) return;

        $this->usuario->update($this->payload());

        $role = Role::find($this->role_id);
        if ($this->role_id && !in_array($this->usuario->email, ['ca140611@gmail.com', 'admin@admin.com'])) {
            $this->usuario->syncRoles([$role->name]);
        }

        if ($role->name === 'Cobrador') {
            Cartera::where('user_id', $this->usuario->id)->update(['user_id' => null]);
            if ($this->cartera_id) {
                $cartera = Cartera::find($this->cartera_id);
                if ($cartera) {
                    Cartera::where('id', $this->cartera_id)->update(['user_id' => null]);
                    $cartera->update(['user_id' => $this->usuario->id]);
                }
            }
        } else {
            Cartera::where('user_id', $this->usuario->id)->update(['user_id' => null]);
        }
    }
}

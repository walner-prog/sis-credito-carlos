<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Validation\Rule;
use App\Models\Cartera;
use App\Models\User;
use Closure;

class CarteraForm extends Form
{
    public $nombre = '';
    public $estado = 'activa';
    public $user_id = null; // <--- aquí guardaremos el usuario seleccionado
    public $usuarios = []; // <--- lista de usuarios para el select
    public ?Cartera $cartera = null;

    public function mount()
    {
        $this->usuarios = User::orderBy('name')->get();
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('carteras', 'nombre')
                    ->ignore($this->cartera?->id)
                    ->whereNull('deleted_at'),
            ],
            'estado' => ['required', Rule::in(['activa', 'inactiva'])],
            'user_id' => [
                'nullable',
                'exists:users,id',
                // 🚨 NUEVA REGLA: Valida que el usuario no tenga otra cartera
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value) {
                        // Busca si existe otra cartera con este user_id
                        $query = Cartera::where('user_id', $value);

                        // Si estamos editando, ignora la cartera actual
                        if ($this->cartera) {
                            $query->where('id', '!=', $this->cartera->id);
                        }

                        if ($query->exists()) {
                            $fail("El usuario seleccionado ya tiene otra cartera asignada.");
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.unique' => 'Este nombre de cartera ya existe.',
            'user_id.exists' => 'El usuario seleccionado no es válido.',
            // El mensaje para la validación personalizada está dentro de la regla.
        ];
    }

    public function setCartera(Cartera $cartera): void
    {
        $this->cartera = $cartera;
        $this->nombre = $cartera->nombre;
        $this->estado = $cartera->estado ? 'activa' : 'inactiva';
        $this->user_id = $cartera->user_id; // <--- carga el usuario existente
    }

    protected function payload(): array
    {
        return $this->only([
            'nombre',
            'estado',
            'user_id', // <--- incluir en la creación/actualización
        ]);
    }

    public function store(): void
    {
        $this->validate($this->rules(), $this->messages());
        Cartera::create($this->payload());
        $this->reset();
        $this->estado = 'activa';
    }

   public function update(): void
    {
        if (! $this->cartera) {
            throw new \RuntimeException('No hay cartera cargada para actualizar.');
        }

        $this->validate($this->rules(), $this->messages());
        $this->cartera->update($this->payload());
    }
}

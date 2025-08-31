<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Validation\Rule;
use App\Models\Cliente;
use App\Models\Cartera;
use Illuminate\Support\Facades\Auth; // 👈 Importa la clase Auth

class ClienteForm extends Form
{
    public $nombres = '';
    public $apellidos = '';
    public $identificacion = '';
    public $telefono = '';
    public $direccion = '';
    public $km_referencia = '';
    public $estado = 'activo';
    public $cartera_id = null;
    
    public ?Cliente $cliente = null;
    public $carteras = []; // para el select

    public function mount()
    {
        // 🚨 CAMBIO CLAVE: Solo carga la cartera del usuario autenticado
        $user = Auth::user();
        if ($user) {
            // Busca la cartera donde el user_id coincide con el ID del usuario autenticado.
            $this->carteras = Cartera::where('user_id', $user->id)->get();
        } else {
            // Si no hay usuario autenticado (opcional, para prevenir errores)
            $this->carteras = collect();
        }
    }

    public function rules(): array
    {
        return [
            'nombres' => 'required|string|min:2|max:255',
            'apellidos' => 'required|string|min:2|max:255',
            'identificacion' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clientes', 'identificacion')
                    ->ignore($this->cliente?->id)
                    ->whereNull('deleted_at'),
            ],
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:500',
            'km_referencia' => 'nullable|string|max:50',
            'estado' => ['required', Rule::in(['activo', 'inactivo'])],
            'cartera_id' => 'required|exists:carteras,id',
        ];
    }

    public function messages(): array
    {
        return [
            'identificacion.unique' => 'Esta identificación ya existe.',
            'cartera_id.required' => 'Debe seleccionar una cartera.',
            'cartera_id.exists' => 'La cartera seleccionada no es válida.',
        ];
    }

    public function setCliente(Cliente $cliente): void
    {
        $this->cliente = $cliente;
        $this->nombres = $cliente->nombres;
        $this->apellidos = $cliente->apellidos;
        $this->identificacion = $cliente->identificacion;
        $this->telefono = $cliente->telefono;
        $this->direccion = $cliente->direccion;
        $this->km_referencia = $cliente->km_referencia;
        $this->estado = $cliente->estado;
        $this->cartera_id = $cliente->cartera_id;
    }

    protected function payload(): array
    {
        return $this->only([
            'nombres',
            'apellidos',
            'identificacion',
            'telefono',
            'direccion',
            'km_referencia',
            'estado',
            'cartera_id',
        ]);
    }

    public function store(): void
    {
        $this->validate($this->rules(), $this->messages());
        Cliente::create($this->payload());
        $this->reset();
        $this->estado = 'activo';
        $this->cartera_id = null;
    }

    public function update(): void
    {
        if (! $this->cliente) {
            throw new \RuntimeException('No hay cliente cargado para actualizar.');
        }
        $this->validate($this->rules(), $this->messages());
        $this->cliente->update($this->payload());
    }
}

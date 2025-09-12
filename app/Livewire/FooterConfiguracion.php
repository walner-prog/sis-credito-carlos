<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Auth;

class FooterConfiguracion extends Component
{
    public $config;

    public function mount()
    {
        $this->config = Configuracion::firstOrCreate(
            ['id' => 1],
            [
                'nombre_sistema' => 'CG Sistema',
                'ruc' => null,
                'direccion' => null,
                'telefono' => null,
                'propietario' => null,
            ]
        );
    }

    public function render()
    {
       // Solo mostrar si el usuario tiene rol 'Administrador'
if (!Auth::user() || !Auth::user()->hasRole('Administrador')) {
    return ''; // no renderiza nada
}

        return view('livewire.footer-configuracion', [
            'config' => $this->config
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Configuracion;

class NombreSistema extends Component
{
    public $nombre_sistema;

    public function mount()
    {
        // Tomar siempre la primera configuración
        $config = Configuracion::first();
        $this->nombre_sistema = $config?->nombre_sistema ?? null;
    }

    public function render()
    {
        return view('livewire.nombre-sistema');
    }
}

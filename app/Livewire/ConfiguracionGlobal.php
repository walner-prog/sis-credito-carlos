<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Configuracion;

class ConfiguracionGlobal extends Component
{
    use WithFileUploads;

    public $config;
    public $logoUpload;

    // Propiedades públicas separadas
    public $nombre_sistema;
    public $ruc;
    public $direccion;
    public $telefono;
    public $propietario;
    public $tasa_interes_global;
    public $permite_multicredito;
    public $cuota_frecuencia_default;
    public $unidad_plazo_default;
    public $dias_gracia_primera_cuota;
    public $dias_no_cobrables = [];


    public function mount()
    {
        $this->config = Configuracion::firstOrCreate(['id' => 1], [
            'nombre_sistema'           => 'CG Sistema',
            'ruc'                      => null,
            'direccion'                => null,
            'telefono'                 => null,
            'propietario'              => null,
            'logo'                     => null,
            'tasa_interes_global'      => 20.00,
            'permite_multicredito'     => false,
            'cuota_frecuencia_default' => 'diaria',
            'unidad_plazo_default'     => 'dias',
            'dias_gracia_primera_cuota' => 1,
            'dias_no_cobrables'        => json_encode([]),
        ]);

        // Asignar valores a propiedades públicas
        $this->nombre_sistema          = $this->config->nombre_sistema;
        $this->ruc                      = $this->config->ruc;
        $this->direccion                = $this->config->direccion;
        $this->telefono                 = $this->config->telefono;
        $this->propietario              = $this->config->propietario;
        $this->tasa_interes_global      = $this->config->tasa_interes_global;
        $this->permite_multicredito     = $this->config->permite_multicredito;
        $this->cuota_frecuencia_default = $this->config->cuota_frecuencia_default;
        $this->unidad_plazo_default     = $this->config->unidad_plazo_default;
        $this->dias_gracia_primera_cuota = $this->config->dias_gracia_primera_cuota;
        $this->dias_no_cobrables = $this->config->dias_no_cobrables ? json_decode($this->config->dias_no_cobrables, true) : [];
    }

    public function guardar()
    {
        $this->validate([
            'nombre_sistema'           => 'nullable|string|max:255',
            'ruc'                      => 'nullable|string|max:255',
            'direccion'                => 'nullable|string|max:255',
            'telefono'                 => 'nullable|string|max:255',
            'propietario'              => 'nullable|string|max:255',
            'tasa_interes_global'      => 'required|numeric|min:0',
            'permite_multicredito'     => 'boolean',
            'cuota_frecuencia_default' => 'required|in:diaria,semanal,quincenal,mensual',
            'unidad_plazo_default'     => 'required|in:dias,meses',
            'logoUpload'               => 'nullable|image|max:2048',

        ]);

        // Asignar valores al modelo
        $this->config->nombre_sistema          = $this->nombre_sistema;
        $this->config->ruc                      = $this->ruc;
        $this->config->direccion                = $this->direccion;
        $this->config->telefono                 = $this->telefono;
        $this->config->propietario              = $this->propietario;
        $this->config->tasa_interes_global      = $this->tasa_interes_global;
        $this->config->permite_multicredito     = $this->permite_multicredito;
        $this->config->cuota_frecuencia_default = $this->cuota_frecuencia_default;
        $this->config->unidad_plazo_default     = $this->unidad_plazo_default;
        $this->config->dias_gracia_primera_cuota = $this->dias_gracia_primera_cuota;
        $this->config->dias_no_cobrables        = json_encode($this->dias_no_cobrables);

        // Guardar logo si hay
        if ($this->logoUpload) {
            $this->config->logo = $this->logoUpload->store('config', 'public');
        }

        $this->config->save();

        session()->flash('update', 'Configuración actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.configuracion-global');
    }
}

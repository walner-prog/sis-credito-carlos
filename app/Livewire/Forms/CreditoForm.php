<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Validation\Rule;
use App\Models\Credito;
use App\Models\Cliente;
use App\Models\Configuracion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CreditoCuota;
use Illuminate\Support\Facades\Auth;
use App\Models\Cartera;
use App\Models\User; // Agregado el modelo User

class CreditoForm extends Form
{
    public $cliente_id = null;
    public $user_id = null;
    public $clientes = [];

    public $monto_solicitado = 0;
    public $monto_total = 0;
    public $saldo_pendiente = 0;
    public $tasa_interes = 20.00;
    public $plazo = 24;
    public $unidad_plazo = 'dias';
    public $cuota = 0;
    public $estado = 'activo';
    public $fecha_inicio;
    public $fecha_vencimiento;
    public $cuota_frecuencia = 'diaria';

    public ?Credito $credito = null;

    // ---------------------------------------------
    // Montaje inicial
    // ---------------------------------------------
    public function mount()
    {
        // Si el usuario es administrador, no cargamos clientes para evitar la creación.
        if (Auth::user()->rol === 'admin') {
            $this->clientes = collect();
        } else {
            // Se asegura de que solo se carguen los clientes de la cartera del usuario autenticado
            $cartera = Cartera::where('user_id', Auth::id())->first();
            if ($cartera) {
                $this->clientes = Cliente::where('cartera_id', $cartera->id)->orderBy('nombres')->get();
            } else {
                $this->clientes = collect(); // Colección vacía si el usuario no tiene cartera
            }
        }

        $this->fecha_inicio = Carbon::today()->toDateString();

        if (!$this->credito) {
            $this->cargarConfiguracionGlobal();
        }
    }

    // ---------------------------------------------
    // Cargar configuración global
    // ---------------------------------------------
    public function cargarConfiguracionGlobal()
    {
        $configGlobal = Configuracion::first();
        if ($configGlobal) {
            $this->tasa_interes = $configGlobal->tasa_interes_global;
            $this->cuota_frecuencia = $configGlobal->cuota_frecuencia_default;
            $this->unidad_plazo = $configGlobal->unidad_plazo_default;
        }
        $this->recalcularCredito();
    }

    // ---------------------------------------------
    // Obtener configuración global
    // ---------------------------------------------
    protected function getConfiguracionGlobal()
    {
        return Configuracion::first();
    }

    // ---------------------------------------------
    // Calcular fecha de cuota considerando días de gracia y días no cobrables
    // ---------------------------------------------
   protected function calcularFechaCuota(int $indiceCuota, int $factor): Carbon
{
    $config = $this->getConfiguracionGlobal();
    $fecha = Carbon::parse($this->fecha_inicio);

    // 1️⃣ Aplicar días de gracia inicial
    $diasGracia = $config->dias_gracia_primera_cuota ?? 0;
    $fecha->addDays($diasGracia);

    // 2️⃣ Obtener días no cobrables mapeados
    $mapDias = [
        'lunes' => 'Monday',
        'martes' => 'Tuesday',
        'miércoles' => 'Wednesday',
        'jueves' => 'Thursday',
        'viernes' => 'Friday',
        'sábado' => 'Saturday',
        'domingo' => 'Sunday',
    ];

    $diasNoCobrables = [];
    if ($config && !empty($config->dias_no_cobrables)) {
        $diasNoCobrables = json_decode($config->dias_no_cobrables, true);
        $diasNoCobrables = array_map(fn($dia) => $mapDias[strtolower($dia)] ?? $dia, $diasNoCobrables);
    }

    // 3️⃣ Calcular fecha iterando por cuota
    $cuotasPrevias = $indiceCuota - 1;
    while ($cuotasPrevias > 0) {
        $fecha->addDay();
        if (!in_array($fecha->format('l'), $diasNoCobrables)) {
            $cuotasPrevias--;
        }
    }

    // 4️⃣ Ajustar la fecha si cae en día no cobrable
    while (in_array($fecha->format('l'), $diasNoCobrables)) {
        $fecha->addDay();
    }

    return $fecha;
}


    // ---------------------------------------------
    // Actualización de propiedades
    // ---------------------------------------------
    public function updated($propertyName)
    {
        // Si el usuario es admin, no se permite la actualización de propiedades del formulario
        if (Auth::user()->rol === 'admin') {
            return;
        }

        if (!$this->credito && in_array($propertyName, ['monto_solicitado', 'plazo', 'tasa_interes', 'unidad_plazo'])) {
            $this->recalcularCredito();
        }
    }

    // ---------------------------------------------
    // Recalcular crédito
    // ---------------------------------------------
    protected function recalcularCredito(): void
    {
        if (!$this->monto_solicitado || $this->monto_solicitado <= 0) return;

        $this->monto_total = round(
            $this->monto_solicitado + ($this->monto_solicitado * ($this->tasa_interes / 100)),
            2
        );

        $this->saldo_pendiente = $this->monto_total;

        $factor = $this->getFactorFrecuencia($this->cuota_frecuencia);

        $numCuotas = $this->plazo > 0 ? ceil($this->plazo / $factor) : 1;

        if ($numCuotas > 0) {
            $cuotaBase = round($this->monto_total / $numCuotas, 2);
            $sumaPrimeras = $cuotaBase * ($numCuotas - 1);
            $ultimaCuota = round($this->monto_total - $sumaPrimeras, 2);
            $this->cuota = $numCuotas === 1 ? $this->monto_total : $cuotaBase;
        }

        $this->fecha_vencimiento = $this->calcularFechaVencimiento($this->plazo);
    }

    protected function getFactorFrecuencia($frecuencia): float
    {
        return match ($frecuencia) {
            'diaria' => 1,
            'semanal' => 7,
            'quincenal' => 15,
            'mensual' => 30,
            default => 1,
        };
    }

    protected function calcularFechaVencimiento(int $plazo): string
    {
        return Carbon::parse($this->fecha_inicio ?? Carbon::today())->addDays($plazo)->toDateString();
    }

    public function rules()
    {
        return [
            'cliente_id' => [
                'required',
                'exists:clientes,id',
                function ($attribute, $value, $fail) {
                    $config = $this->getConfiguracionGlobal();
                    if ($config && !$config->permite_multicredito) {
                        $creditoExistente = Credito::where('cliente_id', $value)
                            ->where('estado', 'activo')
                            ->exists();
                        if ($creditoExistente) {
                            $fail('El cliente ya tiene un crédito activo y no se permite multicrédito.');
                        }
                    }
                }
            ],
            'monto_solicitado' => ['required', 'numeric', 'min:1'],
            'tasa_interes' => ['required', 'numeric', 'min:0'],
            'plazo' => ['required', 'integer', 'min:1'],
            'unidad_plazo' => ['required', Rule::in(['dias'])],
        ];
    }

    protected function creationPayload(): array
    {
        return [
            'cliente_id' => $this->cliente_id,
            'user_id' => auth()->id(),
            'monto_solicitado' => $this->monto_solicitado,
            'monto_total' => $this->monto_total,
            'saldo_pendiente' => $this->saldo_pendiente,
            'tasa_interes' => $this->tasa_interes,
            'plazo' => $this->plazo,
            'unidad_plazo' => $this->unidad_plazo,
            'cuota' => $this->cuota,
            'estado' => $this->estado,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'cuota_frecuencia' => $this->cuota_frecuencia,
        ];
    }

    protected function updatePayload(): array
    {
        return [
            'cliente_id' => $this->cliente_id,
            'monto_solicitado' => $this->monto_solicitado,
            'monto_total' => $this->monto_total,
            'saldo_pendiente' => $this->saldo_pendiente,
            'tasa_interes' => $this->tasa_interes,
            'plazo' => $this->plazo,
            'unidad_plazo' => $this->unidad_plazo,
            'cuota' => $this->cuota,
            'estado' => $this->estado,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'cuota_frecuencia' => $this->cuota_frecuencia,
        ];
    }

    // ---------------------------------------------
    // Crear crédito
    // ---------------------------------------------
    
      
   public function store(): Credito
{
    $this->validate();
    $this->fecha_inicio = $this->fecha_inicio ?? Carbon::today()->toDateString();
    $this->recalcularCredito();

    $credito = null;

    DB::transaction(function () use (&$credito) {
        $config = $this->getConfiguracionGlobal();
        if ($config && !$config->permite_multicredito) {
            $creditoExistente = Credito::where('cliente_id', $this->cliente_id)
                ->where('estado', 'activo')
                ->exists();
            if ($creditoExistente) {
                throw new \Exception("El cliente ya tiene un crédito activo y no se permite multicrédito.");
            }
        }

        // Crear crédito base
        $credito = Credito::create($this->creationPayload());
        $this->credito = $credito;

        $factor = $this->getFactorFrecuencia($this->cuota_frecuencia);
        $numCuotas = $this->plazo > 0 ? ceil($this->plazo / $factor) : 1;
        $this->credito->num_cuotas = $numCuotas;
        $this->credito->save();

        $cuotaBase = round($this->monto_total / $numCuotas, 2);
        $sumaPrimeras = $cuotaBase * ($numCuotas - 1);
        $ultimaCuota = round($this->monto_total - $sumaPrimeras, 2);

        $ultimaFecha = null;

        // Inicializamos la fecha de la primera cuota
        $fechaCuota = Carbon::parse($this->fecha_inicio);

        // Aplicar días de gracia inicial
        $diasGracia = $config->dias_gracia_primera_cuota ?? 0;
        $fechaCuota->addDays($diasGracia);

        // Mapear días no cobrables
        $mapDias = [
            'lunes' => 'Monday',
            'martes' => 'Tuesday',
            'miércoles' => 'Wednesday',
            'jueves' => 'Thursday',
            'viernes' => 'Friday',
            'sábado' => 'Saturday',
            'domingo' => 'Sunday',
        ];
        $diasNoCobrables = [];
        if ($config && !empty($config->dias_no_cobrables)) {
            $diasNoCobrables = json_decode($config->dias_no_cobrables, true);
            $diasNoCobrables = array_map(fn($dia) => $mapDias[strtolower($dia)] ?? $dia, $diasNoCobrables);
        }

        for ($i = 1; $i <= $numCuotas; $i++) {
            // Ajustar fecha si cae en día no cobrable
            while (in_array($fechaCuota->format('l'), $diasNoCobrables)) {
                $fechaCuota->addDay();
            }

            $monto = ($i === $numCuotas) ? $ultimaCuota : $cuotaBase;

            CreditoCuota::create([
                'credito_id' => $credito->id,
                'numero_cuota' => $i,
                'monto' => $monto,
                'monto_original' => $monto,
                'fecha_vencimiento' => $fechaCuota->toDateString(),
                'estado' => 'pendiente',
            ]);

            $ultimaFecha = $fechaCuota->copy();

            // Avanzar para la próxima cuota según factor
            $diasSumar = $factor;
            do {
                $fechaCuota->addDay();
                if (!in_array($fechaCuota->format('l'), $diasNoCobrables)) {
                    $diasSumar--;
                }
            } while ($diasSumar > 0);
        }

        // Actualizar fecha de vencimiento del crédito con la última cuota
        if ($ultimaFecha) {
            $credito->fecha_vencimiento = $ultimaFecha->toDateString();
            $credito->save();
        }
    });

    return $credito;
}


    // ---------------------------------------------
    // Actualizar crédito
    // ---------------------------------------------
    public function update(): Credito
    {
        // Se impide la actualización si el usuario es administrador
        if (Auth::user()->rol === 'admin') {
            throw new \Exception("Los administradores no pueden actualizar créditos.");
        }

        if (!$this->credito) throw new \Exception("No hay crédito cargado para actualizar.");

        $this->recalcularCredito();
        $this->credito->update($this->updatePayload());
        $this->recalcularCuotas();

        return $this->credito;
    }

    // ---------------------------------------------
    // Recalcular cuotas existentes
    // ---------------------------------------------
 protected function recalcularCuotas()
{
    if (!$this->credito) return;

    $factor = $this->getFactorFrecuencia($this->cuota_frecuencia);
    $numCuotas = $this->plazo > 0 ? ceil($this->plazo / $factor) : 1;
    $cuotaBase = round($this->monto_total / $numCuotas, 2);
    $sumaPrimeras = $cuotaBase * ($numCuotas - 1);
    $ultimaCuota = round($this->monto_total - $sumaPrimeras, 2);

    // Eliminar cuotas existentes
    $this->credito->cuotas()->delete();

    // Inicializamos la fecha de la primera cuota
    $fechaCuota = Carbon::parse($this->fecha_inicio);

    // Aplicar días de gracia inicial
    $config = $this->getConfiguracionGlobal();
    $diasGracia = $config->dias_gracia_primera_cuota ?? 0;
    $fechaCuota->addDays($diasGracia);

    // Mapear días no cobrables
    $mapDias = [
        'lunes' => 'Monday',
        'martes' => 'Tuesday',
        'miércoles' => 'Wednesday',
        'jueves' => 'Thursday',
        'viernes' => 'Friday',
        'sábado' => 'Saturday',
        'domingo' => 'Sunday',
    ];
    $diasNoCobrables = [];
    if ($config && !empty($config->dias_no_cobrables)) {
        $diasNoCobrables = json_decode($config->dias_no_cobrables, true);
        $diasNoCobrables = array_map(fn($dia) => $mapDias[strtolower($dia)] ?? $dia, $diasNoCobrables);
    }

    $ultimaFecha = null;

    for ($i = 1; $i <= $numCuotas; $i++) {
        // Ajustar fecha si cae en día no cobrable
        while (in_array($fechaCuota->format('l'), $diasNoCobrables)) {
            $fechaCuota->addDay();
        }

        $monto = ($i === $numCuotas) ? $ultimaCuota : $cuotaBase;

        CreditoCuota::create([
            'credito_id' => $this->credito->id,
            'numero_cuota' => $i,
            'monto' => $monto,
            'monto_original' => $monto,
            'fecha_vencimiento' => $fechaCuota->toDateString(),
            'estado' => 'pendiente',
        ]);

        $ultimaFecha = $fechaCuota->copy();

        // Avanzar para la próxima cuota según factor
        $diasSumar = $factor;
        do {
            $fechaCuota->addDay();
            if (!in_array($fechaCuota->format('l'), $diasNoCobrables)) {
                $diasSumar--;
            }
        } while ($diasSumar > 0);
    }

    // Actualizar fecha de vencimiento del crédito con la última cuota
    if ($ultimaFecha) {
        $this->credito->fecha_vencimiento = $ultimaFecha->toDateString();
        $this->credito->num_cuotas = $numCuotas;
        $this->credito->save();
    }
}


    public function setCredito(Credito $credito): void
    {
        $this->credito = $credito;

        $this->cliente_id = $credito->cliente_id;
        $this->monto_solicitado = $credito->monto_solicitado;
        $this->monto_total = $credito->monto_total;
        $this->saldo_pendiente = $credito->saldo_pendiente;
        $this->tasa_interes = $credito->tasa_interes;
        $this->plazo = $credito->plazo;
        $this->unidad_plazo = $credito->unidad_plazo;
        $this->cuota = $credito->cuota;
        $this->estado = $credito->estado;
        $this->fecha_inicio = $credito->fecha_inicio;
        $this->fecha_vencimiento = $credito->fecha_vencimiento;
        $this->cuota_frecuencia = $credito->cuota_frecuencia;
    }
}

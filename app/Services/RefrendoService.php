<?php

namespace App\Services;

use App\Models\Refrendo;
use App\Models\Concesion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RefrendoService
{
    /**
     * Crear un nuevo refrendo para una concesión.
     * Puede ser llamado manualmente o desde ConcesionService al crear una concesión.
     */
    public function crear(array $data): Refrendo
    {
        return DB::transaction(function () use ($data) {

            $concesion = Concesion::with('ultimoRefrendo')
                ->findOrFail($data['id_concesion']);

            $ultimo = $concesion->ultimoRefrendo;

            // 1. Evitar duplicado si ya hay un refrendo pendiente sin vencer
            if ($ultimo && $ultimo->estado === 'pendiente' && ! $ultimo->esta_vencido) {
                throw new \Exception('Ya existe un refrendo pendiente vigente para esta concesión.');
            }

            // 2. Calcular fecha_inicio del nuevo periodo
            $fechaInicio = $ultimo
                ? Carbon::parse($ultimo->fecha_fin)
                : Carbon::parse($concesion->fecha_inicio);

            // 3. fecha_fin = inicio + 1 año
            $fechaFin = $fechaInicio->copy()->addYear();

            // 4. fecha_limite_pago: usa la del usuario o calcula automáticamente
            $fechaLimitePago = isset($data['fecha_limite_pago']) && $data['fecha_limite_pago']
                ? Carbon::parse($data['fecha_limite_pago'])
                : $fechaFin->copy(); // por defecto: igual a fecha_fin

            // 5. Crear refrendo
            $refrendo = Refrendo::create([
                'id_concesion'      => $concesion->id_concesion,
                'tipo_refrendo'     => $data['tipo_refrendo'] ?? 'mantenimiento',
                'fecha_refrendo'    => Carbon::today(),
                'fecha_inicio'      => $fechaInicio,
                'fecha_fin'         => $fechaFin,
                'fecha_limite_pago' => $fechaLimitePago,
                'monto'             => $data['monto'] ?? null,
                'estado'            => 'pendiente',
                'observaciones'     => $data['observaciones'] ?? null,
            ]);

            return $refrendo;
        });
    }

    /**
     * Generar el siguiente refrendo anual de una concesión.
     * Solo se puede generar si el último refrendo ya fue pagado.
     */
    public function generarSiguiente(Concesion $concesion, string $tipo = 'mantenimiento'): Refrendo
    {
        $ultimo = $concesion->ultimoRefrendo;

        if (! $ultimo) {
            throw new \Exception('No existe ningún refrendo previo para esta concesión.');
        }

        if ($ultimo->estado !== 'pagado') {
            throw new \Exception('El último refrendo debe estar pagado antes de generar el siguiente.');
        }

        return $this->crear([
            'id_concesion'  => $concesion->id_concesion,
            'tipo_refrendo' => $tipo,
        ]);
    }
}
<?php

namespace App\Services;

use App\Models\Concesion;
use App\Models\CatEstatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConcesionService
{
    protected RefrendoService $refrendoService;

    public function __construct(RefrendoService $refrendoService)
    {
        $this->refrendoService = $refrendoService;
    }

    /**
     * Crear una nueva concesión con su primer refrendo.
     */
    public function crear(array $data): Concesion
    {
        return DB::transaction(function () use ($data) {

            // 1. Cerrar concesión activa previa del mismo lote
            Concesion::where('id_lote', $data['id_lote'])
                ->whereNull('fecha_fin')
                ->update(['fecha_fin' => Carbon::today()]);

            // 2. Calcular fecha_fin según tipo
            $fechaFin = null;
            if ($data['tipo'] === 'temporal') {
                $fechaFin = Carbon::parse($data['fecha_inicio'])->addYears(7);
            }

            // 3. Estatus inicial: Activa
            $estatus = CatEstatus::where('nombre', 'Activa')->firstOrFail();

            // 4. Crear concesión
            $concesion = Concesion::create([
                'id_lote'          => $data['id_lote'],
                'id_titular'       => $data['id_titular'],
                'id_uso_funerario' => $data['id_uso_funerario'],
                'id_estatus'       => $estatus->id_estatus,
                'tipo'             => $data['tipo'],
                'fecha_inicio'     => $data['fecha_inicio'],
                'fecha_fin'        => $fechaFin,
                'observaciones'    => $data['observaciones'] ?? null,
            ]);

            // 5. Generar primer refrendo (año 1)
            $this->refrendoService->crear([
                'id_concesion'      => $concesion->id_concesion,
                'tipo_refrendo'     => $data['tipo_refrendo'] ?? 'mantenimiento',
                'monto'             => $data['monto'] ?? null,
                'fecha_limite_pago' => $data['fecha_limite_pago'] ?? null,
                'observaciones'     => 'Refrendo inicial generado automáticamente.',
            ]);

            return $concesion->fresh([
                'lote', 'titular', 'usoFunerario', 'estatus', 'refrendos'
            ]);
        });
    }

    /**
     * Actualizar datos de una concesión existente.
     */
    public function actualizar(Concesion $concesion, array $data): Concesion
    {
        return DB::transaction(function () use ($concesion, $data) {

            // Recalcular fecha_fin si cambió el tipo o la fecha_inicio
            $fechaFin = $concesion->fecha_fin;

            if ($data['tipo'] === 'temporal') {
                $fechaFin = Carbon::parse($data['fecha_inicio'])->addYears(7);
            } elseif ($data['tipo'] === 'perpetuidad') {
                $fechaFin = null;
            }

            $concesion->update([
                'id_lote'          => $data['id_lote'],
                'id_titular'       => $data['id_titular'],
                'id_uso_funerario' => $data['id_uso_funerario'],
                'tipo'             => $data['tipo'],
                'fecha_inicio'     => $data['fecha_inicio'],
                'fecha_fin'        => $fechaFin,
                'observaciones'    => $data['observaciones'] ?? null,
            ]);

            return $concesion->fresh();
        });
    }

    /**
     * Cancelar una concesión sin eliminarla.
     */
    public function cancelar(Concesion $concesion, ?string $observaciones = null): Concesion
    {
        return DB::transaction(function () use ($concesion, $observaciones) {

            $estatus = CatEstatus::where('nombre', 'Cancelada')->firstOrFail();

            $concesion->update([
                'id_estatus'    => $estatus->id_estatus,
                'fecha_fin'     => Carbon::today(),
                'observaciones' => $observaciones ?? $concesion->observaciones,
            ]);

            return $concesion->fresh();
        });
    }

    /**
     * Recalcular y actualizar el estatus de una concesión según sus refrendos.
     * Llamar después de cualquier pago o cambio en refrendos.
     */
    public function recalcularEstatus(Concesion $concesion): Concesion
    {
        // Activa y Cancelada son estados terminales asignados explícitamente,
        // este método no debe sobreescribirlos.
        $estatusIntocables = ['Activa', 'Cancelada'];

        if (in_array($concesion->estatus?->nombre, $estatusIntocables)) {
            return $concesion;
        }

        $nombreEstatus = match(true) {
            $concesion->esta_vencida   => 'Inactiva',
            $concesion->anos_en_adeudo >= 1 => 'Con Adeudo',
            default                    => 'Al Corriente',
        };

        $estatus = CatEstatus::where('nombre', $nombreEstatus)->firstOrFail();
        $concesion->update(['id_estatus' => $estatus->id_estatus]);

        return $concesion->fresh(['estatus']);
    }
}
<?php

namespace App\Services;

use App\Models\Concesion;
use App\Models\CatEstatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\RefrendoService;

class ConcesionService
{
    protected $refrendoService;

    public function __construct(RefrendoService $refrendoService)
    {
        $this->refrendoService = $refrendoService;
    }


    /**
     * Crear una nueva concesión
     */
    public function crear(array $data): Concesion
    {
        return DB::transaction(function () use ($data) {

            // 1️⃣ Cerrar concesión activa previa del lote
            Concesion::where('id_lote', $data['id_lote'])
                ->whereNull('fecha_fin')
                ->update([
                    'fecha_fin' => Carbon::now(),
                ]);

            // 2️⃣ Obtener estatus inicial (ej: Activa)
            $estatus = CatEstatus::where('nombre', 'Con Adeudo')
                ->firstOrFail();

            // 3️⃣ Crear concesión
            $concesion = Concesion::create([
                'id_lote'          => $data['id_lote'],
                'id_titular'       => $data['id_titular'],
                'id_uso_funerario' => $data['id_uso_funerario'],
                'id_estatus'       => $estatus->id_estatus,
                'fecha_inicio'     => $data['fecha_inicio'],
                'observaciones'    => $data['observaciones'] ?? null,
            ]);

            // 4️⃣ Crear primer refrendo automáticamente (pendiente)
            $this->refrendoService->crear([
                'id_concesion' => $concesion->id_concesion,
                'monto'        => $data['monto'], 
                'observaciones'=> 'Refrendo inicial generado automáticamente'
            ]);

            return $concesion->fresh([
                'lote',
                'titular',
                'usoFunerario',
                'estatus',
                'refrendos'
            ]);
        });
    }

    /**
     * Actualizar una concesión existente
     */
    public function actualizar(Concesion $concesion, array $data): Concesion
    {
        return DB::transaction(function () use ($concesion, $data) {
            
            // 1️⃣ Actualizar datos principales de la concesión
            $concesion->update([
                'id_lote'          => $data['id_lote'],
                'id_titular'       => $data['id_titular'],
                'id_uso_funerario' => $data['id_uso_funerario'],
                'fecha_inicio'     => $data['fecha_inicio'],
                'observaciones'    => $data['observaciones'] ?? null,
            ]);

            // 2️⃣ Si se envió un monto, actualizamos el refrendo inicial
            // Buscamos el primer refrendo (el que se creó al inicio)
            if (isset($data['monto'])) {
                $primerRefrendo = $concesion->refrendos()->orderBy('created_at', 'asc')->first();
                if ($primerRefrendo) {
                    $primerRefrendo->update([
                        'monto' => $data['monto']
                    ]);
                }
            }

            return $concesion->fresh();
        });
    }
}
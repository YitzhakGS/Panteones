<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Lote;

class LoteService
{
    /**
     * Crea un lote y lo asigna a un espacio físico.
     */
    public function create(array $data): Lote
    {
        return DB::transaction(function () use ($data) {

            // 1️⃣ Crear el lote
            $lote = Lote::create([
                'numero'           => $data['numero'],
                'metros_cuadrados' => $data['metros_cuadrados'] ?? null,

                'col_norte'        => $data['col_norte'] ?? null,
                'col_sur'          => $data['col_sur'] ?? null,
                'col_oriente'      => $data['col_oriente'] ?? null,
                'col_poniente'     => $data['col_poniente'] ?? null,

                'med_norte'        => $data['med_norte'] ?? null,
                'med_sur'          => $data['med_sur'] ?? null,
                'med_oriente'      => $data['med_oriente'] ?? null,
                'med_poniente'     => $data['med_poniente'] ?? null,

                'referencias'      => $data['referencias'] ?? null,
            ]);

            // 2️⃣ Cerrar cualquier asignación activa previa
            $lote->espaciosFisicosLotes()
                ->whereNull('fecha_fin')
                ->update([
                    'fecha_fin' => now(),
                ]);

            // 3️⃣ Asignar el nuevo espacio físico
            $lote->espacioActual()->attach(
                $data['id_espacio_fisico'],
                ['fecha_inicio' => now()]
            );

            // 4️⃣ Retornar el lote actualizado
            return $lote->fresh([
                'espacioActual.cuadrilla.seccion',
                'espacioActual.tipoEspacioFisico'
            ]);
        });
    }
}
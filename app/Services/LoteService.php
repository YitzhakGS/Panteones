<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Lote;

class LoteService
{
    /**
     * Crear lote y asignar espacio físico
     */
    public function create(array $data): Lote
    {
        return DB::transaction(function () use ($data) {

            // 1️⃣ Crear lote
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
                ->update(['fecha_fin' => now()]);

            // 3️⃣ Asignar espacio físico actual
            $lote->espaciosActuales()->attach(
                $data['id_espacio_fisico'],
                ['fecha_inicio' => now()]
            );

            return $lote->fresh([
                'espaciosActuales.cuadrilla.seccion',
                'espaciosActuales.tipoEspacioFisico'
            ]);
        });
    }

    /**
     * Actualizar lote y manejar cambio de espacio físico
     */
    public function update(Lote $lote, array $data): Lote
    {
        return DB::transaction(function () use ($lote, $data) {

            // Actualizar datos del lote
            $lote->update([
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

            // Manejo del cambio de espacio físico
            if (!empty($data['id_espacio_fisico'])) {

                $espacioActual = $lote->espaciosActuales()->first();

                if (!$espacioActual || 
                    $espacioActual->id_espacio_fisico != $data['id_espacio_fisico']) {

                    // Cerrar asignación actual
                    $lote->espaciosFisicosLotes()
                        ->whereNull('fecha_fin')
                        ->update(['fecha_fin' => now()]);

                    // Asignar nueva
                    $lote->espaciosActuales()->attach(
                        $data['id_espacio_fisico'],
                        ['fecha_inicio' => now()]
                    );
                }
            }

            return $lote->fresh([
                'espaciosActuales.cuadrilla.seccion',
                'espaciosActuales.tipoEspacioFisico'
            ]);
        });
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\EspacioFisicoLote;
use App\Models\Lote;
use App\Models\EspacioFisico;

class EspacioFisicoLoteController extends Controller
{
    /**
     * Asigna un lote a un espacio físico.
     *
     * Cierra cualquier asignación vigente previa del lote.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_lote' => 'required|exists:lotes,id_lote',
            'id_espacio_fisico' => 'required|exists:espacios_fisicos,id_espacio_fisico',
            'fecha_inicio' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request) {

            $fechaInicio = $request->fecha_inicio ?? now();

            // 1️⃣ Cerrar asignación vigente (si existe)
            EspacioFisicoLote::where('id_lote', $request->id_lote)
                ->whereNull('fecha_fin')
                ->update([
                    'fecha_fin' => $fechaInicio,
                ]);

            // 2️⃣ Crear nueva asignación
            EspacioFisicoLote::create([
                'id_lote' => $request->id_lote,
                'id_espacio_fisico' => $request->id_espacio_fisico,
                'fecha_inicio' => $fechaInicio,
            ]);
        });

        return back()->with('success', 'Lote asignado correctamente al espacio físico.');
    }

    /**
     * Finaliza la asignación de un lote (lo deja sin espacio físico).
     */
    public function destroy(EspacioFisicoLote $espacioFisicoLote): RedirectResponse
    {
        $espacioFisicoLote->update([
            'fecha_fin' => now(),
        ]);

        return back()->with('success', 'Asignación del lote finalizada correctamente.');
    }

    /**
     * Reasigna un lote a otro espacio físico.
     *
     * Es un alias semántico de store(), por claridad.
     */
    public function reassign(Request $request): RedirectResponse
    {
        return $this->store($request);
    }
}
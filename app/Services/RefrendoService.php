<?php

namespace App\Services;

use App\Models\Refrendo;
use App\Models\Concesion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CatEstatus;

class RefrendoService
{
    /**
     * Crear refrendo anual (queda en estado pendiente)
     */
    public function crear(array $data): Refrendo
    {
        return DB::transaction(function () use ($data) {

            $concesion = Concesion::with('ultimoRefrendo')
                ->findOrFail($data['id_concesion']);

            $ultimo = $concesion->ultimoRefrendo;

            // 🚨 1️⃣ Evitar crear otro si el último está pendiente
            if ($ultimo && $ultimo->estado === 'pendiente') {
                throw new \Exception('Ya existe un refrendo pendiente para esta concesión.');
            }

            // 2️⃣ Determinar inicio del periodo
            if ($ultimo) {
                $periodoInicio = Carbon::parse($ultimo->periodo_fin);
            } else {
                $periodoInicio = Carbon::parse($concesion->fecha_inicio);
            }

            // 3️⃣ Calcular fin (+1 año)
            $periodoFin = $periodoInicio->copy()->addYear();

            // 4️⃣ Crear refrendo en estado pendiente
            $refrendo = Refrendo::create([
                'id_concesion'   => $concesion->id_concesion,
                'fecha_refrendo' => now(),
                'periodo_inicio' => $periodoInicio,
                'periodo_fin'    => $periodoFin,
                'monto'          => $data['monto'] ?? null,
                'estado'         => 'pendiente',
                'observaciones'  => $data['observaciones'] ?? null,
            ]);

            // Al haber un refrendo pendiente, la concesión pasa a 'Con Adeudo'
            $estatusAdeudo = CatEstatus::where('nombre', 'Con Adeudo')->first();
            if ($estatusAdeudo) {
                $concesion->update(['id_estatus' => $estatusAdeudo->id_estatus]);
            }

            return $refrendo;
        });
    }
}
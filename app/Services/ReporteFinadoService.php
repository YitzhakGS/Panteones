<?php

namespace App\Services;

use App\Models\MovimientoFinado;
use App\Models\Concesion;
use Illuminate\Support\Facades\DB;

class ReporteFinadoService
{
    // =========================================================
    // 📊 REPORTE 1: EXHUMACIONES
    // =========================================================
    public function reporteExhumaciones()
    {
        return MovimientoFinado::query()
            ->with(['finado', 'concesion', 'concesionOrigen'])
            ->where('tipo', 'exhumacion')
            ->orderBy('fecha')
            ->get()
            ->map(function ($m) {

                $finado = $m->finado;

                return [
                    'dia' => $m->fecha->format('d'),
                    'mes' => ucfirst($m->fecha->translatedFormat('F')),
                    'anio' => $m->fecha->format('Y'),

                    'nombre_finado' => trim(
                        "{$finado->nombre} {$finado->apellido_paterno} {$finado->apellido_materno}"
                    ),

                    // 🔥 ubicación anterior
                    'ubicacion_panteon' => $this->formatearUbicacion($m->concesionOrigen),

                    'fecha_defuncion' => optional($finado->fecha_defuncion)->format('d/m/Y'),

                    'solicitante' => $m->solicitante,

                    // 🔥 ubicación nueva
                    'ubicacion_nueva' => $m->ubicacion_destino_externa
                        ?? $this->formatearUbicacion($m->concesion),
                ];
            });
    }

    // =========================================================
    // 📊 REPORTE 2: CONCESIONES / REFRENDO
    // =========================================================
    public function reporteConcesiones()
    {
        return Concesion::query()
            ->with(['movimientos.finado'])
            ->get()
            ->map(function ($c) {

                $movimientos = $c->movimientos;

                $finados = $movimientos
                    ->pluck('finado')
                    ->filter()
                    ->unique('id_finado');

                return [
                    'nombre_contribuyente' => $c->nombre_contribuyente,

                    'numero_lote' => $this->formatearUbicacion($c),

                    'fecha_refrendo' => optional($c->fecha_refrendo)->format('d/m/Y'),

                    'nombres_occisos' => $finados
                        ->map(fn($f) => trim("{$f->nombre} {$f->apellido_paterno} {$f->apellido_materno}"))
                        ->implode(', '),

                    'fecha_inhumacion' => optional(
                        $movimientos
                            ->whereIn('tipo', ['inhumacion', 'reinhumacion'])
                            ->sortByDesc('fecha')
                            ->first()
                    )->fecha?->format('d/m/Y'),

                    'exhumacion' => $movimientos->contains('tipo', 'exhumacion') ? 'SI' : 'NO',

                    'reinhumacion' => $movimientos->contains('tipo', 'reinhumacion') ? 'SI' : 'NO',

                    'construccion' => $finados
                        ->pluck('tipo_construccion')
                        ->filter()
                        ->unique()
                        ->implode(', ') ?: null,
                ];
            });
    }

    // =========================================================
    // 🧱 HELPER: FORMATEAR UBICACIÓN
    // =========================================================
    private function formatearUbicacion($concesion)
    {
        if (!$concesion) {
            return null;
        }

        // 🔥 Ajusta esto a tus campos reales
        return trim(
            "{$concesion->seccion}, Área Núm. {$concesion->area}, {$concesion->tipo_espacio} Núm. {$concesion->numero}"
        );
    }
}
<?php

namespace App\Services;

use App\Models\MovimientoFinado;
use App\Models\Concesion;
use App\Services\FinadoService;

class ReporteFinadoService
{
    public function __construct(protected FinadoService $finadoService) {}

    // =========================================================
    // REPORTE 1: EXHUMACIONES
    // =========================================================
    public function reporteExhumaciones(array $filtros = [])
    {
        return MovimientoFinado::query()
            ->with(['finado'])
            ->whereIn('tipo', ['exhumacion', 'movimiento', 'reinhumacion'])
            ->when(
                !empty($filtros['fecha_inicio']),
                fn($q) => $q->where('fecha', '>=', $filtros['fecha_inicio'])
            )
            ->when(
                !empty($filtros['fecha_fin']),
                fn($q) => $q->where('fecha', '<=', $filtros['fecha_fin'])
            )
            ->orderBy('fecha')
            ->get()
            ->map(function ($m) {
                $finado = $m->finado;

                // ubicacion_anterior = de dónde salió
                // ubicacion_actual o ubicacion_externa = a dónde fue
                $ubicacionNueva = $m->es_externo
                    ? $m->ubicacion_externa
                    : $m->ubicacion_actual;

                return [
                    'dia'               => $m->fecha->format('d'),
                    'mes'               => ucfirst($m->fecha->translatedFormat('F')),
                    'anio'              => $m->fecha->format('Y'),
                    'nombre_finado'     => trim("{$finado->nombre} {$finado->apellido_paterno} {$finado->apellido_materno}"),
                    'ubicacion_panteon' => $m->ubicacion_anterior ?? '—',
                    'fecha_defuncion'   => optional($finado->fecha_defuncion)->format('d/m/Y') ?? '—',
                    'solicitante'       => $m->solicitante ?? '—',
                    'ubicacion_nueva'   => $ubicacionNueva ?? '—',
                ];
            });
    }

    // =========================================================
    // REPORTE 2: CONCESIONES / REFRENDO
    // =========================================================
    public function reporteConcesiones(array $filtros = [])
    {
        // Tomamos movimientos de inhumación agrupados por concesión
        $query = MovimientoFinado::query()
            ->with(['finado', 'ubicacionActual.titular', 'ubicacionActual.lote.espaciosActuales.seccion', 'ubicacionActual.lote.espaciosActuales.tipoEspacioFisico'])
            ->whereIn('tipo', ['inhumacion', 'reinhumacion'])
            ->when(
                !empty($filtros['fecha_inicio']),
                fn($q) => $q->where('fecha', '>=', $filtros['fecha_inicio'])
            )
            ->when(
                !empty($filtros['fecha_fin']),
                fn($q) => $q->where('fecha', '<=', $filtros['fecha_fin'])
            )
            ->orderBy('fecha')
            ->get();

        // Agrupar por concesión
        return $query
            ->groupBy('id_ubicacion_actual')
            ->map(function ($movimientos) {
                $concesion  = $movimientos->first()->ubicacionActual;
                $titular    = $concesion?->titular;

                // Todos los finados inhumados en esta concesión
                $finados = $movimientos
                    ->pluck('finado')
                    ->filter()
                    ->unique('id_finado');

                // Movimientos de exhumación y reinhumación para esta concesión
                $todosMovimientos = MovimientoFinado::where('id_ubicacion_actual', $movimientos->first()->id_ubicacion_actual)
                    ->get();

                $tieneExhumacion   = $todosMovimientos->contains('tipo', 'exhumacion');
                $tieneReinhumacion = $todosMovimientos->contains('tipo', 'reinhumacion');

                return [
                    'nombre_contribuyente' => $titular?->familia ?? '—',
                    'numero_lote'          => $movimientos->first()->ubicacion_actual ?? '—',
                    'fecha_refrendo'       => optional($concesion?->ultimoRefrendo?->fecha_refrendo)->format('d/m/Y') ?? '—',
                    'nombres_occisos'      => $finados
                        ->map(fn($f) => trim("{$f->nombre} {$f->apellido_paterno} {$f->apellido_materno}"))
                        ->implode(', '),
                    'fecha_inhumacion'     => optional($movimientos->sortByDesc('fecha')->first()->fecha)->format('d/m/Y') ?? '—',
                    'exhumacion'           => $tieneExhumacion   ? 'SI' : '',
                    'reinhumacion'         => $tieneReinhumacion ? 'SI' : '',
                    'construccion'         => $finados->pluck('tipo_construccion')->filter()->unique()->implode(', ') ?: '—',
                ];
            })
            ->values();
    }
}
<?php

namespace App\Services;

use App\Models\MovimientoFinado;
use App\Services\FinadoService;
use Illuminate\Pagination\LengthAwarePaginator;

class ReporteFinadoService
{
    public function __construct(protected FinadoService $finadoService) {}

    // =========================================================
    // REPORTE 1: EXHUMACIONES (CON PAGINACIÓN)
    // =========================================================
    public function reporteExhumaciones(array $filtros = [])
    {
        $query = MovimientoFinado::query()
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
            ->orderBy('fecha');

        $paginado = $query->paginate(16);

        $paginado->getCollection()->transform(function ($m) {
            $finado = $m->finado;

            $ubicacionNueva = $m->es_externo
                ? $m->ubicacion_externa
                : $m->ubicacion_actual;

            return [
                'dia'               => $m->fecha->format('d'),
                'mes'               => ucfirst($m->fecha->translatedFormat('F')),
                'anio'              => $m->fecha->format('Y'),
                'nombre_finado'     => $finado
                    ? trim("{$finado->nombre} {$finado->apellido_paterno} {$finado->apellido_materno}")
                    : '— Finado eliminado —',
                'ubicacion_panteon' => $m->ubicacion_anterior ?? '—',
                'fecha_defuncion'   => optional($finado?->fecha_defuncion)->format('d/m/Y') ?? '—',
                'solicitante'       => $m->solicitante ?? '—',
                'ubicacion_nueva'   => $ubicacionNueva ?? '—',
            ];
        });

        return $paginado;
    }

    public function reporteConcesiones(array $filtros = [])
    {
        $query = MovimientoFinado::query()
            ->with([
                'finado',
                'ubicacionActual'        => fn($q) => $q->withTrashed(),
                'ubicacionActual.titular' => fn($q) => $q->withTrashed(),
                'ubicacionActual.lote'   => fn($q) => $q->withTrashed(),
                'ubicacionActual.lote.espaciosActuales.seccion'           => fn($q) => $q->withTrashed(),
                'ubicacionActual.lote.espaciosActuales.tipoEspacioFisico' => fn($q) => $q->withTrashed(),
            ])
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

        $coleccion = $query
            ->groupBy('id_ubicacion_actual')
            ->map(function ($movimientos) {

                $primero   = $movimientos->first();
                $concesion = $primero?->ubicacionActual;
                $titular   = $concesion?->titular;

                $finados = $movimientos
                    ->pluck('finado')
                    ->filter()
                    ->unique('id_finado');

                $todosMovimientos = MovimientoFinado::where(
                    'id_ubicacion_actual',
                    $primero->id_ubicacion_actual
                )->get();

                $tieneExhumacion   = $todosMovimientos->contains('tipo', 'exhumacion');
                $tieneReinhumacion = $todosMovimientos->contains('tipo', 'reinhumacion');

                return [
                    'nombre_contribuyente' => $titular?->familia ?? '— Titular eliminado —',
                    'numero_lote'          => $primero->ubicacion_actual ?? '—',
                    'fecha_refrendo'       => optional($concesion?->ultimoRefrendo?->fecha_refrendo)->format('d/m/Y') ?? '—',
                    'nombres_occisos'      => $finados
                        ->map(fn($f) => trim("{$f->nombre} {$f->apellido_paterno} {$f->apellido_materno}"))
                        ->implode(', '),
                    'fecha_inhumacion'     => optional(
                        $movimientos->sortByDesc('fecha')->first()?->fecha
                    )->format('d/m/Y') ?? '—',
                    'exhumacion'           => $tieneExhumacion   ? 'SI' : '',
                    'reinhumacion'         => $tieneReinhumacion ? 'SI' : '',
                    'construccion'         => $finados->pluck('tipo_construccion')->filter()->unique()->implode(', ') ?: '—',
                ];
            })
            ->values();

        $perPage = request()->get('per_page', 16);
        $page    = request()->get('page', 1);

        $items = $coleccion->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $coleccion->count(),
            $perPage,
            $page,
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}
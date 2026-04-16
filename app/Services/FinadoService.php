<?php

namespace App\Services;

use App\Models\Finado;
use App\Models\Concesion;
use App\Models\MovimientoFinado;
use App\Models\Lote;
use Exception;

class FinadoService
{
    protected LoteService $loteService;

    public function __construct(LoteService $loteService)
    {
        $this->loteService = $loteService;
    }

    // -------------------------
    // Helper — genera texto snapshot de una concesión
    // -------------------------
    public function formatearUbicacion(Concesion $concesion): string
    {
        $lote    = $concesion->lote;
        $espacio = $lote?->espaciosActuales->first();
        $seccion = optional($espacio?->seccion)->nombre ?? '—';
        $tipo    = optional($espacio?->tipoEspacioFisico)->nombre ?? '';
        $nombre  = $espacio?->nombre ?? '';
        $numero  = $lote?->numero ?? '—';

        return trim("{$seccion}, {$tipo} {$nombre}, Lote {$numero}");
    }

    // -------------------------
    // Helper — ubicación actual texto
    // -------------------------
    private function ubicacionActualTexto(Finado $finado): ?string
    {
        return $finado->ultimoMovimiento?->ubicacion_actual;
    }

    // -------------------------
    // INHUMAR
    // -------------------------
    public function inhumar(Finado $finado, int $idConcesion, array $data = [])
    {
        if ($this->obtenerEstadoActual($finado) === 'inhumado') {
            throw new Exception('El finado ya está inhumado');
        }

        $concesion = Concesion::with([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ])->findOrFail($idConcesion);

        return MovimientoFinado::create([
            'id_finado'          => $finado->id_finado,
            'id_ubicacion_actual'=> $idConcesion,
            'ubicacion_actual'   => $this->formatearUbicacion($concesion),
            'ubicacion_anterior' => null,
            'tipo'               => 'inhumacion',
            'fecha'              => $data['fecha'] ?? now(),
            'solicitante'        => $data['solicitante'] ?? null,
            'observaciones'      => $data['observaciones'] ?? null,
            'es_misma_ubicacion' => false,
            'es_externo'         => false,
            'ubicacion_externa'  => null,
        ]);
    }

    // -------------------------
    // EXHUMAR
    // -------------------------
    public function exhumar(Finado $finado, array $data = [])
    {
        if ($this->obtenerEstadoActual($finado) !== 'inhumado') {
            throw new Exception('El finado no está inhumado');
        }

        return MovimientoFinado::create([
            'id_finado'          => $finado->id_finado,
            'id_ubicacion_actual'=> null,
            'ubicacion_actual'   => $data['ubicacion_externa'] ?? null,
            'ubicacion_anterior' => $this->ubicacionActualTexto($finado),
            'tipo'               => 'exhumacion',
            'fecha'              => $data['fecha'] ?? now(),
            'solicitante'        => $data['solicitante'] ?? null,
            'observaciones'      => $data['observaciones'] ?? null,
            'es_misma_ubicacion' => false,
            'es_externo'         => (bool) ($data['es_externo'] ?? false),
            'ubicacion_externa'  => $data['ubicacion_externa'] ?? null,
        ]);
    }

    // -------------------------
    // REINHUMAR
    // -------------------------
    public function reinhumar(Finado $finado, int $idConcesion, array $data = [])
    {
        if ($this->obtenerEstadoActual($finado) !== 'exhumado') {
            throw new Exception('El finado debe estar exhumado');
        }

        $concesion = Concesion::with([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ])->findOrFail($idConcesion);

        return MovimientoFinado::create([
            'id_finado'          => $finado->id_finado,
            'id_ubicacion_actual'=> $idConcesion,
            'ubicacion_actual'   => $this->formatearUbicacion($concesion),
            'ubicacion_anterior' => $this->ubicacionActualTexto($finado),
            'tipo'               => 'reinhumacion',
            'fecha'              => $data['fecha'] ?? now(),
            'solicitante'        => $data['solicitante'] ?? null,
            'observaciones'      => $data['observaciones'] ?? null,
            'es_misma_ubicacion' => false,
            'es_externo'         => false,
            'ubicacion_externa'  => null,
        ]);
    }

    // -------------------------
    // MOVER
    // -------------------------
    public function mover(Finado $finado, int $idConcesion, array $data = [])
    {

        $idConcesion = $data['id_ubicacion_actual'] ?? null;

        if (!$idConcesion) {
            throw new Exception('No se proporcionó la concesión actual');
        }

        if ($this->obtenerEstadoActual($finado) !== 'inhumado') {
            throw new Exception('El finado debe estar inhumado para moverlo');
        }

        // 1️⃣ Guardar ubicación anterior (TEXTO)
        $ubicacionAnterior = $this->ubicacionActualTexto($finado);

        // 2️⃣ Actualizar lote (si aplica)
        if (!empty($data['id_lote'])) {

            $lote = \App\Models\Lote::findOrFail($data['id_lote']);

            if ($lote) {
                app(\App\Services\LoteService::class)->update($lote, [
                    'numero'           => $data['numero'] ?? $lote->numero,
                    'metros_cuadrados' => $data['metros_cuadrados'] ?? $lote->metros_cuadrados,

                    'col_norte'        => $data['col_norte'] ?? null,
                    'col_sur'          => $data['col_sur'] ?? null,
                    'col_oriente'      => $data['col_oriente'] ?? null,
                    'col_poniente'     => $data['col_poniente'] ?? null,

                    'med_norte'        => $data['med_norte'] ?? null,
                    'med_sur'          => $data['med_sur'] ?? null,
                    'med_oriente'      => $data['med_oriente'] ?? null,
                    'med_poniente'     => $data['med_poniente'] ?? null,

                    'referencias'      => $data['referencias'] ?? null,

                    'id_espacio_fisico'=> $data['id_espacio_fisico'] ?? null,
                ]);
            }
        }

        // 3️⃣ Volver a cargar concesión con datos actualizados
        $concesion = Concesion::with([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ])->findOrFail($idConcesion);

        // 4️⃣ Generar nueva ubicación (TEXTO)
        $ubicacionNueva = $this->formatearUbicacion($concesion);

        // 5️⃣ Guardar movimiento
        return MovimientoFinado::create([
            'id_finado'          => $finado->id_finado,
            'id_ubicacion_actual'=> $idConcesion,
            'ubicacion_actual'   => $ubicacionNueva,
            'ubicacion_anterior' => $ubicacionAnterior,
            'tipo'               => 'movimiento',
            'fecha'              => $data['fecha'] ?? now(),
            'solicitante'        => $data['solicitante'] ?? null,
            'observaciones'      => $data['observaciones'] ?? null,
            'es_misma_ubicacion' => false,
            'es_externo'         => false,
            'ubicacion_externa'  => null,
        ]);
    }

    // -------------------------
    // ESTADO ACTUAL
    // -------------------------
    public function obtenerEstadoActual(Finado $finado): string
    {
        $ultimo = $finado->movimientos()
            ->latest('fecha')
            ->latest('id_movimiento')
            ->first();

        if (!$ultimo) {
            return 'sin_movimientos';
        }

        return match ($ultimo->tipo) {
            'inhumacion', 'reinhumacion', 'movimiento' => 'inhumado',
            'exhumacion'                               => 'exhumado',
            default                                    => 'desconocido',
        };
    }
}
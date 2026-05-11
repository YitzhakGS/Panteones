<?php

namespace App\Services;

use App\Models\Finado;
use App\Models\Concesion;
use App\Models\MovimientoFinado;
use Exception;

class FinadoService
{
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
    // Helper — texto del último movimiento
    // -------------------------
    private function ubicacionAnteriorTexto(Finado $finado): ?string
    {
        return $finado->ultimoMovimiento?->ubicacion_actual;
    }

    // -------------------------
    // INHUMAR — registro inicial
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
            'id_finado'           => $finado->id_finado,
            'id_ubicacion_actual' => $idConcesion,
            'ubicacion_actual'    => $this->formatearUbicacion($concesion),
            'ubicacion_anterior'  => null,
            'tipo'                => 'inhumacion',
            'fecha'               => $data['fecha'] ?? now(),
            'solicitante'         => $data['solicitante'] ?? null,
            'observaciones'       => $data['observaciones'] ?? null,
            'es_misma_ubicacion'  => false,
            'es_externo'          => false,
            'ubicacion_externa'   => null,
        ]);
    }

    // -------------------------
    // MOVER A OTRO LOTE
    // Modifica la concesión para que apunte al nuevo lote
    // y registra el movimiento
    // -------------------------
    public function moverALote(Finado $finado, int $idConcesion, int $idNuevoLote, array $data = [])
    {
        if ($this->obtenerEstadoActual($finado) !== 'inhumado') {
            throw new Exception('El finado debe estar inhumado para moverlo');
        }

        // 1️⃣ Ubicación anterior desde el snapshot del último movimiento
        $ubicacionAnterior = $this->ubicacionAnteriorTexto($finado);

        // 2️⃣ Cargar concesión actual
        $concesion = Concesion::with([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ])->findOrFail($idConcesion);

        // 3️⃣ Verificar que el lote destino sea diferente al actual
        if ($concesion->id_lote == $idNuevoLote) {
            throw new Exception('El lote destino es el mismo que el actual');
        }

        // 4️⃣ Actualizar la concesión con el nuevo lote
        $concesion->update(['id_lote' => $idNuevoLote]);

        // 5️⃣ Recargar con nuevo lote
        $concesion->load([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ]);

        $ubicacionNueva = $this->formatearUbicacion($concesion);

        // 6️⃣ Registrar movimiento
        return MovimientoFinado::create([
            'id_finado'           => $finado->id_finado,
            'id_ubicacion_actual' => $idConcesion,
            'ubicacion_actual'    => $ubicacionNueva,
            'ubicacion_anterior'  => $ubicacionAnterior,
            'tipo'                => 'movimiento',
            'fecha'               => $data['fecha'] ?? now(),
            'solicitante'         => $data['solicitante'] ?? null,
            'observaciones'       => $data['observaciones'] ?? null,
            'es_misma_ubicacion'  => false,
            'es_externo'          => false,
            'ubicacion_externa'   => null,
        ]);
    }

    // -------------------------
    // MOVER A MISMA UBICACIÓN
    // No cambia la concesión ni el lote, solo registra el movimiento
    // -------------------------
    public function moverMismaUbicacion(Finado $finado, int $idConcesion, array $data = [])
    {
        if ($this->obtenerEstadoActual($finado) !== 'inhumado') {
            throw new Exception('El finado debe estar inhumado');
        }

        $concesion = Concesion::with([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ])->findOrFail($idConcesion);

        $ubicacionTexto = $this->formatearUbicacion($concesion);

        return MovimientoFinado::create([
            'id_finado'           => $finado->id_finado,
            'id_ubicacion_actual' => $idConcesion,
            'ubicacion_actual'    => $ubicacionTexto,
            'ubicacion_anterior'  => $this->ubicacionAnteriorTexto($finado),
            'tipo'                => 'movimiento',
            'fecha'               => $data['fecha'] ?? now(),
            'solicitante'         => $data['solicitante'] ?? null,
            'observaciones'       => $data['observaciones'] ?? null,
            'es_misma_ubicacion'  => true,
            'es_externo'          => false,
            'ubicacion_externa'   => null,
        ]);
    }

    // -------------------------
    // EXHUMAR / EXTERNO
    // id_ubicacion_actual queda nulo, ubicacion_actual es texto libre
    // -------------------------
    public function exhumar(Finado $finado, array $data = [])
    {
        if ($this->obtenerEstadoActual($finado) !== 'inhumado') {
            throw new Exception('El finado no está inhumado');
        }

        return MovimientoFinado::create([
            'id_finado'           => $finado->id_finado,
            'id_ubicacion_actual' => null, // ← sin concesión registrada
            'ubicacion_actual'    => $data['ubicacion_externa'] ?? null,
            'ubicacion_anterior'  => $this->ubicacionAnteriorTexto($finado),
            'tipo'                => 'exhumacion',
            'fecha'               => $data['fecha'] ?? now(),
            'solicitante'         => $data['solicitante'] ?? null,
            'observaciones'       => $data['observaciones'] ?? null,
            'es_misma_ubicacion'  => false,
            'es_externo'          => (bool) ($data['es_externo'] ?? false),
            'ubicacion_externa'   => $data['ubicacion_externa'] ?? null,
        ]);
    }

    // -------------------------
    // REINHUMAR — después de exhumación
    // -------------------------
    public function reinhumar(Finado $finado, int $idConcesion, array $data = [])
    {
        if ($this->obtenerEstadoActual($finado) !== 'exhumado') {
            throw new Exception('El finado debe estar exhumado para reinhumarlo');
        }

        $concesion = Concesion::with([
            'lote.espaciosActuales.seccion',
            'lote.espaciosActuales.tipoEspacioFisico',
        ])->findOrFail($idConcesion);

        return MovimientoFinado::create([
            'id_finado'           => $finado->id_finado,
            'id_ubicacion_actual' => $idConcesion,
            'ubicacion_actual'    => $this->formatearUbicacion($concesion),
            'ubicacion_anterior'  => $this->ubicacionAnteriorTexto($finado),
            'tipo'                => 'reinhumacion',
            'fecha'               => $data['fecha'] ?? now(),
            'solicitante'         => $data['solicitante'] ?? null,
            'observaciones'       => $data['observaciones'] ?? null,
            'es_misma_ubicacion'  => false,
            'es_externo'          => false,
            'ubicacion_externa'   => null,
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
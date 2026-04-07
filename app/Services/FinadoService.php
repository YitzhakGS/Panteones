<?php

namespace App\Services;

use App\Models\Finado;
use App\Models\MovimientoFinado;
use Exception;

class FinadoService
{
    // -------------------------
    // INHUMAR
    // -------------------------
    public function inhumar(int $idFinado, int $idConcesion, array $data = [])
    {
        $finado = Finado::findOrFail($idFinado);

        $estado = $this->obtenerEstadoActual($finado);

        if ($estado === 'inhumado') {
            throw new Exception('El finado ya está inhumado');
        }

        return MovimientoFinado::create([
            'id_finado' => $idFinado,
            'id_concesion' => $idConcesion,
            'id_concesion_origen' => null, // 🔥 inicio

            'tipo' => 'inhumacion',
            'fecha' => $data['fecha'] ?? now(),
            'observaciones' => $data['observaciones'] ?? null,

            // 🔥 nuevos
            'es_misma_ubicacion' => false,
            'ubicacion_destino_externa' => null,
            'solicitante' => $data['solicitante'] ?? null,
        ]);
    }

    // -------------------------
    // EXHUMAR
    // -------------------------
    public function exhumar(int $idFinado, array $data = [])
    {
        $finado = Finado::findOrFail($idFinado);

        $estado = $this->obtenerEstadoActual($finado);

        if ($estado !== 'inhumado') {
            throw new Exception('El finado no está inhumado');
        }

        $actual = $finado->ultimoMovimiento?->id_concesion;

        if (!$actual) {
            throw new Exception('No se pudo determinar la ubicación actual');
        }

        return MovimientoFinado::create([
            'id_finado' => $idFinado,

            // 🔥 destino puede ser null (cremación, salida, etc.)
            'id_concesion' => $data['id_concesion'] ?? null,
            'id_concesion_origen' => $actual,

            'tipo' => 'exhumacion',
            'fecha' => $data['fecha'] ?? now(),
            'observaciones' => $data['observaciones'] ?? null,

            // 🔥 nuevos
            'es_misma_ubicacion' => $data['es_misma_ubicacion'] ?? false,
            'ubicacion_destino_externa' => $data['ubicacion_destino_externa'] ?? null,
            'solicitante' => $data['solicitante'] ?? null,
        ]);
    }

    // -------------------------
    // REINHUMAR
    // -------------------------
    public function reinhumar(int $idFinado, int $idConcesion, array $data = [])
    {
        $finado = Finado::findOrFail($idFinado);

        $estado = $this->obtenerEstadoActual($finado);

        if ($estado !== 'exhumado') {
            throw new Exception('El finado debe estar exhumado para reinhumar');
        }

        $actual = $finado->ultimoMovimiento?->id_concesion_origen
            ?? $finado->ultimoMovimiento?->id_concesion;

        return MovimientoFinado::create([
            'id_finado' => $idFinado,
            'id_concesion' => $idConcesion,
            'id_concesion_origen' => $actual,

            'tipo' => 'reinhumacion',
            'fecha' => $data['fecha'] ?? now(),
            'observaciones' => $data['observaciones'] ?? null,

            // 🔥 nuevos
            'es_misma_ubicacion' => $data['es_misma_ubicacion'] ?? false,
            'ubicacion_destino_externa' => null,
            'solicitante' => $data['solicitante'] ?? null,
        ]);
    }

    // -------------------------
    // ESTADO ACTUAL
    // -------------------------
    public function obtenerEstadoActual(Finado $finado): string
    {
        $ultimo = $finado->movimientos()
            ->latest('fecha')
            ->latest('id_movimiento') // 🔥 evita empates
            ->first();

        if (!$ultimo) {
            return 'sin_movimientos';
        }

        return match ($ultimo->tipo) {
            'inhumacion', 'reinhumacion' => 'inhumado',
            'exhumacion' => 'exhumado',
            default => 'desconocido',
        };
    }
}
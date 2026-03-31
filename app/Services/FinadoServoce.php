<?php

namespace App\Services;

use App\Models\Finado;
use App\Models\MovimientoFinado;
use Illuminate\Support\Facades\DB;
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

        if (!isset($data['id_concesion'])) {
            throw new Exception('Se requiere id_concesion para exhumar');
        }

        return MovimientoFinado::create([
            'id_finado'     => $idFinado,
            'id_concesion'  => $idConcesion,
            'tipo'          => 'inhumacion',
            'fecha'         => $data['fecha'] ?? now(),
            'observaciones' => $data['observaciones'] ?? null,
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
        
        if (!isset($data['id_concesion'])) {
            throw new Exception('Se requiere id_concesion para exhumar');
        }

        return MovimientoFinado::create([
            'id_finado'     => $idFinado,
            'id_concesion'  => $data['id_concesion'],
            'tipo'          => 'exhumacion',
            'fecha'         => $data['fecha'] ?? now(),
            'observaciones' => $data['observaciones'] ?? null,
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

        if (!isset($data['id_concesion'])) {
            throw new Exception('Se requiere id_concesion para exhumar');
        }

        return MovimientoFinado::create([
            'id_finado'     => $idFinado,
            'id_concesion'  => $idConcesion,
            'tipo'          => 'reinhumacion',
            'fecha'         => $data['fecha'] ?? now(),
            'observaciones' => $data['observaciones'] ?? null,
        ]);
    }

    // -------------------------
    // ESTADO ACTUAL
    // -------------------------
    public function obtenerEstadoActual(Finado $finado): string
    {
        $ultimo = $finado->movimientos()
            ->latest('fecha')
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



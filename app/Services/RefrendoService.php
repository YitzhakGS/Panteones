<?php

namespace App\Services;

use App\Models\Refrendo;
use App\Models\Concesion;
use App\Models\ConfigGlobal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RefrendoService
{
    // -------------------------------------------------------------------------
    // Método privado — centraliza la decisión de fecha límite
    // -------------------------------------------------------------------------

    private function calcularFechaLimite(Carbon $fechaFin, ?string $fechaManual): Carbon
    {
        // Si viene fecha manual explícita desde el formulario, siempre respetarla
        if ($fechaManual) {
            return Carbon::parse($fechaManual);
        }

        $modo = ConfigGlobal::get('modo_fecha_limite', 'global');

        return match($modo) {
            // Usa la fecha global guardada en config, o fecha_fin como fallback
            'global' => Carbon::parse(
                ConfigGlobal::get('fecha_limite_pago') ?? $fechaFin
            ),
            // Cada concesión calcula su propia fecha desde su fecha_fin
            'por_concesion' => $fechaFin->copy(),
            default => $fechaFin->copy(),
        };
    }

    // -------------------------------------------------------------------------
    // Crear refrendo
    // -------------------------------------------------------------------------

    public function crear(array $data): Refrendo
    {
        return DB::transaction(function () use ($data) {

            $concesion = Concesion::with('ultimoRefrendo')
                ->findOrFail($data['id_concesion']);

            $ultimo = $concesion->ultimoRefrendo;

            if ($ultimo && $ultimo->estado === 'pendiente' && ! $ultimo->esta_vencido) {
                throw new \Exception('Ya existe un refrendo pendiente vigente para esta concesión.');
            }

            $fechaInicio = $ultimo
                ? Carbon::parse($ultimo->fecha_fin)
                : Carbon::parse($concesion->fecha_inicio);

            $fechaFin = $fechaInicio->copy()->addYear();

            // ← Antes eran 4 líneas de lógica inline, ahora una sola llamada
            $fechaLimitePago = $this->calcularFechaLimite(
                $fechaFin,
                $data['fecha_limite_pago'] ?? null
            );

            $refrendo = Refrendo::create([
                'id_concesion'      => $concesion->id_concesion,
                'tipo_refrendo'     => $data['tipo_refrendo'] ?? 'mantenimiento',
                'fecha_refrendo'    => Carbon::today(),
                'fecha_inicio'      => $fechaInicio,
                'fecha_fin'         => $fechaFin,
                'fecha_limite_pago' => $fechaLimitePago,
                'monto'             => $data['monto'] ?? null,
                'estado'            => 'pendiente',
                'observaciones'     => $data['observaciones'] ?? null,
            ]);

            return $refrendo;
        });
    }

    // -------------------------------------------------------------------------
    // Generar siguiente refrendo anual
    // -------------------------------------------------------------------------

    public function generarSiguiente(Concesion $concesion, string $tipo = 'mantenimiento'): Refrendo
    {
        $ultimo = $concesion->ultimoRefrendo;

        if (! $ultimo) {
            throw new \Exception('No existe ningún refrendo previo para esta concesión.');
        }

        if ($ultimo->estado !== 'pagado') {
            throw new \Exception('El último refrendo debe estar pagado antes de generar el siguiente.');
        }

        return $this->crear([
            'id_concesion'  => $concesion->id_concesion,
            'tipo_refrendo' => $tipo,
        ]);
    }

    // -------------------------------------------------------------------------
    // Fecha límite global
    // -------------------------------------------------------------------------

    public function setFechaLimiteGlobal(string $fecha): int
    {
        return DB::transaction(function () use ($fecha) {

            ConfigGlobal::set('fecha_limite_pago', $fecha);

            $actualizados = Refrendo::where('estado', 'pendiente')
                ->update(['fecha_limite_pago' => $fecha]);

            return $actualizados;
        });
    }
}

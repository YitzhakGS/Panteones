<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Refrendo;
use App\Models\CatEstatus;
use Illuminate\Support\Facades\DB;
use Exception;

class PagoService
{
    /**
     * Registra un pago y dispara las actualizaciones de estado en cascada.
     */
    public function registrar(array $data): Pago
    {
        return DB::transaction(function () use ($data) {

            // 1. CARGA Y BLOQUEO: Buscamos el refrendo y bloqueamos la fila para evitar "Race Conditions"
            $refrendo = Refrendo::with('concesion')->lockForUpdate()->findOrFail($data['id_refrendo']);

            // 2. REGLA DE NEGOCIO: No pagar algo ya pagado o cancelado
            if ($refrendo->estado === 'pagado') {
                throw new Exception('Operación inválida: El refrendo ya cuenta con un pago registrado.');
            }

            if ($refrendo->estado === 'cancelado') {
                throw new Exception('Operación inválida: No se puede pagar un refrendo que ha sido cancelado.');
            }

            // 3. REGLA DE NEGOCIO: Validar que el monto no sea cero (opcional según tu política)
            if ($data['monto'] <= 0) {
                throw new Exception('El monto del pago debe ser mayor a cero.');
            }

            // 4. CREAR EL REGISTRO DE PAGO
            $pago = Pago::create([
                'id_refrendo'  => $refrendo->id_refrendo,
                'fecha_pago'   => $data['fecha_pago'] ?? now(),
                'folio_ticket' => $data['folio_ticket'] ?? null,
                'monto'        => $data['monto'],
                'forma_pago'   => $data['forma_pago'] ?? 'Efectivo',
                'observaciones'=> $data['observaciones'] ?? null,
            ]);

            // 5. ACTUALIZAR ESTADO DEL REFRENDO
            // El refrendo cambia a 'pagado'
            $refrendo->update([
                'estado' => 'pagado'
            ]);

            // Si no hay más refrendos 'pendiente', la ponemos 'Al Corriente'
            $tieneMasAdeudos = Refrendo::where('id_concesion', $refrendo->id_concesion)
                ->where('estado', 'pendiente')
                ->exists();

            if (!$tieneMasAdeudos) {
                $estatusSano = CatEstatus::where('nombre', 'Al Corriente')->first();
                if ($estatusSano) {
                    $refrendo->concesion->update(['id_estatus' => $estatusSano->id_estatus]);
                }
            }

            return $pago;
        });
    }
}
<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Refrendo;
use App\Models\CatEstatus;
use Illuminate\Support\Facades\DB;
use Exception;

class PagoService
{
    public function __construct(protected ConcesionService $concesionService, protected RefrendoService $refrendoService) {}

    public function registrar(array $data): Pago
    {
        return DB::transaction(function () use ($data) {

            $refrendo = Refrendo::with('concesion.estatus')
                ->lockForUpdate()
                ->findOrFail($data['id_refrendo']);

            if ($refrendo->estado === 'pagado') {
                throw new Exception('El refrendo ya cuenta con un pago registrado.');
            }

            if ($refrendo->estado === 'cancelado') {
                throw new Exception('No se puede pagar un refrendo cancelado.');
            }

            if ($data['monto'] <= 0) {
                throw new Exception('El monto del pago debe ser mayor a cero.');
            }

            if ($data['monto'] < $refrendo->monto) {
                throw new Exception('El pago no cubre el monto total del refrendo.');
            }

            $pago = Pago::create([
                'id_refrendo'   => $refrendo->id_refrendo,
                'fecha_pago'    => $data['fecha_pago'] ?? now(),
                'folio_ticket'  => $data['folio_ticket'] ?? null,
                'monto'         => $data['monto'],
                'forma_pago'    => $data['forma_pago'] ?? 'Efectivo',
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $refrendo->update(['estado' => 'pagado']);


            // Generar automáticamente el siguiente refrendo anual
            $this->refrendoService->generarSiguiente($refrendo->concesion); // 👈 agregar esto

            // Delegar el recálculo de estatus al ConcesionService
            $this->concesionService->recalcularEstatus($refrendo->concesion);

            return $pago;
        });
    }


    public function actualizar(array $data): Pago
    {
        return DB::transaction(function () use ($data) {

            $pago = Pago::with('refrendo.concesion.estatus')
                ->lockForUpdate()
                ->findOrFail($data['id_pago']);

            $refrendo = $pago->refrendo;

            if ($refrendo->estado === 'cancelado') {
                throw new Exception('No se puede editar un pago de un refrendo cancelado.');
            }

            if ($data['monto'] <= 0) {
                throw new Exception('El monto debe ser mayor a cero.');
            }

            if ($data['monto'] < $refrendo->monto) {
                throw new Exception('El monto no cubre el total del refrendo.');
            }

            $pago->update([
                'fecha_pago'    => $data['fecha_pago'],
                'folio_ticket'  => $data['folio_ticket'] ?? null,
                'monto'         => $data['monto'],
                'forma_pago'    => $data['forma_pago'] ?? 'Efectivo',
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            return $pago;
        });
    }
}
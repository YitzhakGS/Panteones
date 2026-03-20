<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Refrendo;
use App\Models\CatEstatus;
use Illuminate\Support\Facades\DB;
use Exception;

class PagoService
{
    public function __construct(protected ConcesionService $concesionService) {}

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

            $pago = Pago::create([
                'id_refrendo'   => $refrendo->id_refrendo,
                'fecha_pago'    => $data['fecha_pago'] ?? now(),
                'folio_ticket'  => $data['folio_ticket'] ?? null,
                'monto'         => $data['monto'],
                'forma_pago'    => $data['forma_pago'] ?? 'Efectivo',
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $refrendo->update(['estado' => 'pagado']);

            // Delegar el recálculo de estatus al ConcesionService
            $this->concesionService->recalcularEstatus($refrendo->concesion);

            return $pago;
        });
    }
}
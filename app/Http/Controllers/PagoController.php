<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PagoService;
use Illuminate\Http\RedirectResponse;

class PagoController extends Controller
{
    /**
     * Registrar pago de un refrendo
     */
    public function store(Request $request, PagoService $pagoService): RedirectResponse
    {
        $refrendo = \App\Models\Refrendo::findOrFail($request->id_refrendo);

        $request->validate([
            'id_refrendo'   => 'required|exists:refrendos,id_refrendo',
            'fecha_pago'    => 'required|date',
            'monto'         => 'required|numeric|min:0',
            'folio_ticket'  => 'nullable|string|max:100',
            'forma_pago'    => 'nullable|string|max:100',
            'observaciones' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($request, $refrendo) {
                    if ((float) $request->monto > (float) $refrendo->monto && empty($value)) {
                        $fail('Las observaciones son obligatorias cuando el monto supera el del refrendo.');
                    }
                },
            ],
        ]);

        try {
            $pagoService->registrar($request->all());
            return redirect()->route('refrendos.index')->with('success', 'Pago registrado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $pagoId): RedirectResponse
    {
        $request->validate([
            'fecha_pago'   => 'required|date',
            'monto'        => 'required|numeric|min:0',
            'folio_ticket' => 'nullable|string|max:100',
            'forma_pago'   => 'nullable|string|max:100',
            'observaciones'=> 'nullable|string'
        ]);

        try {
            $pago = \App\Models\Pago::findOrFail($pagoId);

            $pago->update([
                'fecha_pago'   => $request->fecha_pago,
                'monto'        => $request->monto,
                'folio_ticket' => $request->folio_ticket,
                'forma_pago'   => $request->forma_pago,
                'observaciones'=> $request->observaciones,
            ]);

            return redirect()->route('refrendos.index')->with('success', 'Pago actualizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
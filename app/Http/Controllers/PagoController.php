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
        $request->validate([
            'id_refrendo' => 'required|exists:refrendos,id_refrendo',
            'fecha_pago'  => 'required|date',
            'monto'       => 'required|numeric|min:0',
            'folio_ticket'=> 'nullable|string|max:100',
            'forma_pago'  => 'nullable|string|max:100',
            'observaciones'=> 'nullable|string'
        ]);

        try {
            $pagoService->registrar($request->all());

            return back()->with('success', 'Pago registrado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Refrendo;
use App\Models\Concesion;
use App\Services\RefrendoService;

class RefrendoController extends Controller
{
    /**
     * Listado de refrendos
     */
    public function index(): View
    {
        $refrendos = Refrendo::with([
            'concesion.lote',
            'concesion.titular'
        ])
        ->orderBy('fecha_refrendo', 'desc')
        ->paginate(15);

        return view('refrendos.index', compact('refrendos'));
    }

    /**
     * Formulario para crear refrendo
     */
    public function create(): View
    {
        return view('refrendos.create', [
            'concesiones' => Concesion::with('lote', 'titular')
                ->whereNull('fecha_fin')
                ->get()
        ]);
    }

    /**
     * Guardar refrendo
     */
    public function store(Request $request, RefrendoService $service)
    {
        $request->validate([
            'id_concesion' => 'required|exists:concesiones,id_concesion',
            'monto'        => 'nullable|numeric|min:0',
            'observaciones'=> 'nullable|string',
        ]);

        $service->crear($request->all());

        return redirect()
            ->route('refrendos.index')
            ->with('success', 'Refrendo anual registrado correctamente.');
    }

    /**
     * Ver refrendo
     */
    public function show(Refrendo $refrendo): View
    {
        $refrendo->load('concesion.lote', 'concesion.titular');

        return view('refrendos.show', compact('refrendo'));
    }
}
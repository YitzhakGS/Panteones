<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Lote;
use App\Models\EspacioFisico;
use App\Models\CatSeccion; // Cambiado de CatCuadrilla a CatSeccion
use App\Services\LoteService;

class LotesController extends Controller
{
    /**
     * Muestra un listado de todos los lotes.
     */
    public function index(Request $request): View
    {
        $query = Lote::with([
            'espaciosActuales.seccion',
            'espaciosActuales.tipoEspacioFisico'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%$search%")
                ->orWhere('ubicacion', 'like', "%$search%")
                ->orWhere('colindancias', 'like', "%$search%");
            });
        }

        $lotes = $query
            ->orderBy('id_lote', 'desc')
            ->paginate(10)
            ->withQueryString();

        $secciones = CatSeccion::orderBy('nombre')->get();

        return view('lotes.index', compact('lotes', 'secciones'));
    }

    public function create(): View
    {
        return view('lotes.create');
    }

    public function store(Request $request, LoteService $loteService): RedirectResponse
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'id_espacio_fisico' => 'required|exists:espacios_fisicos,id_espacio_fisico',
        ]);

        $loteService->create($request->all());

        return redirect()
            ->route('lotes.index')
            ->with('success', 'Lote creado correctamente.');
    }

    public function show(Lote $lote): View
    {
        $lote->load(['espaciosFisicosLotes.espacioFisico.seccion']);

        return view('lotes.show', compact('lote'));
    }

    public function edit(Lote $lote): View
    {
        $lote->load([
            'espaciosActuales.seccion',
            'espaciosActuales.tipoEspacioFisico'
        ]);

        return view('lotes.edit', compact('lote'));
    }

    public function update(Request $request, Lote $lote, LoteService $loteService): RedirectResponse
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'id_espacio_fisico' => 'required|exists:espacios_fisicos,id_espacio_fisico',
            // ... resto de validaciones se mantienen igual
        ]);

        $loteService->update($lote, $request->all());

        return redirect()
            ->route('lotes.index')
            ->with('success', 'Lote actualizado correctamente.');
    }

    public function destroy(Lote $lote): RedirectResponse
    {
        $lote->delete();
        return redirect()->route('lotes.index')->with('success', 'Lote eliminado correctamente.');
    }

    /**
     * Devuelve la información de un lote en formato JSON.
     */
    public function getData($lote)
    {
        $lote = \App\Models\Lote::with([
            'espaciosActuales.seccion',
            'espaciosActuales.tipoEspacioFisico'
        ])->findOrFail($lote);

        $espacio = $lote->espaciosActuales->first();

        return response()->json([
            'numero' => $lote->numero,
            'metros_cuadrados' => $lote->metros_cuadrados,

            'med_norte' => $lote->med_norte,
            'med_sur' => $lote->med_sur,
            'med_oriente' => $lote->med_oriente,
            'med_poniente' => $lote->med_poniente,

            'col_norte' => $lote->col_norte,
            'col_sur' => $lote->col_sur,
            'col_oriente' => $lote->col_oriente,
            'col_poniente' => $lote->col_poniente,

            'referencias' => $lote->referencias,

            'id_seccion' => $espacio->seccion->id_seccion ?? null,
            'id_espacio_fisico' => $espacio->id_espacio_fisico ?? null,

            'nombre_seccion' => $espacio->seccion->nombre ?? null,
            'tipo_espacio' => $espacio->tipoEspacioFisico->nombre ?? null,
            'nombre_espacio' => $espacio->nombre ?? null
        ]);
    }


}
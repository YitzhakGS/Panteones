<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Concesion;
use App\Models\Lote;
use App\Models\Titular;
use App\Models\CatUsoFunerario;
use App\Models\CatEstatus;
use App\Services\ConcesionService;

class ConcesionController extends Controller
{
    /**
     * Listado de concesiones
     */
    public function index(): View
    {
        $concesiones = Concesion::with([
            'lote',
            'titular',
            'usoFunerario',
            'estatus',
            'ultimoRefrendo'
        ])
        ->orderBy('fecha_inicio', 'desc')
        ->paginate(15);

        // ESTO FALTABA
        $lotes     = Lote::orderBy('numero')->get();
        $titulares = Titular::orderBy('familia')->get();
        $usos      = CatUsoFunerario::orderBy('nombre')->get();
        $estatus   = CatEstatus::orderBy('nombre')->get();

        return view('concesiones.index', compact(
            'concesiones',
            'lotes',
            'titulares',
            'usos',
            'estatus'
        ));
    }

    /**
     * Formulario crear concesión
     */
    public function create(): View
    {
        return view('concesiones.create', [
            'lotes'        => Lote::orderBy('numero')->get(),
            'titulares'    => Titular::orderBy('familia')->get(),
            'usos'         => CatUsoFunerario::orderBy('nombre')->get(),
            'estatus'      => CatEstatus::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Guardar concesión
     */

    public function store(Request $request, ConcesionService $concesionService)
    {
        $request->validate([
            'id_lote'          => 'required|exists:lotes,id_lote',
            'id_titular'       => 'required|exists:titulares,id_titular',
            'id_uso_funerario' => 'required|exists:uso_funerario,id_uso_funerario',
            'fecha_inicio'     => 'required|date',
            'monto'            => 'nullable|numeric|min:0', 
            'observaciones'    => 'nullable|string',
        ]);

        $concesionService->crear($request->all());

        return redirect()
            ->route('concesiones.index')
            ->with('success', 'Concesión creada correctamente con refrendo inicial pendiente.');
    }

    /**
     * Ver concesión
     */
    public function show(Concesion $concesion)
    {
        // Cargamos las relaciones y también el monto del último refrendo si existe
        $concesion->load([
            'lote',
            'titular',
            'usoFunerario',
            'estatus',
            'ultimoRefrendo'
        ]);

        // SI LA PETICIÓN ES AJAX (como la de JavaScript), devolvemos JSON
        if (request()->ajax() || request()->wantsJson()) {
            // En tu ConcesionController.php, dentro del método show:

            return response()->json([
                'id_concesion'     => $concesion->id_concesion,
                'id_lote'          => $concesion->id_lote,
                'id_titular'       => $concesion->id_titular,
                'id_uso_funerario' => $concesion->id_uso_funerario,
                // Forzamos el formato ISO que requiere el input date
                'fecha_inicio'     => \Carbon\Carbon::parse($concesion->fecha_inicio)->format('Y-m-d'),
                'observaciones'    => $concesion->observaciones,
                'monto'            => $concesion->ultimoRefrendo ? $concesion->ultimoRefrendo->monto : 0,
            ]);
        }

        // Si es una petición normal de navegador, sigue devolviendo la vista
        return view('concesiones.show', compact('concesion'));
    }

    /**
     * Editar concesión
     */
    public function edit(Concesion $concesion): View
    {
        return view('concesiones.edit', [
            'concesion' => $concesion,
            'lotes' => Lote::all(),
            'titulares' => Titular::all(),
            'usos' => CatUsoFunerario::all()
        ]);
    }

    /**
     * Actualizar concesión
     */
    public function update(Request $request, Concesion $concesion, ConcesionService $concesionService): RedirectResponse
    {
        // 1. Validar (Asegúrate de que los nombres coincidan con el 'name' de tus inputs)
        $request->validate([
            'id_lote'          => 'required|exists:lotes,id_lote',
            'id_titular'       => 'required|exists:titulares,id_titular',
            'id_uso_funerario' => 'required|exists:uso_funerario,id_uso_funerario',
            'fecha_inicio'     => 'required|date',
            'monto'            => 'nullable|numeric|min:0',
            'observaciones'    => 'nullable|string',
        ]);

        // 2. Llamar al Service para procesar la actualización
        $concesionService->actualizar($concesion, $request->all());

        return redirect()
            ->route('concesiones.index')
            ->with('success', 'Concesión actualizada correctamente.');
    }

    /**
     * Eliminar (soft delete)
     */
    public function destroy(Concesion $concesion): RedirectResponse
    {
        $concesion->delete();

        return redirect()
            ->route('concesiones.index')
            ->with('success', 'Concesión eliminada.');
    }
}
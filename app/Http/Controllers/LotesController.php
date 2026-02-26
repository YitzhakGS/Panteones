<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Lote;
use App\Models\EspacioFisico;
use App\Services\LoteService;
use App\Models\CatCuadrilla;
/**
 * Controlador encargado de la gestión del catálogo de lotes.+
 *
 * Maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * relacionadas con los lotes del sistema.
 */
class LotesController extends Controller
{
    /**
     * Muestra un listado de todos los lotes.
     *
     * @return View
     */
    public function index(): View
    {
        // 1. Obtenemos los lotes para la tabla principal
        $lotes = Lote::with([
            'espaciosActuales.cuadrilla.seccion',
            'espaciosActuales.tipoEspacioFisico'
        ])
        ->orderBy('id_lote', 'desc')
        ->paginate(10);

        // 2. Obtenemos las CUADRILLAS para el primer select del modal
        // Cargamos la relación 'seccion' para poder mostrar "[Sección] - [Cuadrilla]"
        $cuadrillas = CatCuadrilla::with('seccion')
            ->orderBy('nombre')
            ->get();

        // 3. Pasamos las variables a la vista
        // Nota: He quitado 'espaciosFisicos' porque ahora los cargaremos por AJAX
        return view('lotes.index', compact('lotes', 'cuadrillas'));
    }

    /**
     * Muestra el formulario para crear un nuevo lote.
     *
     * @return View
     */
    public function create(): View
    {
        return view('lotes.create');
    }

    /**
     * Almacena un nuevo lote en la base de datos.
     *
     * @param Request $request
     * @return RedirectResponse
     */
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

    /**
     * Muestra el detalle de un lote específico.
     *
     * Incluye los espacios físicos asociados (histórico).
     *
     * @param Lote $lote
     * @return View
     */
    public function show(Lote $lote): View
    {
        $lote->load([
            'espaciosFisicos'
        ]);

        return view('lotes.show', compact('lote'));
    }

    /**
     * Muestra el formulario para editar un lote existente.
     *
     * @param Lote $lote
     * @return View
     */
    public function edit(Lote $lote): View
    {
        $lote->load([
            'espaciosActuales.cuadrilla.seccion',
            'espaciosActuales.tipoEspacioFisico'
        ]);

        return view('lotes.edit', compact('lote'));
    }

    /**
     * Actualiza los datos de un lote existente.
     *
     * @param Request $request
     * @param Lote $lote
     * @return RedirectResponse
     */
    public function update(Request $request, Lote $lote): RedirectResponse
    {
        $request->validate([
            'numero'            => 'required|string|max:50',
            'metros_cuadrados'  => 'nullable|numeric|min:0',

            'col_norte'         => 'nullable|string|max:255',
            'col_sur'           => 'nullable|string|max:255',
            'col_oriente'       => 'nullable|string|max:255',
            'col_poniente'      => 'nullable|string|max:255',

            'med_norte'         => 'nullable|numeric|min:0',
            'med_sur'           => 'nullable|numeric|min:0',
            'med_oriente'       => 'nullable|numeric|min:0',
            'med_poniente'      => 'nullable|numeric|min:0',

            'referencias'       => 'nullable|string',
        ]);

        $lote->update($request->only([
            'numero',
            'metros_cuadrados',
            'col_norte',
            'col_sur',
            'col_oriente',
            'col_poniente',
            'med_norte',
            'med_sur',
            'med_oriente',
            'med_poniente',
            'referencias',
        ]));

        return redirect()
            ->route('lotes.index')
            ->with('success', 'Lote actualizado correctamente.');
    }

    /**
     * Elimina un lote del sistema (Soft Delete).
     *
     * @param Lote $lote
     * @return RedirectResponse
     */
    public function destroy(Lote $lote): RedirectResponse
    {
        $lote->delete();

        return redirect()
            ->route('lotes.index')
            ->with('success', 'Lote eliminado correctamente.');
    }

    /**
     * Devuelve la información de un lote en formato JSON.
     *
     * @param Lote $lote
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Lote $lote)
    {
        // Cargar la relación real, no el accessor
        $lote->load([
            'espaciosActuales.cuadrilla.seccion',
            'espaciosActuales.tipoEspacioFisico'
        ]);

        return response()->json([
            'id_lote'           => $lote->id_lote,
            'numero'            => $lote->numero,
            'metros_cuadrados'  => $lote->metros_cuadrados,

            'med_norte'         => $lote->med_norte,
            'med_sur'           => $lote->med_sur,
            'med_oriente'       => $lote->med_oriente,
            'med_poniente'      => $lote->med_poniente,

            'col_norte'         => $lote->col_norte,
            'col_sur'           => $lote->col_sur,
            'col_oriente'       => $lote->col_oriente,
            'col_poniente'      => $lote->col_poniente,

            'referencias'       => $lote->referencias,

            'id_cuadrilla'      => $lote->espacioActual ? $lote->espacioActual->id_cuadrilla : null,
            'id_espacio_fisico' => $lote->espacioActual ? $lote->espacioActual->id_espacio_fisico : null,

            'ubicacion'         => $lote->ubicacion_formateada ?? 'Sin ubicación asignada'
        ]);
    }    

    public function getEspaciosByCuadrilla($id_cuadrilla)
    {
        // 1. Usamos 'tipoEspacioFisico' que es el nombre de tu función en el modelo
        $espacios = \App\Models\EspacioFisico::with('tipoEspacioFisico') 
            ->where('id_cuadrilla', $id_cuadrilla)
            ->orderBy('nombre', 'asc')
            ->get();

        $resultado = $espacios->map(function($espacio) {
            return [
                'id' => $espacio->id_espacio_fisico,
                'nombre' => $espacio->nombre,
                // 2. IMPORTANTE: Accedemos al objeto de la relación. 
                // Verifica si el campo en la tabla de tipos se llama 'nombre' o 'descripcion'
                'tipo' => $espacio->tipoEspacioFisico ? $espacio->tipoEspacioFisico->nombre : 'Sin Tipo'
            ];
        });

        return response()->json($resultado);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatCuadrilla;
use App\Models\CatSeccion;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador encargado de la gestión del catálogo de cuadrillas.
 *
 * Maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * relacionadas con las cuadrillas del sistema.
 */
class CuadrillasController extends Controller
{
    /**
     * Muestra un listado de todas las cuadrillas.
     *
     * Carga las relaciones:
     * - sección
     * - espacios físicos asociados
     *
     * @return View
     */
    public function index(): View
    {
        $secciones = CatSeccion::orderBy('nombre')->get();
        $cuadrillas = CatCuadrilla::with(['seccion', 'espaciosFisicos'])->get();

        return view('cuadrillas.index', compact('cuadrillas', 'secciones'));
    }

    /**
     * Muestra el formulario para crear una nueva cuadrilla.
     *
     * @return View
     */
    public function create(): View
    {
        $secciones = CatSeccion::all();

        return view('cuadrillas.create', compact('secciones'));
    }

    /**
     * Almacena una nueva cuadrilla en la base de datos.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_seccion' => 'required|exists:cat_secciones,id_seccion',
            'nombre'     => 'required|string|max:255',
        ]);

        CatCuadrilla::create($request->only([
            'id_seccion',
            'nombre'
        ]));

        return redirect()
            ->route('cuadrillas.index')
            ->with('success', 'Cuadrilla creada correctamente.');
    }

    /**
     * Muestra el detalle de una cuadrilla específica.
     *
     * @param CatCuadrilla $cuadrilla
     * @return View
     */
    public function show(CatCuadrilla $cuadrilla): View
    {
        $cuadrilla->load(['seccion', 'espaciosFisicos']);

        return view('cuadrillas.show', compact('cuadrilla'));
    }

    /**
     * Muestra el formulario para editar una cuadrilla existente.
     *
     * @param CatCuadrilla $cuadrilla
     * @return View
     */
    public function edit(CatCuadrilla $cuadrilla): View
    {
        $secciones = CatSeccion::all();

        return view('cuadrillas.edit', compact('cuadrilla', 'secciones'));
    }

    /**
     * Actualiza los datos de una cuadrilla existente.
     *
     * @param Request $request
     * @param CatCuadrilla $cuadrilla
     * @return RedirectResponse
     */
    public function update(Request $request, CatCuadrilla $cuadrilla): RedirectResponse
    {
        $request->validate([
            'id_seccion' => 'required|exists:cat_secciones,id_seccion',
            'nombre'     => 'required|string|max:255',
        ]);

        $cuadrilla->update($request->only([
            'id_seccion',
            'nombre'
        ]));

        return redirect()
            ->route('cuadrillas.index')
            ->with('success', 'Cuadrilla actualizada correctamente.');
    }

    /**
     * Elimina una cuadrilla del sistema (Soft Delete).
     *
     * @param CatCuadrilla $cuadrilla
     * @return RedirectResponse
     */
    public function destroy(CatCuadrilla $cuadrilla): RedirectResponse
    {
        $cuadrilla->delete();

        return redirect()
            ->route('cuadrillas.index')
            ->with('success', 'Cuadrilla eliminada correctamente.');
    }

    /**
     * Devuelve la información de una cuadrilla en formato JSON.
     *
     * Útil para peticiones AJAX o consumo vía API.
     *
     * @param CatCuadrilla $cuadrilla
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(CatCuadrilla $cuadrilla)
    {
        return response()->json(
            $cuadrilla->load(['seccion', 'espaciosFisicos'])
        );
    }
}

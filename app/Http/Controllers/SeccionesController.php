<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatSeccion;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador encargado de la gestión del catálogo de secciones.
 *
 * Maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * relacionadas con las secciones del sistema.
 */
class SeccionesController extends Controller
{
    /**
     * Muestra un listado de todas las secciones.
     *
     * Carga las relaciones:
     * - cuadrillas
     * - espacios físicos asociados a cada cuadrilla
     *
     * @return View
     */
    public function index(): View
    {
        $secciones = CatSeccion::with('cuadrillas.espaciosFisicos')->get();
        return view('secciones.index', compact('secciones'));
    }

    /**
     * Muestra el formulario para crear una nueva sección.
     *
     * @return View
     */
    public function create(): View
    {
        return view('secciones.create');
    }

    /**
     * Almacena una nueva sección en la base de datos.
     *
     * Valida que el campo "nombre" sea obligatorio antes de guardar.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required'
        ]);

        CatSeccion::create($request->all());

        return redirect()
            ->route('secciones.index')
            ->with('success', 'Sección creada correctamente.');
    }

    /**
     * Muestra el detalle de una sección específica.
     *
     * Utiliza Route Model Binding para obtener la sección.
     *
     * @param CatSeccion $seccion
     * @return View
     */
    public function show(CatSeccion $seccion): View
    {
        return view('secciones.show', compact('seccion'));
    }

    /**
     * Muestra el formulario para editar una sección existente.
     *
     * @param CatSeccion $seccion
     * @return View
     */
    public function edit(CatSeccion $seccion): View
    {
        return view('secciones.edit', compact('seccion'));
    }

    /**
     * Actualiza los datos de una sección existente.
     *
     * Valida que el campo "nombre" sea obligatorio.
     *
     * @param Request $request
     * @param CatSeccion $seccion
     * @return RedirectResponse
     */
    public function update(Request $request, CatSeccion $seccion): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required'
        ]);

        $seccion->update($request->only('nombre'));

        return redirect()
            ->route('secciones.index')
            ->with('success', 'Sección actualizada correctamente.');
    }

    /**
     * Elimina una sección del sistema.
     *
     * @param CatSeccion $seccion
     * @return RedirectResponse
     */
    public function destroy(CatSeccion $seccion): RedirectResponse
    {
        $seccion->delete();

        return redirect()
            ->route('secciones.index')
            ->with('success', 'Sección eliminada correctamente.');
    }

    /**
     * Devuelve la información de una sección en formato JSON.
     *
     * Útil para peticiones AJAX o consumo vía API.
     *
     * @param CatSeccion $seccion
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(CatSeccion $seccion)
    {
        return response()->json($seccion);
    }
}

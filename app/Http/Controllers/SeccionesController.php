<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatSeccion;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador encargado de la gestión del catálogo de secciones.
 */
class SeccionesController extends Controller
{
    /**
     * Muestra un listado de todas las secciones.
     *
     * Carga la relación directa:
     * - espaciosFisicos (Cambiado de cuadrillas.espaciosFisicos)
     */
    public function index(): View
    {
        // Cargamos directamente los espacios físicos asociados
        $secciones = CatSeccion::with('espaciosFisicos')->get();
        
        return view('catalogos.secciones.index', compact('secciones'));
    }

    /**
     * Muestra el formulario para crear una nueva sección.
     */
    public function create(): View
    {
        return view('catalogos.secciones.create');
    }

    /**
     * Almacena una nueva sección en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:cat_secciones,nombre'
        ]);

        CatSeccion::create($request->only('nombre'));

        return redirect()
            ->route('secciones.index')
            ->with('success', 'Sección creada correctamente.');
    }

    /**
     * Muestra el detalle de una sección específica.
     */
    public function show(CatSeccion $seccion): View
    {
        // Cargamos la relación para mostrar los espacios en la vista de detalle
        $seccion->load('espaciosFisicos.tipoEspacioFisico');
        
        return view('catalogos.secciones.show', compact('seccion'));
    }

    /**
     * Muestra el formulario para editar una sección existente.
     */
    public function edit(CatSeccion $seccion): View
    {
        return view('catalogos.secciones.edit', compact('seccion'));
    }

    /**
     * Actualiza los datos de una sección existente.
     */
    public function update(Request $request, CatSeccion $seccion): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:cat_secciones,nombre,' . $seccion->id_seccion . ',id_seccion'
        ]);

        $seccion->update($request->only('nombre'));

        return redirect()
            ->route('secciones.index')
            ->with('success', 'Sección actualizada correctamente.');
    }

    /**
     * Elimina una sección del sistema (Soft Delete).
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
     */
    public function getData(CatSeccion $seccion)
    {
        return response()->json($seccion->load('espaciosFisicos'));
    }
}
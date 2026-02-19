<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Titular;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador encargado de la gestión de titulares.
 *
 * Maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * relacionadas con los titulares del sistema.
 */
class TitularesController extends Controller
{
    /**
     * Muestra un listado de todos los titulares.
     *
     * @return View
     */
        public function index(): View
        {
            $titulares = Titular::orderBy('familia')->paginate(15);
            return view('titulares.index', compact('titulares'));
        }

    /**
     * Muestra el formulario para crear un nuevo titular.
     *
     * @return View
     */
    public function create(): View
    {
        return view('titulares.create');
    }

    /**
     * Almacena un nuevo titular en la base de datos.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'familia'        => 'required|string|max:255',
            'domicilio'      => 'required|string|max:255',
            'colonia'        => 'required|string|max:255',
            'codigo_postal'  => 'required|string|max:10',
            'municipio'      => 'required|string|max:255',
            'estado'         => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:20',
        ]);

        Titular::create($request->all());

        return redirect()
            ->route('titulares.index')
            ->with('success', 'Titular creado correctamente.');
    }

    /**
     * Muestra el detalle de un titular específico.
     *
     * @param Titular $titular
     * @return View
     */
    public function show(Titular $titular): View
    {
        return view('titulares.show', compact('titular'));
    }

    /**
     * Muestra el formulario para editar un titular existente.
     *
     * @param Titular $titular
     * @return View
     */
    public function edit(Titular $titular): View
    {
        return view('titulares.edit', compact('titular'));
    }

    /**
     * Actualiza los datos de un titular existente.
     *
     * @param Request $request
     * @param Titular $titular
     * @return RedirectResponse
     */
    public function update(Request $request, Titular $titular): RedirectResponse
    {
        $request->validate([
            'familia'        => 'required|string|max:255',
            'domicilio'      => 'required|string|max:255',
            'colonia'        => 'required|string|max:255',
            'codigo_postal'  => 'required|string|max:10',
            'municipio'      => 'required|string|max:255',
            'estado'         => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:20',
        ]);

        $titular->update($request->only([
            'familia',
            'domicilio',
            'colonia',
            'codigo_postal',
            'municipio',
            'estado',
            'telefono'
        ]));

        return redirect()
            ->route('titulares.index')
            ->with('success', 'Titular actualizado correctamente.');
    }

    /**
     * Elimina un titular del sistema (Soft Delete).
     *
     * @param Titular $titular
     * @return RedirectResponse
     */
    public function destroy(Titular $titular): RedirectResponse
    {
        $titular->delete();

        return redirect()
            ->route('titulares.index')
            ->with('success', 'Titular eliminado correctamente.');
    }   

    /**
     * Devuelve la información de un titular en formato JSON.
     *
     * @param Titular $titular
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Titular $titular)
    {
        return response()->json($titular);
    }

    
}

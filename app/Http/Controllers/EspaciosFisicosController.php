<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\EspacioFisico;
use App\Models\CatSeccion;
use App\Models\CatTipoEspacioFisico;

/**
 * Controlador encargado de la gestión del catálogo de espacios físicos.
 *
 * Maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * relacionadas con los espacios físicos del sistema.
 */
class EspaciosFisicosController extends Controller
{
    /**
     * Muestra un listado de todos los espacios físicos.
     *
     * Carga las relacion:
     * - sección (para mostrar el nombre de la sección a la que pertenece cada espacio)
     *
     * @return View
     */
    public function index(): View
    {
        
        // Ahora cargamos Secciones directamente
        $secciones = CatSeccion::orderBy('nombre', 'asc')->get();


        $tiposEspacioFisico = CatTipoEspacioFisico::orderBy('nombre')->get();

        // Cambiamos get() por paginate() para activar la paginación de Laravel
        $espaciosFisicos = EspacioFisico::with([
            'seccion', // Carga la sección directamente
            'tipoEspacioFisico'
        ])
        ->orderBy('id_espacio_fisico', 'desc') // Opcional: mostrar los más nuevos primero
        ->paginate(10); // Esto genera los links y limita la consulta SQL

        return view(
            'espacios_fisicos.index',
            compact('espaciosFisicos', 'secciones', 'tiposEspacioFisico')
        );
    }

    /**
     * Muestra el formulario para crear un nuevo espacio físico.
     *
     * @return View
     */
    public function create(): View
    {
        $secciones = CatSeccion::orderBy('nombre')->get();
        $tiposEspacioFisico = CatTipoEspacioFisico::all();

        return view(
            'espacios_fisicos.create',
            compact('secciones', 'tiposEspacioFisico')
        );
    }

    /**
     * Almacena un nuevo espacio físico en la base de datos.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'id_seccion'                => 'required|exists:cat_secciones,id_seccion',
            'id_tipo_espacio_fisico'     => 'required|exists:cat_tipos_espacio_fisico,id_tipo_espacio_fisico',
            'nombre'                     => 'required|string|max:255',
            'descripcion'                => 'nullable|string',
        ]);

        EspacioFisico::create($request->only([
            'id_seccion',
            'id_tipo_espacio_fisico',
            'nombre',
            'descripcion',
        ]));

        return redirect()
            ->route('espacios_fisicos.index')
            ->with('success', 'Espacio físico creado correctamente.');
    }

    /**
     * Muestra el detalle de un espacio físico específico.
     *
     * Incluye los lotes asociados (solo lectura).
     *
     * @param EspacioFisico $espacioFisico
     * @return View
     */
    public function show(EspacioFisico $espacioFisico): View
    {
        $espacioFisico->load([
            'seccion',
            'tipoEspacioFisico',
            'lotes'
        ]);

        return view('espacios_fisicos.show', compact('espacioFisico'));
    }

    /**
     * Muestra el formulario para editar un espacio físico existente.
     *
     * @param EspacioFisico $espacioFisico
     * @return View
     */
    public function edit(EspacioFisico $espacioFisico): View
    {
        $secciones = CatSeccion::orderBy('nombre')->get();
        $tiposEspacioFisico = CatTipoEspacioFisico::all();

        return view(
            'espacios_fisicos.edit',
            compact('espacioFisico', 'secciones', 'tiposEspacioFisico')
        );
    }

    /**
     * Actualiza los datos de un espacio físico existente.
     *
     * @param Request $request
     * @param EspacioFisico $espacioFisico
     * @return RedirectResponse
     */
    public function update(Request $request, EspacioFisico $espacioFisico): RedirectResponse
    {
        $request->validate([
            'id_seccion'                => 'required|exists:cat_secciones,id_seccion',
            'id_tipo_espacio_fisico'     => 'required|exists:cat_tipos_espacio_fisico,id_tipo_espacio_fisico',
            'nombre'                     => 'required|string|max:255',
            'descripcion'                => 'nullable|string',
        ]);

        $espacioFisico->update($request->only([
            'id_seccion',
            'id_tipo_espacio_fisico',
            'nombre',
            'descripcion',
        ]));

        return redirect()
            ->route('espacios_fisicos.index')
            ->with('success', 'Espacio físico actualizado correctamente.');
    }

    /**
     * Elimina un espacio físico del sistema (Soft Delete).
     *
     * @param EspacioFisico $espacioFisico
     * @return RedirectResponse
     */
    public function destroy(EspacioFisico $espacioFisico): RedirectResponse
    {
        $espacioFisico->delete();

        return redirect()
            ->route('espacios_fisicos.index')
            ->with('success', 'Espacio físico eliminado correctamente.');
    }

    /**
     * Devuelve la información de un espacio físico en formato JSON.
     *
     * Útil para peticiones AJAX o consumo vía API.
     *
     * @param EspacioFisico $espacioFisico
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(EspacioFisico $espacioFisico)
    {
        return response()->json(
            $espacioFisico->load([
                'seccion',
                'tipoEspacioFisico',
                'lotes'
            ])
        );
    }

    public function getEspaciosBySeccion($id_seccion)
    {
        $espacios = EspacioFisico::with('tipoEspacioFisico')
            ->where('id_seccion', $id_seccion)
            ->orderBy('nombre')
            ->get();

        $resultado = $espacios->map(function($espacio){
            return [
                'id_espacio_fisico' => $espacio->id_espacio_fisico,
                'nombre' => $espacio->nombre,
                'tipo' => $espacio->tipoEspacioFisico 
                            ? $espacio->tipoEspacioFisico->nombre 
                            : 'Sin tipo'
            ];
        });

        return response()->json($resultado);
    }
}

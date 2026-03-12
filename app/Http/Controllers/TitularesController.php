<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Titular;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\TipoDocumento;
use App\Models\TipoDocumentoEntidad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Documento;


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
    public function index(Request $request): View
    {
        $query = Titular::with('documentos.tipoDocumento');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('familia', 'like', "%$search%")
                ->orWhere('domicilio', 'like', "%$search%")
                ->orWhere('colonia', 'like', "%$search%")
                ->orWhere('telefono', 'like', "%$search%");
            });
        }

        $titulares = $query
            ->orderBy('familia')
            ->paginate(15)
            ->withQueryString();

        $tiposDocumentoTitular = TipoDocumento::whereHas('entidades', function ($query) {
            $query->where('modelo', \App\Models\Titular::class);
        })->get();

        return view('titulares.index', compact('titulares', 'tiposDocumentoTitular'));
    }

    /**
     * Muestra el formulario para crear un nuevo titular.
     *
     * @return View
     */
    public function create(): View
    {
        $tiposDocumentoTitular = TipoDocumento::whereHas('entidades', function ($query) {
            $query->where('modelo', \App\Models\Titular::class);
        })->get();    

        return view('titulares.create', compact('tiposDocumentoTitular'));
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
            'familia'       => 'required|string|max:255',
            'domicilio'     => 'required|string|max:255',
            'colonia'       => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'municipio'     => 'required|string|max:255',
            'estado'        => 'required|string|max:255',
            'telefono'      => 'nullable|string|max:20',
            'documentos.*.archivo'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $archivosGuardados = [];

        try {
            DB::beginTransaction();

            $titular = Titular::create($request->only([
                'familia', 'domicilio', 'colonia',
                'codigo_postal', 'municipio', 'estado', 'telefono'
            ]));

            foreach ($request->file('documentos', []) as $idTipo => $item) {
                if (empty($item['archivo'])) continue;

                $archivo = $item['archivo'];
                $nombre  = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
                $ruta    = $archivo->storeAs('documentos/titulares', $nombre, 'public');

                $archivosGuardados[] = $ruta;

                Documento::create([
                    'documentable_id'   => $titular->id_titular,
                    'documentable_type' => \App\Models\Titular::class,
                    'id_tipo_documento' => $idTipo,
                    'archivo'           => $ruta,
                    'registrado_por'    => Auth::id(),
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            foreach ($archivosGuardados as $ruta) {
                Storage::disk('public')->delete($ruta);
            }

            return redirect()->back()->with('error', 'Ocurrió un error al guardar el titular.');
        }

        return redirect()->route('titulares.index')->with('success', 'Titular creado correctamente.');
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
        $tiposDocumentoTitular = TipoDocumento::whereHas('entidades', function ($query) {
            $query->where('modelo', \App\Models\Titular::class);
        })->get();

        $titular->load('documentos.tipoDocumento');
    
        return view('titulares.edit', compact('titular', 'tiposDocumentoTitular'));
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
            'familia'       => 'required|string|max:255',
            'domicilio'     => 'required|string|max:255',
            'colonia'       => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'municipio'     => 'required|string|max:255',
            'estado'        => 'required|string|max:255',
            'telefono'      => 'nullable|string|max:20',
            'documentos.*.archivo'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $archivosGuardados = [];

        try {

            DB::beginTransaction();

            $titular->update($request->only([
                'familia',
                'domicilio',
                'colonia',
                'codigo_postal',
                'municipio',
                'estado',
                'telefono'
            ]));

            foreach ($request->input('documentos', []) as $idTipo => $datos) {

                if (!$request->hasFile("documentos.$idTipo.archivo")) {
                    continue;
                }

                $archivo = $request->file("documentos.$idTipo.archivo");

                $nombre = Str::uuid().'.'.$archivo->getClientOriginalExtension();

                $ruta = $archivo->storeAs(
                    'documentos/titulares',
                    $nombre,
                    'public'
                );

                $archivosGuardados[] = $ruta;

                $docExistente = Documento::where('documentable_id', $titular->id_titular)
                    ->where('documentable_type', \App\Models\Titular::class)
                    ->where('id_tipo_documento', $idTipo)
                    ->first();

                if ($docExistente) {

                    if ($docExistente->archivo && Storage::disk('public')->exists($docExistente->archivo)) {
                        Storage::disk('public')->delete($docExistente->archivo);
                    }

                    $docExistente->delete();
                }

                Documento::create([
                    'documentable_id'   => $titular->id_titular,
                    'documentable_type' => \App\Models\Titular::class,
                    'id_tipo_documento' => $idTipo,
                    'archivo'           => $ruta,
                    'fecha_emision'     => $datos['fecha_emision'] ?? null,
                    'registrado_por'    => Auth::id(),
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            foreach ($archivosGuardados as $ruta) {
                Storage::disk('public')->delete($ruta);
            }

            return redirect()->back()->with(
                'error',
                'Ocurrió un error al actualizar el titular: '.$e->getMessage()
            );
        }

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
        $titular->load('documentos.tipoDocumento');
        return response()->json($titular);
    }

    
}

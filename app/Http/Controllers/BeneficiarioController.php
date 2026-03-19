<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiario;
use App\Models\Titular;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Documento;

class BeneficiarioController extends Controller
{
    public function index(Request $request): View
    {
        $query = Beneficiario::with('titular', 'documentos.tipoDocumento');

        if ($request->filled('search')) {

            $query->withTrashed();

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('domicilio', 'like', "%{$search}%")
                  ->orWhere('colonia', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%")

                  ->orWhereHas('titular', function ($t) use ($search) {
                      $t->where('familia', 'like', "%{$search}%")
                        ->orWhere('domicilio', 'like', "%{$search}%")
                        ->orWhere('colonia', 'like', "%{$search}%");
                  });
            });
        }

        $beneficiarios = $query
            ->orderBy('id_titular')
            ->orderBy('orden')
            ->paginate(10)
            ->withQueryString();

        $tiposDocumento = TipoDocumento::whereHas('entidades', function ($q) {
            $q->where('modelo', \App\Models\Beneficiario::class);
        })->get();

        $titulares = Titular::orderBy('familia')->get();

        return view('beneficiarios.index', compact(
            'beneficiarios',
            'tiposDocumento',
            'titulares'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_titular'     => 'required|exists:titulares,id_titular',
            'nombre'         => 'required|string|max:255',
            'domicilio'      => 'required|string|max:255',
            'colonia'        => 'required|string|max:255',
            'codigo_postal'  => 'required|string|max:10',
            'municipio'      => 'required|string|max:255',
            'estado'         => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $titular = Titular::findOrFail($request->id_titular);

            $orden = ($titular->beneficiarios()->withTrashed()->max('orden') ?? 0) + 1;

            $beneficiario = $titular->beneficiarios()->create([
                'nombre' => $request->nombre,
                'domicilio' => $request->domicilio,
                'colonia' => $request->colonia,
                'codigo_postal' => $request->codigo_postal,
                'municipio' => $request->municipio,
                'estado' => $request->estado,
                'telefono' => $request->telefono,
                'orden' => $orden
            ]);

            // 🔥 GUARDAR DOCUMENTOS (POLYMORPHIC)
            if ($request->has('documentos')) {

                foreach ($request->documentos as $doc) {

                    if (isset($doc['archivo']) && $doc['archivo']->isValid()) {

                        $archivo = $doc['archivo'];

                        $ruta = $archivo->store('documentos/beneficiarios', 'public');

                        $beneficiario->documentos()->create([
                            'archivo' => $ruta,
                            'id_tipo_documento' => $doc['id_tipo_documento'],
                            'registrado_por' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Error al guardar beneficiario');
        }

        return redirect()
            ->route('beneficiarios.index')
            ->with('success', 'Beneficiario creado correctamente');
    }

    public function update(Request $request, Beneficiario $beneficiario): RedirectResponse
    {
        $request->validate([
            'nombre'         => 'required|string|max:255',
            'domicilio'      => 'required|string|max:255',
            'colonia'        => 'required|string|max:255',
            'codigo_postal'  => 'required|string|max:10',
            'municipio'      => 'required|string|max:255',
            'estado'         => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:20',
            'documentos.*.archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $archivosGuardados = [];

        try {
            DB::beginTransaction();

            $beneficiario->update($request->only([
                'nombre', 'domicilio', 'colonia',
                'codigo_postal', 'municipio', 'estado', 'telefono'
            ]));

            // ✅ Mismo patrón que TitularesController
            foreach ($request->input('documentos', []) as $idTipo => $datos) {

                if (!$request->hasFile("documentos.$idTipo.archivo")) {
                    continue;
                }

                $archivo = $request->file("documentos.$idTipo.archivo");
                $nombre  = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
                $ruta    = $archivo->storeAs('documentos/beneficiarios', $nombre, 'public');

                $archivosGuardados[] = $ruta;

                // Borra el documento anterior si existe
                $docExistente = Documento::where('documentable_id', $beneficiario->id_beneficiario)
                    ->where('documentable_type', \App\Models\Beneficiario::class)
                    ->where('id_tipo_documento', $idTipo)
                    ->first();

                if ($docExistente) {
                    if ($docExistente->archivo && Storage::disk('public')->exists($docExistente->archivo)) {
                        Storage::disk('public')->delete($docExistente->archivo);
                    }
                    $docExistente->delete();
                }

                // Crea el nuevo documento
                Documento::create([
                    'documentable_id'   => $beneficiario->id_beneficiario,
                    'documentable_type' => \App\Models\Beneficiario::class,
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

            return back()->with('error', 'Error al actualizar beneficiario: ' . $e->getMessage());
        }

        return redirect()
            ->route('beneficiarios.index')
            ->with('success', 'Beneficiario actualizado correctamente');
    }

    public function destroy(Beneficiario $beneficiario): RedirectResponse
    {
        $beneficiario->delete();

        return redirect()
            ->route('beneficiarios.index')
            ->with('success', 'Beneficiario eliminado correctamente');
    }

    public function getData(Beneficiario $beneficiario)
    {
        $beneficiario->load('documentos.tipoDocumento');

        return response()->json($beneficiario);
    }
}
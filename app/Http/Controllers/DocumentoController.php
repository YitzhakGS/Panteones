<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with('tipoDocumento')
            ->orderBy('id_documento', 'desc')
            ->paginate(15);

        return view('catalogos.documentos.index', compact('documentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documentable_id'   => 'required',
            'documentable_type' => 'required|string',
            'documentos'        => 'nullable|array',
            'documentos.*.id_tipo_documento' => 'required|exists:tipo_documentos,id_tipo_documento',
            'documentos.*.archivo'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $carpeta = Str::plural(strtolower(class_basename($request->documentable_type)));

        $archivosGuardados = [];
        $registros = [];

        try {
            DB::beginTransaction();

            foreach ($request->file('documentos') ?? [] as $index => $item) {

                // 🔥 Si no subieron archivo, lo saltamos
                if (!isset($item['archivo'])) continue;

                $archivo = $item['archivo'];

                $nombre  = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
                $ruta    = $archivo->storeAs('documentos/' . $carpeta, $nombre, 'public');

                $archivosGuardados[] = $ruta;

                $registros[] = [
                    'documentable_id'   => $request->documentable_id,
                    'documentable_type' => $request->documentable_type,
                    'id_tipo_documento' => $request->input("documentos.$index.id_tipo_documento"),
                    'fecha_emision'     => $request->input("documentos.$index.fecha_emision"),
                    'archivo'           => $ruta,
                    'registrado_por'    => Auth::id(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }

            // 🔥 Solo insertamos si hay algo
            if (!empty($registros)) {
                Documento::insert($registros);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            foreach ($archivosGuardados as $ruta) {
                Storage::disk('public')->delete($ruta);
            }

            return back()->with('error', 'Ocurrió un error al guardar los documentos.');
        }

        return back()->with('success', 'Documentos procesados correctamente.');
    }

    public function show($id)
    {
        $documento = Documento::findOrFail($id);
        $path = storage_path('app/public/' . $documento->archivo);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function destroy($id)
    {
        $documento = Documento::findOrFail($id);

        if ($documento->archivo && Storage::disk('public')->exists($documento->archivo)) {
            Storage::disk('public')->delete($documento->archivo);
        }

        $documento->delete();

        return back()->with('success', 'Documento eliminado.');
    }
}
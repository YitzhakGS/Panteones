<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use App\Services\TipoDocumentoService;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function __construct(protected TipoDocumentoService $service) {}

    public function index(Request $request)
    {
        $search = $request->input('search');

        $tiposDocumentos = TipoDocumento::with('entidades')
            ->when($search, function ($query) use ($search) {
                $query->where('nombre', 'LIKE', "%$search%");
            })
            ->orderBy('id_tipo_documento', 'desc')
            ->paginate(10);

        return view('catalogos.tipo-documentos.index', compact('tiposDocumentos', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'modelos'     => 'nullable|array',
            'modelos.*'   => 'string',
        ]);

        $this->service->store($request->all());

        return redirect()->route('tipo-documentos.index')
            ->with('success', 'Tipo de documento creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'modelos'     => 'nullable|array',
            'modelos.*'   => 'string',
        ]);

        $tipoDocumento = TipoDocumento::findOrFail($id);

        $this->service->update($tipoDocumento, $request->all());

        return redirect()->route('tipo-documentos.index')
            ->with('success', 'Tipo de documento actualizado');
    }

    public function destroy($id)
    {
        $tipoDocumento = TipoDocumento::findOrFail($id);
        $tipoDocumento->delete();

        return redirect()->route('tipo-documentos.index')
            ->with('success', 'Tipo de documento eliminado');
    }
}
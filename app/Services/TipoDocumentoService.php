<?php

namespace App\Services;

use App\Models\TipoDocumento;
use App\Models\TipoDocumentoEntidad;

class TipoDocumentoService
{
    public function store(array $data): TipoDocumento
    {
        $tipoDocumento = TipoDocumento::create([
            'nombre'      => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
        ]);

        $this->sincronizarEntidades($tipoDocumento, $data['modelos'] ?? []);

        return $tipoDocumento;
    }

    public function update(TipoDocumento $tipoDocumento, array $data): TipoDocumento
    {
        $tipoDocumento->update([
            'nombre'      => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
        ]);

        $this->sincronizarEntidades($tipoDocumento, $data['modelos'] ?? []);

        return $tipoDocumento;
    }

    private function sincronizarEntidades(TipoDocumento $tipoDocumento, array $modelos): void
    {
        // Borra los vínculos anteriores y los reemplaza con los nuevos
        TipoDocumentoEntidad::where('id_tipo_documento', $tipoDocumento->id_tipo_documento)->delete();

        foreach ($modelos as $modelo) {
            TipoDocumentoEntidad::create([
                'id_tipo_documento' => $tipoDocumento->id_tipo_documento,
                'modelo'            => $modelo,
            ]);
        }
    }
}
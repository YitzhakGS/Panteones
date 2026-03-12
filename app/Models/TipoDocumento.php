<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TipoDocumentoEntidad;

class TipoDocumento extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_documentos';

    protected $primaryKey = 'id_tipo_documento';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function documentos()
    {
        return $this->hasMany(Documento::class,'id_tipo_documento','id_tipo_documento');
    }

    public function entidades()
    {
        return $this->hasMany(TipoDocumentoEntidad::class, 'id_tipo_documento', 'id_tipo_documento');
    }
}

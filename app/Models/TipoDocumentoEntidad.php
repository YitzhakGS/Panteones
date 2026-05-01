<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumentoEntidad extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'tipo_documento_entidades';

    protected $fillable = [
        'id_tipo_documento',
        'modelo',
    ];

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento', 'id_tipo_documento');
    }
}

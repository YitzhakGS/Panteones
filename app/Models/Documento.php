<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use SoftDeletes;

    protected $table = 'documentos';

    protected $primaryKey = 'id_documento';

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'id_tipo_documento',
        'archivo',
        'registrado_por'
    ];

    public function tipoDocumento()
    {
        return $this->belongsTo(
            TipoDocumento::class,
            'id_tipo_documento',
            'id_tipo_documento'
        );
    }

    public function documentable()
    {
        return $this->morphTo();
    }
}

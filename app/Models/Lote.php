<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';

    protected $fillable = [
        'id_espacio_fisico',
        'id_estado_lote',
        'numero',
    ];

    public function espacioFisico()
    {
        return $this->belongsTo(EspacioFisico::class, 'id_espacio_fisico');
    }

    public function estadoLote()
    {
        return $this->belongsTo(CatEstadoLote::class, 'id_estado_lote');
    }

}

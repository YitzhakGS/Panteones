<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class EspacioFisicoLote extends Model
{
    use HasFactory;

    protected $table = 'espacio_fisico_lote';

    protected $fillable = [
        'id_espacio_fisico',
        'id_lote',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function espacioFisico()
    {
        return $this->belongsTo(
            EspacioFisico::class,
            'id_espacio_fisico',
            'id_espacio_fisico'
        );
    }

    public function lote()
    {
        return $this->belongsTo(
            Lote::class,
            'id_lote',
            'id_lote'
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EspacioFisico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'espacios_fisicos';
    protected $primaryKey = 'id_espacio_fisico';

    protected $fillable = [
        'id_cuadrilla',
        'id_tipo_espacio_fisico',
        'nombre',
        'descripcion',
    ];

    public function cuadrilla()
    {
        return $this->belongsTo(CatCuadrilla::class, 'id_cuadrilla');
    }

    public function tipoEspacioFisico()
    {
        return $this->belongsTo(CatTipoEspacioFisico::class, 'id_tipo_espacio_fisico');
    }

    public function lotes()
    {
        return $this->belongsToMany(
            Lote::class,
            'espacio_fisico_lote',
            'id_espacio_fisico',
            'id_lote'
        )->withPivot(['fecha_inicio', 'fecha_fin'])
            ->withTimestamps();
    }

    public function lotesVigentes()
    {
        return $this->lotes()->wherePivotNull('fecha_fin');
    }

    public function espacioFisicoLotes()
    {
        return $this->hasMany(
            EspacioFisicoLote::class,
            'id_espacio_fisico',
            'id_espacio_fisico'
        );
    }
}

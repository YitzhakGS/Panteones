<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\Cast;
class Lote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';

    protected $fillable = [
        'numero',
        'metros_cuadrados',
        'col_norte',
        'col_sur',
        'col_oriente',
        'col_poniente',
        'med_norte',
        'med_sur',
        'med_oriente',
        'med_poniente',
        'referencias',
    ];

    // 📜 Historial completo
    public function espaciosFisicosLotes()
    {
        return $this->hasMany(
            EspacioFisicoLote::class,
            'id_lote',
            'id_lote'
        );
    }

    public function espaciosActuales()
    {
        return $this->belongsToMany(
                EspacioFisico::class,
                'espacio_fisico_lote',
                'id_lote',
                'id_espacio_fisico'
            )
            ->withPivot('fecha_inicio', 'fecha_fin')
            ->wherePivotNull('fecha_fin');
    }

    public function getEspacioActualAttribute()
    {
        return $this->espaciosActuales->first();
    }

    public function getUbicacionFormateadaAttribute(): ?string
    {
        $espacio = $this->espacioActual;

        if (! $espacio) {
            return null;
        }

        $seccion   = optional($espacio->cuadrilla->seccion)->nombre;
        $cuadrilla = optional($espacio->cuadrilla)->nombre;
        $tipo      = optional($espacio->tipoEspacioFisico)->nombre;
        $nombre    = $espacio->nombre;

        return trim("{$seccion}\n{$cuadrilla}\n{$tipo} {$nombre}");
    }
}
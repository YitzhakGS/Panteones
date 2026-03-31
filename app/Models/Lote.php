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
        'numero',
        'metros_cuadrados',
        'col_norte', 'col_sur', 'col_oriente', 'col_poniente',
        'med_norte', 'med_sur', 'med_oriente', 'med_poniente',
        'referencias',
    ];

    public function espaciosFisicosLotes()
    {
        return $this->hasMany(EspacioFisicoLote::class, 'id_lote', 'id_lote');
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

    /**
     * ATRIBUTO ACTUALIZADO: 
     * Accede directamente a la sección desde el espacio físico.
     */
    public function getUbicacionFormateadaAttribute(): ?string
    {
        $espacio = $this->espacioActual;

        if (!$espacio) {
            return null;
        }

        // Acceso directo: Espacio -> Seccion
        $seccion = optional($espacio->seccion)->nombre;
        $tipo    = optional($espacio->tipoEspacioFisico)->nombre;
        $nombre  = $espacio->nombre;

        // Quitamos la variable $cuadrilla que ya no existe
        return trim("{$seccion}\n{$tipo} {$nombre}");
    }

    public function concesiones()
    {
        return $this->hasMany(Concesion::class, 'id_lote', 'id_lote');
    }

    public function concesionActiva()
        {
            return $this->hasOne(Concesion::class, 'lote_id')
            ->whereNull('fecha_fin')
            ->whereHas('estatus', fn($q) => $q->where('nombre', 'Vigente'));
    }
}
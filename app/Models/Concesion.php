<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Concesion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'concesiones';
    protected $primaryKey = 'id_concesion';

    protected $fillable = [
        'lote_id',
        'titular_id',
        'id_uso_funerario',
        'id_estatus',
        'fecha_inicio',
        'fecha_fin',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    /* =========================
       RELACIONES
       ========================= */

    // Lote
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    // Titular
    public function titular()
    {
        return $this->belongsTo(Titular::class, 'titular_id');
    }

    // Uso funerario
    public function usoFunerario()
    {
        return $this->belongsTo(CatUsoFunerario::class, 'id_uso_funerario');
    }

    // Estatus
    public function estatus()
    {
        return $this->belongsTo(CatEstatus::class, 'id_estatus');
    }

    // Refrendos
    public function refrendos()
    {
        return $this->hasMany(Refrendo::class, 'id_concesion');
    }

    // Último refrendo (clave para padrón)
    public function ultimoRefrendo()
    {
        return $this->hasOne(Refrendo::class, 'id_concesion')
            ->latestOfMany('fecha_refrendo');
    }
}

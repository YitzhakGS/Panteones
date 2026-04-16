<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoFinado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimientos_finados';
    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_finado',
        'id_ubicacion_actual',
        'ubicacion_actual',
        'ubicacion_anterior',
        'tipo',
        'fecha',
        'solicitante',
        'observaciones',
        'es_misma_ubicacion',
        'es_externo',
        'ubicacion_externa',
    ];

    protected $casts = [
        'fecha'             => 'date',
        'es_misma_ubicacion'=> 'boolean',
        'es_externo'        => 'boolean',
    ];

    public function finado(): BelongsTo
    {
        return $this->belongsTo(Finado::class, 'id_finado', 'id_finado');
    }

    public function ubicacionActual(): BelongsTo
    {
        return $this->belongsTo(Concesion::class, 'id_ubicacion_actual', 'id_concesion');
    }

    public function concesion()
    {
        return $this->belongsTo(Concesion::class, 'id_ubicacion_actual');
    }
}
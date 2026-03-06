<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refrendo extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = 'refrendos';
    protected $primaryKey = 'id_refrendo';

    protected $fillable = [
        'id_concesion',
        'fecha_refrendo',
        'periodo_inicio',
        'periodo_fin',
        'estado',
        'monto',
        'observaciones',
    ];

    protected $casts = [
        'fecha_refrendo' => 'date',
        'periodo_inicio' => 'date',
        'periodo_fin'    => 'date',
        'monto'          => 'decimal:2',
    ];

    /* =========================
       RELACIONES
       ========================= */

    public function concesion()
    {
        return $this->belongsTo(Concesion::class, 'id_concesion', 'id_concesion');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_refrendo', 'id_refrendo');
    }
}

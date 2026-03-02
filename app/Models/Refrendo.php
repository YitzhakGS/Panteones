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
        'numero_refrendo',
        'importe',
        'observaciones',
    ];

    protected $casts = [
        'fecha_refrendo' => 'date',
        'importe'        => 'decimal:2',
    ];

    /* =========================
       RELACIONES
       ========================= */

    public function concesion()
    {
        return $this->belongsTo(Concesion::class, 'id_concesion');
    }
}

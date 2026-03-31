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
        'id_concesion',
        'tipo',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // 🔗 Relaciones

    public function finado(): BelongsTo
    {
        return $this->belongsTo(Finado::class, 'id_finado', 'id_finado');
    }

    public function concesion(): BelongsTo
    {
        return $this->belongsTo(Concesion::class, 'id_concesion', 'id_concesion');
    }

    public static function contarActivosEnConcesion(int $idConcesion)
    {
        return self::where('id_concesion', $idConcesion)
            ->whereIn('tipo', ['inhumacion', 'reinhumacion'])
            ->count();
    }
}
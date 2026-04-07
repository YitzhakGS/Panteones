<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Finado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finados';
    protected $primaryKey = 'id_finado';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_defuncion',
        'sexo',
        'observaciones',
        'solicitante',
        'tipo_construccion',
    ];

    protected $casts = [
        'fecha_defuncion' => 'date',
    ];

    // 🔗 Relaciones

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoFinado::class, 'id_finado', 'id_finado');
    }

    public function ultimoMovimiento(): HasOne
    {
        return $this->hasOne(MovimientoFinado::class, 'id_finado', 'id_finado')
            ->ofMany([
                'fecha' => 'max',
                'id_movimiento' => 'max',
            ]);
    }
}
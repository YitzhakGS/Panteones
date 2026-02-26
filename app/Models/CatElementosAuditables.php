<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatElementoAuditable extends Model
{
    protected $table = 'cat_elementos_auditables';
    protected $primaryKey = 'id_elemento';

    protected $fillable = [
        'entidad', // Titular, Lote, EspacioFisico
        'campo',   // telefono, nombre, domicilio
    ];

    /**
     * Scope útil para obtener campos auditables por entidad
     */
    public function scopePorEntidad($query, string $entidad)
    {
        return $query->where('entidad', $entidad);
    }
}

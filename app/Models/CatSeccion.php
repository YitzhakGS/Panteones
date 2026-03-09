<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatSeccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cat_secciones';
    protected $primaryKey = 'id_seccion';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = ['nombre'];

    /**
     * Esta es la relación que tu controlador busca con ->with('espaciosFisicos')
     */
    public function espaciosFisicos(): HasMany
    {
        // 1. Modelo relacionado: EspacioFisico
        // 2. Llave foránea en la tabla espacios_fisicos: 'id_seccion'
        // 3. Llave local en la tabla cat_secciones: 'id_seccion'
        return $this->hasMany(EspacioFisico::class, 'id_seccion', 'id_seccion');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatCuadrilla extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cat_cuadrillas';
    protected $primaryKey = 'id_cuadrilla';

    protected $fillable = [
        'id_seccion',
        'nombre'
    ];

    public function seccion()
    {
        return $this->belongsTo(CatSeccion::class, 'id_seccion');
    }

    public function espaciosFisicos()
    {
        return $this->hasMany(EspacioFisico::class, 'id_cuadrilla');
    }
}

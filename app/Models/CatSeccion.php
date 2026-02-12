<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatSeccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cat_secciones';
    protected $primaryKey = 'id_seccion';

    protected $fillable = ['nombre'];

    public function cuadrillas()
    {
        return $this->hasMany(CatCuadrilla::class, 'id_seccion');
    }
}

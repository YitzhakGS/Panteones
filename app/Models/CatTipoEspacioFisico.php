<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoEspacioFisico extends Model
{
    use HasFactory;

    protected $table = 'cat_tipo_espacio_fisico';
    protected $primaryKey = 'id_tipo_espacio_fisico';

    protected $fillable = [
        'nombre',
    ];

    public function espaciosFisicos()
    {
        return $this->hasMany(EspacioFisico::class, 'id_tipo_espacio_fisico');
    }
}

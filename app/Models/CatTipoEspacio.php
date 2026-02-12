<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoEspacio extends Model
{
    use HasFactory;

    protected $table = 'cat_tipo_espacio';
    protected $primaryKey = 'id_tipo_espacio';

    protected $fillable = [
        'nombre',
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_tipo_espacio');
    }
}

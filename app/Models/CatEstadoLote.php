<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatEstadoLote extends Model
{
    use HasFactory;

    protected $table = 'cat_estado_lote';
    protected $primaryKey = 'id_estado_lote';

    protected $fillable = [
        'nombre',
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_estado_lote');
    }
}

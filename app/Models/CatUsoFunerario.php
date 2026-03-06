<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatUsoFunerario extends Model
{
    use HasFactory;

    protected $table = 'uso_funerario';
    protected $primaryKey = 'id_uso_funerario';

    protected $fillable = ['nombre'];

    public function lotes()
    {
        return $this->hasMany(Concesion::class, 'id_uso_funerario');
    }
}

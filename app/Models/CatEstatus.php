<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatEstatus extends Model
{
    use HasFactory;

    protected $table = 'cat_estatus';
    protected $primaryKey = 'id_estatus';

    protected $fillable = ['nombre'];

    public function concesiones()
    {
        return $this->hasMany(Concesion::class, 'id_estatus');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Titular extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'titulares';
    protected $primaryKey = 'id_titular';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'familia',
        'domicilio',
        'colonia',
        'codigo_postal',
        'municipio',
        'estado',
        'telefono'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    |
    | Aquí después podrás relacionar:
    | - lotes
    | - espacios físicos
    | - concesiones
    |
    */
}

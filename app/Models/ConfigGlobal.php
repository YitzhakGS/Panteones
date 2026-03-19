<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigGlobal extends Model
{
    use HasFactory;
    protected $table      = 'config_global';
    protected $primaryKey = 'id';
 
    protected $fillable = ['clave', 'valor', 'descripcion'];
 
    /**
     * Obtiene el valor de una clave de configuración.
     */
    public static function get(string $clave): ?string
    {
        return static::where('clave', $clave)->value('valor');
    }
 
    /**
     * Establece el valor de una clave de configuración.
     */
    public static function set(string $clave, ?string $valor): void
    {
        static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor]
        );
    }
}

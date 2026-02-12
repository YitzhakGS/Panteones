<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
   
        protected $table = 'settings_colores';
        protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

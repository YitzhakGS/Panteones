<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'beneficiarios';
    protected $primaryKey = 'id_beneficiario';

    protected $fillable = [
        'id_titular',
        'nombre',
        'domicilio',
        'colonia',
        'codigo_postal',
        'municipio',
        'estado',
        'telefono',
        'orden'
    ];

    public function titular()
    {
        return $this->belongsTo(Titular::class, 'id_titular');
    }

    public function documentos()
    {
        return $this->morphMany(Documento::class, 'documentable');
    }

    protected static function booted()
    {
        static::deleting(function ($beneficiario) {

            // Solo aplica en soft delete normal
            if ($beneficiario->isForceDeleting()) {
                return;
            }

            self::where('id_titular', $beneficiario->id_titular)
                ->where('orden', '>', $beneficiario->orden)
                ->decrement('orden');
        });
    }
}
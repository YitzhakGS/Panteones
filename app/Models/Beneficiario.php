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
        // ── Cuando cambia el titular ──────────────────────────────────────────
        static::updating(function ($beneficiario) {

            if ($beneficiario->isDirty('id_titular')) {

                $titularAnterior = $beneficiario->getOriginal('id_titular');
                $ordenAnterior   = $beneficiario->getOriginal('orden');

                // 1. Reordena los beneficiarios del titular anterior
                self::where('id_titular', $titularAnterior)
                    ->where('orden', '>', $ordenAnterior)
                    ->decrement('orden');

                // 2. Asigna el siguiente orden en el nuevo titular
                $nuevoOrden = (self::where('id_titular', $beneficiario->id_titular)
                    ->withTrashed()
                    ->max('orden') ?? 0) + 1;

                $beneficiario->orden = $nuevoOrden;
            }
        });

        // ── Al eliminar (soft delete) ─────────────────────────────────────────
        static::deleting(function ($beneficiario) {

            if ($beneficiario->isForceDeleting()) {
                return;
            }

            self::where('id_titular', $beneficiario->id_titular)
                ->where('orden', '>', $beneficiario->orden)
                ->decrement('orden');
        });
    }
}
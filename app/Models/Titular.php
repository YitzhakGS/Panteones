<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        'telefono',
        'fallecido' // 👈 IMPORTANTE
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function concesiones()
    {
        return $this->hasMany(Concesion::class, 'id_titular');
    }

    public function documentos()
    {
        return $this->morphMany(Documento::class, 'documentable');
    }

    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class, 'id_titular')
                    ->orderBy('orden');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActivos($query)
    {
        return $query->where('fallecido', false);
    }

    // -------------------------------------------------------------------------
    // Lógica de negocio
    // -------------------------------------------------------------------------

    public function transferirATitular()
    {
        // Evitar doble ejecución
        if ($this->fallecido) {
            return false;
        }

        $beneficiario = $this->beneficiarios()->first();

        if (!$beneficiario) {
            return false;
        }

        DB::transaction(function () use ($beneficiario) {

            $nuevoTitular = static::create([
                'familia'       => $beneficiario->nombre,
                'domicilio'     => $beneficiario->domicilio,
                'colonia'       => $beneficiario->colonia,
                'codigo_postal' => $beneficiario->codigo_postal,
                'municipio'     => $beneficiario->municipio,
                'estado'        => $beneficiario->estado,
                'telefono'      => $beneficiario->telefono,
            ]);

            // Reasigna concesiones
            $this->concesiones()->update([
                'id_titular' => $nuevoTitular->id_titular
            ]);

            // 👇 Obtenemos los tipos de documento válidos para Titular
            $tiposValidos = \App\Models\TipoDocumento::whereHas('entidades', function ($q) {
                $q->where('modelo', \App\Models\Titular::class);
            })->pluck('id_tipo_documento');

            // 👇 Solo transferimos documentos que sean válidos para Titular
            $beneficiario->documentos()
                ->whereIn('id_tipo_documento', $tiposValidos)
                ->update([
                    'documentable_id'   => $nuevoTitular->id_titular,
                    'documentable_type' => \App\Models\Titular::class,
                ]);

            // 👇 Los documentos que NO son válidos para Titular se eliminan
            $beneficiario->documentos()
                ->whereNotIn('id_tipo_documento', $tiposValidos)
                ->delete();

            // Elimina beneficiario
            $beneficiario->delete();

            // 👇 Reasigna los beneficiarios restantes al nuevo titular
            // y reordena desde 1
            $this->beneficiarios()
                ->orderBy('orden')
                ->get()
                ->each(function ($ben, $index) use ($nuevoTitular) {
                    $ben->update([
                        'id_titular' => $nuevoTitular->id_titular,
                        'orden'      => $index + 1, // reordena desde 1
                    ]);
                });


            // Marca como fallecido
            $this->update(['fallecido' => true]);
        });

        return true;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HistorialCambio extends Model
{
    protected $table = 'historial_cambios';
    protected $primaryKey = 'id_historial';

    // Solo usamos created_at
    public $timestamps = false;

    protected $fillable = [
        'entidad',        // Titular, Lote, EspacioFisico
        'id_registro',    // PK del registro afectado
        'campo',          // nombre del campo modificado
        'id_accion',      // FK a cat_acciones_auditoria
        'valor_anterior',
        'valor_nuevo',
        'id_usuario',
        'motivo',
    ];

    public function accion()
    {
        return $this->belongsTo(CatAccionAuditoria::class, 'id_accion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}

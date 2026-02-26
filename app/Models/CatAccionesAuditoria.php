<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatAccionAuditoria extends Model
{
    protected $table = 'cat_acciones_auditoria';
    protected $primaryKey = 'id_accion';

    protected $fillable = [
        'nombre', // CREATE, UPDATE, DELETE, RESTORE, ASIGNAR, DESASIGNAR
    ];

    public function historiales()
    {
        return $this->hasMany(HistorialCambio::class, 'id_accion');
    }
}

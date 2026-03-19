<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Refrendo extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_refrendo';
    protected $table = 'refrendos';

    protected $fillable = [
        'id_concesion',
        'tipo_refrendo',
        'fecha_refrendo',
        'fecha_inicio',
        'fecha_fin',
        'fecha_limite_pago',
        'monto',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_refrendo'    => 'date',
        'fecha_inicio'      => 'date',
        'fecha_fin'         => 'date',
        'fecha_limite_pago' => 'date',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function concesion(): BelongsTo
    {
        return $this->belongsTo(Concesion::class, 'id_concesion', 'id_concesion');
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * ¿Este refrendo está vencido? (pendiente y fecha límite ya pasó)
     */
    public function getEstaVencidoAttribute(): bool
    {
        return $this->estado === 'pendiente'
            && $this->fecha_limite_pago
            && $this->fecha_limite_pago->isPast();
    }

    /**
     * Etiqueta legible del estado con contexto de fecha
     */
    public function getEstadoLabelAttribute(): string
    {
        if ($this->esta_vencido) {
            return 'Vencido';
        }

        return match($this->estado) {
            'pendiente'  => 'Pendiente',
            'pagado'     => 'Pagado',
            'cancelado'  => 'Cancelado',
            'vencido'    => 'Vencido',
            default      => ucfirst($this->estado),
        };
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'pendiente')
                     ->where('fecha_limite_pago', '<', Carbon::today());
    }

    public function scopePagados($query)
    {
        return $query->where('estado', 'pagado');
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_refrendo', $tipo);
    }

    /**
     * Un refrendo tiene como máximo un pago.
     */
    public function pago(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\Pago::class, 'id_refrendo');
    }
}
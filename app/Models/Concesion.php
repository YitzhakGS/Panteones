<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Concesion extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_concesion';
    protected $table = 'concesiones'; 

    protected $fillable = [
        'id_lote',
        'id_titular',
        'id_estatus',
        'id_uso_funerario',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function titular(): BelongsTo
    {
        return $this->belongsTo(Titular::class, 'id_titular', 'id_titular');
    }

    public function estatus(): BelongsTo
    {
        return $this->belongsTo(CatEstatus::class, 'id_estatus', 'id_estatus');
    }

    public function usoFunerario(): BelongsTo
    {
        return $this->belongsTo(CatUsoFunerario::class, 'id_uso_funerario', 'id_uso_funerario');
    }

    public function refrendos(): HasMany
    {
        return $this->hasMany(Refrendo::class, 'id_concesion', 'id_concesion');
    }

    // -------------------------------------------------------------------------
    // Relaciones útiles
    // -------------------------------------------------------------------------

    public function ultimoRefrendo()
    {
        return $this->hasOne(Refrendo::class, 'id_concesion', 'id_concesion')
            ->latestOfMany('fecha_refrendo');
    }

    public function refrendosPendientes(): HasMany
    {
        return $this->hasMany(Refrendo::class, 'id_concesion', 'id_concesion')
            ->where('estado', 'pendiente');
    }

    public function refrendosVencidos(): HasMany
    {
        return $this->hasMany(Refrendo::class, 'id_concesion', 'id_concesion')
            ->where('estado', 'pendiente')
            ->where('fecha_limite_pago', '<', Carbon::today());
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * ¿La concesión temporal ya venció?
     */
    public function getEstaVencidaAttribute(): bool
    {
        if ($this->tipo === 'perpetuidad') {
            return false;
        }

        return $this->fecha_fin && $this->fecha_fin->isPast();
    }

    /**
     * Cantidad de refrendos vencidos sin pagar
     */
    public function getAnosEnAdeudoAttribute(): int
    {
        return $this->refrendosVencidos()->count();
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Concesiones con al menos 1 refrendo vencido sin pagar
     */
    public function scopeConAdeudo($query)
    {
        return $query->whereHas('refrendos', function ($q) {
            $q->where('estado', 'pendiente')
              ->where('fecha_limite_pago', '<', Carbon::today());
        });
    }

    /**
     * Concesiones con 3 o más refrendos vencidos sin pagar (RF-09)
     */
    public function scopeEnRiesgo($query)
    {
        return $query->whereHas('refrendos', function ($q) {
            $q->where('estado', 'pendiente')
              ->where('fecha_limite_pago', '<', Carbon::today());
        }, '>=', 3);
    }

    /**
     * Solo concesiones activas (sin fecha_fin o fecha_fin futura)
     */
    public function scopeActivas($query)
    {
        return $query->whereNull('fecha_fin')
                     ->orWhere('fecha_fin', '>=', Carbon::today());
    }
}
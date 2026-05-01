<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_refrendo',
        'fecha_pago',
        'folio_ticket',
        'monto',
        'forma_pago',
        'observaciones',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto'      => 'decimal:2',
    ];

    public function refrendo()
    {
        return $this->belongsTo(Refrendo::class, 'id_refrendo');
    }
}
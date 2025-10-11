<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionGrua extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones_gruas';

    protected $fillable = [
        'user_id',
        'tipo_grua',
        'capacidad',
        'dias_alquiler',
        'fecha_inicio',
        'ubicacion_obra',
        'incluye_operador',
        'incluye_rigger',
        'incluye_combustible',
        'observaciones',
        'precio_total',
        'decision_motivo',
        'decision_por',
        'decision_fecha',

        'estado',
    ];

    protected $casts = [
        'fecha_inicio'         => 'date',
        'incluye_operador'     => 'boolean',
        'incluye_rigger'       => 'boolean',
        'incluye_combustible'  => 'boolean',
        'precio_total'         => 'decimal:2',
        'decision_fecha' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

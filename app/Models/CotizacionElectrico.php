<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CotizacionElectrico extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones_electrico';

    protected $fillable = [
        'user_id',
        'tipo_servicio',
        'area_m2',
        'descripcion_trabajo',
        'voltaje_requerido',
        'duracion_dias',
        'ubicacion_obra',
        'fecha_inicio',
        'incluir_materiales',
        'observaciones',
        'decision_motivo',
        'decision_por',
        'decision_fecha',

        'estado',
    ];

    protected $casts = [
        'area_m2'            => 'decimal:2',
        'duracion_dias'      => 'integer',
        'fecha_inicio'       => 'date',
        'incluir_materiales' => 'boolean',
        'decision_fecha' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

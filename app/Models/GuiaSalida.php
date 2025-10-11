<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuiaSalida extends Model
{
    use SoftDeletes;

    protected $table = 'guia_salidas';

    protected $fillable = [
        'codigo',
        'pedido_id',
        'obra_id',
        'user_id',
        'fecha_emision',
        'estado',          // 'borrador' | 'emitida' | 'anulada'
        'observaciones',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
    ];

    /* ----------------- Relaciones ----------------- */

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleGuiaSalida::class, 'guia_salida_id');
    }
}

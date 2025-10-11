<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedidos';

    protected $fillable = [
        'pedido_id',
        'material_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        // 'subtotal' NO va en fillable porque es columna generada
    ];

    protected $casts = [
        'cantidad'        => 'decimal:2',
        'precio_unitario' => 'decimal:2', // <-- CAST
        'descuento'       => 'decimal:2',
        'subtotal'        => 'decimal:2', // columna generada (solo cast)
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

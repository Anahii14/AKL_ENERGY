<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleOrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_ordenes_compra';

    protected $fillable = [
        'orden_compra_id',
        'material_id',
        'descripcion',
        'unidad',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'       => 'decimal:2',
        'precio_unitario'=> 'decimal:2',
        'subtotal'       => 'decimal:2',
    ];

    public function orden()
    {
        return $this->belongsTo(OrdenCompra::class, 'orden_compra_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Cálculo automático del subtotal si no viene seteado
    protected static function booted()
    {
        static::saving(function (DetalleOrdenCompra $d) {
            if (is_null($d->subtotal)) {
                $d->subtotal = (float)$d->cantidad * (float)$d->precio_unitario;
            }
        });

        // Tras guardar un detalle, recalcular total de la cabecera
        static::saved(function (DetalleOrdenCompra $d) {
            $d->orden?->recalcularTotal();
        });

        static::deleted(function (DetalleOrdenCompra $d) {
            $d->orden?->recalcularTotal();
        });
    }
}

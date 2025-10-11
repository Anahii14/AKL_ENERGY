<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordenes_compra';

    protected $fillable = [
        'codigo',
        'proveedor_id',
        'pedido_id',
        'user_id',
        'fecha_emision',
        'fecha_entrega_estimada',
        'monto_total',
        'estado',
        'moneda',
        'observaciones',
    ];

    protected $casts = [
        'fecha_emision'           => 'date',
        'fecha_entrega_estimada'  => 'date',
        'monto_total'             => 'decimal:2',
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOrdenCompra::class, 'orden_compra_id');
    }

    // Recalcular total al guardar/actualizar detalles
    public function recalcularTotal(): void
    {
        $total = $this->detalles()->sum('subtotal');
        $this->monto_total = $total;
        $this->save();
    }
}

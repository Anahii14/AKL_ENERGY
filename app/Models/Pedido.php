<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pedidos';

    protected $fillable = [
        'codigo',
        'obra_id',
        'user_id',
        'fecha_requerida',
        'estado',
        'observaciones',
        'total_items',
    ];

    protected $casts = [
        'fecha_requerida' => 'date',
    ];

    // Relaciones
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function ordenCompra()
    {
        return $this->hasOne(OrdenCompra::class, 'pedido_id');
    }

    // Scopes útiles
    public function scopeDeUsuario($q, $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function scopeEstado($q, $estado)
    {
        return $q->where('estado', $estado);
    }
}

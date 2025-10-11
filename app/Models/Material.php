<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'proveedor_id',
        'nombre',
        'codigo',
        'descripcion',
        'unidad',
        'stock_actual',
        'stock_minimo',
        'precio_unitario',
        'estado',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    // Laravel por convención usaría "proveedors", mejor ser explícito
    protected $table = 'proveedores';

    // Campos que se pueden llenar con create() o update()
    protected $fillable = [
        'nombre',
        'ruc',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'estado',
    ];

    /**
     * Relación con Materiales (un proveedor puede tener varios materiales).
     */
    public function materiales()
    {
        return $this->hasMany(Material::class, 'proveedor_id');
    }
}

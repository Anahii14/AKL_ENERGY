<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleGuiaSalida extends Model
{
    protected $table = 'detalle_guia_salidas';

    protected $fillable = [
        'guia_salida_id',
        'material_id',
        'descripcion',    
        'unidad',          
        'cantidad',
        'stock_anterior', 
        'stock_nuevo',     
    ];

    public function guia()
    {
        return $this->belongsTo(GuiaSalida::class, 'guia_salida_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}

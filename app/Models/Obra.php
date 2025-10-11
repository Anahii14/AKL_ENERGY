<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    use HasFactory;

    protected $table = 'obras';

    protected $fillable = [
        'nombre',
        'codigo',
        'direccion',
        'fecha_inicio',
        'fecha_fin_estimada',
        'estado',
    ];
}

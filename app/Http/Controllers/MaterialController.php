<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'proveedor_id'   => ['nullable', 'exists:proveedores,id'],
            'nombre'         => ['required', 'string', 'max:180'],
            'codigo'         => ['required', 'string', 'max:50', 'unique:materiales,codigo'],
            'descripcion'    => ['nullable', 'string'],
            'unidad'         => ['required', 'string', 'max:30'],
            'stock_actual'   => ['required', 'numeric', 'min:0'],
            'stock_minimo'   => ['required', 'numeric', 'min:0'],
            'precio_unitario'=> ['required', 'numeric', 'min:0'],
        ]);

        $data['estado'] = 'activo';

        $m = Material::create($data)->load('proveedor:id,nombre');

        return response()->json(['ok' => true, 'material' => $m]);
    }

    public function update(Request $request, Material $material)
    {
        $data = $request->validate([
            'proveedor_id'   => ['nullable', 'exists:proveedores,id'],
            'nombre'         => ['required', 'string', 'max:180'],
            'codigo'         => ['required', 'string', 'max:50', Rule::unique('materiales','codigo')->ignore($material->id)],
            'descripcion'    => ['nullable', 'string'],
            'unidad'         => ['required', 'string', 'max:30'],
            'stock_actual'   => ['required', 'numeric', 'min:0'],
            'stock_minimo'   => ['required', 'numeric', 'min:0'],
            'precio_unitario'=> ['required', 'numeric', 'min:0'],
        ]);

        $material->update($data);
        $material->load('proveedor:id,nombre');

        return response()->json(['ok' => true, 'material' => $material]);
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return response()->json(['ok' => true]);
    }
}

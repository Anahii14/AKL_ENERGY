<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Obra;
use App\Models\Material;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        // puedes paginar si quieres: Proveedor::orderBy('nombre')->paginate(10)
        $proveedores = Proveedor::orderBy('nombre')->get();
        $obras = Obra::orderByDesc('id')->get();
        $materiales  = Material::with('proveedor')->orderBy('nombre')->get();
        // Usa el alias real de tu blade: 'almacen.gestion' según tu carpeta
        return view('DashboardAlmacen.gestion', compact('proveedores','obras','materiales'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:180'],
            'ruc'       => ['required', 'string', 'max:20', Rule::unique('proveedores', 'ruc')],
            'contacto'  => ['nullable', 'string', 'max:150'],
            'telefono'  => ['nullable', 'string', 'max:50'],
            'email'     => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $data['estado'] = 'activo';

        $prov = Proveedor::create($data);

        return response()->json([
            'ok' => true,
            'proveedor' => $prov
        ]);
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:180'],
            'ruc'       => ['required', 'string', 'max:20', Rule::unique('proveedores', 'ruc')->ignore($proveedor->id)],
            'contacto'  => ['nullable', 'string', 'max:150'],
            'telefono'  => ['nullable', 'string', 'max:50'],
            'email'     => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $proveedor->update($data);

        return response()->json([
            'ok' => true,
            'proveedor' => $proveedor->refresh()
        ]);
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return response()->json(['ok' => true]);
    }
}

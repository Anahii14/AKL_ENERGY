<?php

// app/Http/Controllers/ObraController.php
namespace App\Http\Controllers;

use App\Models\Obra;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ObraController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'              => ['required','string','max:180'],
            'codigo'              => ['required','string','max:50', Rule::unique('obras','codigo')],
            'direccion'           => ['nullable','string','max:255'],
            'fecha_inicio'        => ['nullable','date'],
            'fecha_fin_estimada'  => ['nullable','date'],
        ]);

        $data['estado'] = 'activa';

        $obra = Obra::create($data);

        return response()->json(['ok' => true, 'obra' => $obra]);
    }

    public function update(Request $request, Obra $obra)
    {
        $data = $request->validate([
            'nombre'              => ['required','string','max:180'],
            'codigo'              => ['required','string','max:50', Rule::unique('obras','codigo')->ignore($obra->id)],
            'direccion'           => ['nullable','string','max:255'],
            'fecha_inicio'        => ['nullable','date'],
            'fecha_fin_estimada'  => ['nullable','date'],
        ]);

        $obra->update($data);

        return response()->json(['ok' => true, 'obra' => $obra]);
    }

    public function destroy(Obra $obra)
    {
        $obra->delete();
        return response()->json(['ok' => true]);
    }
}

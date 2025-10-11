<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\ObraController;
use App\Models\Obra;

class ObraControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store_crea_obra_y_devuelve_json_ok(): void
    {
        $payload = [
            'nombre'             => 'Obra Río Chico',
            'codigo'             => 'OBR-TEST-'.uniqid(),
            'direccion'          => 'Av. Los Ingenieros 456',
            'fecha_inicio'       => now()->toDateString(),
            'fecha_fin_estimada' => now()->addMonth()->toDateString(),
        ];

        $request    = Request::create('/fake', 'POST', $payload);
        $controller = app(ObraController::class);
        $response   = $controller->store($request);

        $this->assertTrue(method_exists($response, 'getStatusCode'));
        $this->assertSame(200, $response->getStatusCode());

        $json = $response->getData(true);
        $this->assertTrue($json['ok'] ?? false);

        $this->assertDatabaseHas('obras', [
            'codigo' => $payload['codigo'],
            'nombre' => 'Obra Río Chico',
            'estado' => 'activa', // lo define el controlador
        ]);
    }

    /** @test */
    public function update_modifica_obra_y_devuelve_json_ok(): void
    {
        // Crear obra base (como si la hubiera creado store)
        $obra = Obra::create([
            'nombre'   => 'Obra Inicial',
            'codigo'   => 'OBR-INICIAL',
            'direccion'=> 'Calle 1',
            'estado'   => 'activa',
        ]);

        $payload = [
            'nombre'             => 'Obra Renombrada',
            'codigo'             => 'OBR-INICIAL', // mismo código, pasa unique con ignore
            'direccion'          => 'Calle 2',
            'fecha_inicio'       => now()->toDateString(),
            'fecha_fin_estimada' => now()->addDays(10)->toDateString(),
        ];

        $request    = Request::create('/fake', 'PUT', $payload);
        $controller = app(ObraController::class);
        $response   = $controller->update($request, $obra);

        $this->assertTrue(method_exists($response, 'getStatusCode'));
        $this->assertSame(200, $response->getStatusCode());

        $json = $response->getData(true);
        $this->assertTrue($json['ok'] ?? false);

        $this->assertDatabaseHas('obras', [
            'id'      => $obra->id,
            'nombre'  => 'Obra Renombrada',
            'codigo'  => 'OBR-INICIAL',
            'direccion' => 'Calle 2',
        ]);
    }
}

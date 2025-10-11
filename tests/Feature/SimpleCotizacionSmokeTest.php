<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\CotizacionElectricoController;
use App\Models\User;

class SimpleCotizacionSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function crearUsuario(array $overrides = []): User
    {
        $base = [
            'name'              => 'Usuario Test',
            'email'             => 'user'.uniqid().'@test.com',
            'password'          => bcrypt('123456'),
            'security_question' => 'color favorito',
            'security_answer'   => 'azul',
        ];

        return User::create(array_merge($base, $overrides));
    }

    /** @test */
    public function smoke_crear_cotizacion_electrica_simple_via_controlador(): void
    {
        // 1) Usuario autenticado (para $request->user()->id en el controlador)
        $user = $this->crearUsuario();
        $this->be($user);

        // 2) Inicia sesión de prueba para que back()->with(...) funcione
        $this->startSession();

        // 3) Payload mínimo válido según tu controlador
        $payload = [
            'tipo_servicio'       => 'Prueba simple',
            'area_m2'             => 10,
            'descripcion_trabajo' => 'Trabajo de prueba simple',
            'ubicacion_obra'      => 'PruebaCiudad',
            'fecha_inicio'        => now()->toDateString(),
            // opcionales omitidos
        ];

        // 4) Construye un Request POST y “resuelve” el usuario autenticado
        $request = Request::create('/fake', 'POST', $payload);
        $request->setUserResolver(fn () => $user);

        // 5) Llama al controlador real (black-box a nivel de controlador)
        $controller = app(CotizacionElectricoController::class);
        $response   = $controller->store($request);

        // 6) Debe ser una redirección con flash 'ok'
        $this->assertTrue(method_exists($response, 'isRedirection') && $response->isRedirection());
        $this->assertTrue(session()->has('ok'));

        // 7) Verifica que se insertó en la base de datos
        $this->assertDatabaseHas('cotizaciones_electrico', [
            'user_id'       => $user->id,
            'tipo_servicio' => 'Prueba simple',
            'ubicacion_obra'=> 'PruebaCiudad',
        ]);
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\CotizacionGrua;
use App\Models\CotizacionElectrico;
use Illuminate\Support\Carbon;

class CotizacionCastsTest extends TestCase
{
    use RefreshDatabase;

    /** Helper: crea un usuario válido según tu esquema de users */
    private function crearUsuario(array $overrides = []): User
    {
        $base = [
            'name'              => 'Usuario Test',
            'email'             => 'user'.uniqid().'@test.com',
            'password'          => bcrypt('123456'),
            // Campos NOT NULL adicionales de tu tabla users:
            'security_question' => 'color favorito',
            'security_answer'   => 'azul',
        ];

        return User::create(array_merge($base, $overrides));
    }

    /** @test */
    public function grua_castea_fecha_booleanos_y_decimal_correctamente(): void
    {
        $user = $this->crearUsuario();

        // Simulamos checkboxes variados (string/numérico) para probar el cast boolean
        $g = CotizacionGrua::create([
            'user_id'             => $user->id,
            'tipo_grua'           => 'HIAB',
            'capacidad'           => 12.5,
            'dias_alquiler'       => 3,
            'fecha_inicio'        => '2025-10-20',
            'ubicacion_obra'      => 'Chilca',
            'incluye_operador'    => '1',   // truthy string
            'incluye_rigger'      => 0,     // falsy int
            'incluye_combustible' => true,  // boolean
            'observaciones'       => 'Prueba casting',
            'precio_total'        => 1234.50,
            'estado'              => 'pendiente',
        ]);

        // date
        $this->assertInstanceOf(Carbon::class, $g->fecha_inicio);
        $this->assertSame('2025-10-20', $g->fecha_inicio->toDateString());

        // booleans
        $this->assertIsBool($g->incluye_operador);
        $this->assertTrue($g->incluye_operador);

        $this->assertIsBool($g->incluye_rigger);
        $this->assertFalse($g->incluye_rigger);

        $this->assertIsBool($g->incluye_combustible);
        $this->assertTrue($g->incluye_combustible);

        // decimal:2
        $this->assertSame(1234.50, (float) $g->precio_total);

        // datetime para decision_fecha al actualizar
        $ahora = now();
        $g->update([
            'decision_motivo' => 'Aprobado por costo',
            'decision_por'    => $user->id,
            'decision_fecha'  => $ahora, // se castea a datetime
        ]);

        $g->refresh();
        $this->assertInstanceOf(Carbon::class, $g->decision_fecha);
        $this->assertSame($ahora->toDateTimeString(), $g->decision_fecha->toDateTimeString());
    }

    /** @test */
    public function electrico_castea_decimal_integer_date_boolean_y_datetime(): void
    {
        $user = $this->crearUsuario(['email' => 'otro'.uniqid().'@test.com']);

        $e = CotizacionElectrico::create([
            'user_id'            => $user->id,
            'tipo_servicio'      => 'Cableado industrial',
            'area_m2'            => '150.756', // string numérico → decimal:2
            'descripcion_trabajo'=> 'Tendido, canalizado y pruebas.',
            'voltaje_requerido'  => '440V',
            'duracion_dias'      => '15',      // string → integer
            'ubicacion_obra'     => 'Huancayo',
            'fecha_inicio'       => '2025-11-05', // date
            'incluir_materiales' => '0',          // falsy string → boolean
            'observaciones'      => 'Sin materiales incluidos',
            'estado'             => 'pendiente',
        ]);

        // decimal:2
        $this->assertSame(150.76, (float) $e->area_m2);

        // integer
        $this->assertIsInt($e->duracion_dias);
        $this->assertSame(15, $e->duracion_dias);

        // date
        $this->assertInstanceOf(Carbon::class, $e->fecha_inicio);
        $this->assertSame('2025-11-05', $e->fecha_inicio->toDateString());

        // boolean
        $this->assertIsBool($e->incluir_materiales);
        $this->assertFalse($e->incluir_materiales);

        // datetime para decision_fecha
        $momento = now()->addDay();
        $e->update([
            'decision_motivo' => 'Rechazado por alcance',
            'decision_por'    => $user->id,
            'decision_fecha'  => $momento,
            'estado'          => 'rechazada',
        ]);

        $e->refresh();
        $this->assertInstanceOf(Carbon::class, $e->decision_fecha);
        $this->assertSame($momento->toDateTimeString(), $e->decision_fecha->toDateTimeString());
    }
}

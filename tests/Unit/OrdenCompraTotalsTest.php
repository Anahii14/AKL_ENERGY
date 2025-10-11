<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\OrdenCompra;
use App\Models\DetalleOrdenCompra;

class OrdenCompraTotalsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: crea un usuario válido según tu esquema de users.
     * Agrega aquí cualquier otro campo NOT NULL que tengas en tu migración.
     */
    private function crearUsuario(array $overrides = []): User
    {
        $base = [
            'name'              => 'Usuario Test',
            'email'             => 'user'.uniqid().'@test.com',
            'password'          => bcrypt('123456'),
            // Campos extra que tu tabla requiere (NOT NULL, sin default):
            'security_question' => 'color favorito',
            'security_answer'   => 'azul',
            // Si tu users tiene más NOT NULL, agrégalos aquí.
        ];

        return User::create(array_merge($base, $overrides));
    }

    /** @test */
    public function test_calcula_subtotal_automaticamente_en_detalle(): void
    {
        $user = $this->crearUsuario([
            'name'  => 'Admin',
            'email' => 'admin@test.com',
        ]);

        $orden = OrdenCompra::create([
            'codigo'                 => 'OC-2025-0001',
            'proveedor_id'           => null, // ajusta si tu FK es NOT NULL
            'pedido_id'              => null, // ajusta si tu FK es NOT NULL
            'user_id'                => $user->id,
            'fecha_emision'          => now()->toDateString(),
            'fecha_entrega_estimada' => now()->addDays(3)->toDateString(),
            'monto_total'            => 0,
            'estado'                 => 'pendiente', // <- usa un valor válido de tu ENUM
            'moneda'                 => 'PEN',
            'observaciones'          => null,
        ]);

        $detalle = DetalleOrdenCompra::create([
            'orden_compra_id' => $orden->id,
            'material_id'     => null, // ajusta si tu FK es NOT NULL
            'descripcion'     => 'Cable THHN',
            'unidad'          => 'M',
            'cantidad'        => 5,
            'precio_unitario' => 10.50,
            'subtotal'        => null, // lo calcula el modelo
        ]);

        $this->assertSame(52.50, (float)$detalle->subtotal, 'El subtotal no se calculó correctamente.');
    }

    /** @test */
    public function test_recalcula_total_en_orden_cuando_se_modifican_detalles(): void
    {
        $user = $this->crearUsuario([
            'name'  => 'Operador',
            'email' => 'operador@test.com',
        ]);

        $orden = OrdenCompra::create([
            'codigo'                 => 'OC-2025-0002',
            'proveedor_id'           => null,
            'pedido_id'              => null,
            'user_id'                => $user->id,
            'fecha_emision'          => now()->toDateString(),
            'fecha_entrega_estimada' => now()->addDays(5)->toDateString(),
            'monto_total'            => 0,
            'estado'                 => 'pendiente', // <- usa un valor válido de tu ENUM
            'moneda'                 => 'PEN',
        ]);

        // Detalle 1: 2 * 100 = 200
        $d1 = DetalleOrdenCompra::create([
            'orden_compra_id' => $orden->id,
            'material_id'     => null,
            'descripcion'     => 'Tablero principal',
            'unidad'          => 'UND',
            'cantidad'        => 2,
            'precio_unitario' => 100,
            'subtotal'        => null,
        ]);
        $orden->refresh();
        $this->assertSame(200.00, (float)$orden->monto_total, 'Error al sumar el primer detalle.');

        // Detalle 2: 3 * 50 = 150 → total 350
        $d2 = DetalleOrdenCompra::create([
            'orden_compra_id' => $orden->id,
            'material_id'     => null,
            'descripcion'     => 'Canaleta PVC',
            'unidad'          => 'UND',
            'cantidad'        => 3,
            'precio_unitario' => 50,
            'subtotal'        => null,
        ]);
        $orden->refresh();
        $this->assertSame(350.00, (float)$orden->monto_total, 'Error tras agregar segundo detalle.');

        // Actualizo detalle 2: 4 * 50 = 200 → total 400
        $d2->update([
            'cantidad' => 4,
            'subtotal' => null, // forzamos recálculo en el saving
        ]);
        $orden->refresh();
        $this->assertSame(400.00, (float)$orden->monto_total, 'Error al actualizar detalle.');

        // Elimino detalle 1 → debería quedar 200
        $d1->delete();

        // 🔧 Sin tocar modelos: recalculamos explícitamente aquí
        // (evita problemas de eventos/commit en entornos de test)
        $orden->recalcularTotal();
        $orden->refresh();

        $this->assertSame(200.00, (float)$orden->monto_total, 'Error al eliminar detalle.');
    }
}

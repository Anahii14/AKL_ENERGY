<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Obra;
use App\Models\Pedido;
use Illuminate\Support\Str;

class PedidoScopesTest extends TestCase
{
    use RefreshDatabase;

    private function crearUsuario(array $overrides = []): User
    {
        $base = [
            'name'              => 'Usuario Test',
            'email'             => 'user'.Str::lower(Str::random(6)).'@test.com',
            'password'          => bcrypt('123456'),
            'security_question' => 'color favorito',
            'security_answer'   => 'azul',
        ];
        return User::create(array_merge($base, $overrides));
    }

    private function crearObra(array $overrides = []): Obra
    {
        $base = [
            'nombre'    => 'Obra Central',
            'codigo'    => 'OBR-'.Str::upper(Str::random(5)),
            'direccion' => 'Av. Siempre Viva 123',
            'estado'    => 'activa',
        ];
        return Obra::create(array_merge($base, $overrides));
    }

    /** Genera un código corto (8 chars) compatible con tu esquema */
    private function codigoCorto(): string
    {
        return Str::lower(Str::random(8));
    }

    /** @test */
    public function scope_de_usuario_filtra_pedidos_por_user_id(): void
    {
        $u1 = $this->crearUsuario();
        $u2 = $this->crearUsuario(['email' => 'otro'.Str::lower(Str::random(6)).'@test.com']);
        $obra = $this->crearObra();

        $p1 = Pedido::create([
            'codigo'          => $this->codigoCorto(),
            'obra_id'         => $obra->id,
            'user_id'         => $u1->id,
            'fecha_requerida' => now()->toDateString(),
            'estado'          => 'solicitado',
            'total_items'     => 1,
        ]);

        $p2 = Pedido::create([
            'codigo'          => $this->codigoCorto(),
            'obra_id'         => $obra->id,
            'user_id'         => $u2->id,
            'fecha_requerida' => now()->toDateString(),
            'estado'          => 'aprobado',
            'total_items'     => 2,
        ]);

        $soloU1 = Pedido::deUsuario($u1->id)->get();
        $soloU2 = Pedido::deUsuario($u2->id)->get();

        $this->assertCount(1, $soloU1);
        $this->assertTrue($soloU1->first()->is($p1));

        $this->assertCount(1, $soloU2);
        $this->assertTrue($soloU2->first()->is($p2));
    }

    /** @test */
    public function scope_estado_filtra_pedidos_por_estado(): void
    {
        $u = $this->crearUsuario();
        $obra = $this->crearObra();

        $pSol = Pedido::create([
            'codigo'          => $this->codigoCorto(),
            'obra_id'         => $obra->id,
            'user_id'         => $u->id,
            'fecha_requerida' => now()->toDateString(),
            'estado'          => 'solicitado',
            'total_items'     => 1,
        ]);

        $pApr = Pedido::create([
            'codigo'          => $this->codigoCorto(),
            'obra_id'         => $obra->id,
            'user_id'         => $u->id,
            'fecha_requerida' => now()->toDateString(),
            'estado'          => 'aprobado',
            'total_items'     => 1,
        ]);

        $solicitados = Pedido::estado('solicitado')->get();
        $aprobados   = Pedido::estado('aprobado')->get();

        $this->assertCount(1, $solicitados);
        $this->assertTrue($solicitados->first()->is($pSol));

        $this->assertCount(1, $aprobados);
        $this->assertTrue($aprobados->first()->is($pApr));
    }

    /** @test */
    public function scopes_pueden_combinarse(): void
    {
        $u1 = $this->crearUsuario();
        $u2 = $this->crearUsuario(['email' => 'otro'.Str::lower(Str::random(6)).'@test.com']);
        $obra = $this->crearObra();

        $p1 = Pedido::create([
            'codigo'          => $this->codigoCorto(),
            'obra_id'         => $obra->id,
            'user_id'         => $u1->id,
            'fecha_requerida' => now()->toDateString(),
            'estado'          => 'solicitado',
            'total_items'     => 1,
        ]);

        Pedido::create([
            'codigo'          => $this->codigoCorto(),
            'obra_id'         => $obra->id,
            'user_id'         => $u1->id,
            'fecha_requerida' => now()->toDateString(),
            'estado'          => 'aprobado',
            'total_items'     => 1,
        ]);

        Pedido::create([
            'codigo'          => $this->codigoCorto(),
            'obra_id'         => $obra->id,
            'user_id'         => $u2->id,
            'fecha_requerida' => now()->toDateString(),
            'estado'          => 'solicitado',
            'total_items'     => 1,
        ]);

        $res = Pedido::deUsuario($u1->id)->estado('solicitado')->get();

        $this->assertCount(1, $res);
        $this->assertTrue($res->first()->is($p1));
    }
}

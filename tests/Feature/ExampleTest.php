<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_login(): void
    {
        // Si tu raíz redirige al login:
        $this->get('/')
            ->assertStatus(302)
            ->assertRedirect(route('auth.login.form')); // o ->assertRedirect('/login')
    }

    public function test_login_page_is_accessible(): void
    {
        $this->followingRedirects()
            ->get(route('auth.login.form'))   // o '/login'
            ->assertOk()
            ->assertSee('AKL Energy Hub')
            ->assertSee('Ingresa a tu cuenta');
    }
}

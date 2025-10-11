<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginFlowTest extends TestCase
{
    /**
     * Verifica que la raíz del sistema (/) redirige al login.
     */
    public function test_root_redirects_to_login(): void
    {
        $this->get('/')
            ->assertStatus(302)
            ->assertRedirect(route('auth.login.form'));
    }

    /**
     * Verifica que la página de login se puede acceder correctamente.
     */
    public function test_login_page_is_accessible(): void
    {
        $this->followingRedirects()
             ->get(route('auth.login.form'))
             ->assertOk()
             ->assertSee('AKL Energy Hub')
             ->assertSee('Ingresa a tu cuenta');
    }
}

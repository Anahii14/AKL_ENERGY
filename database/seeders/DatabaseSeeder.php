<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Importante importar el modelo User

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Usuario GERENTE GENERAL (Para el Dashboard Gerencial - Idea 1)
        User::create([
            'name'              => 'Gerente General',
            'email'             => 'gerente@test.com',
            'password'          => Hash::make('password'), // Contraseña: password
            'role'              => 'Gerente General',
            'phone'             => '999999999',
            'security_question' => 'Mascota',
            'security_answer'   => 'Firulais',
        ]);

        // 2. Usuario ALMACENERO (Para probar la IA de Anomalías - Idea 2)
        User::create([
            'name'              => 'Encargado Almacén',
            'email'             => 'almacen@test.com',
            'password'          => Hash::make('password'),
            'role'              => 'Encargado de Almacén',
            'phone'             => '988888888',
            'security_question' => 'Ciudad',
            'security_answer'   => 'Lima',
        ]);

        // 3. Usuario ADMIN DE OBRA (Para hacer pedidos)
        User::create([
            'name'              => 'Admin Obra',
            'email'             => 'obra@test.com',
            'password'          => Hash::make('password'),
            'role'              => 'Administrador de Obra',
            'phone'             => '977777777',
            'security_question' => 'Color',
            'security_answer'   => 'Azul',
        ]);

        // 4. Usuario CLIENTE (Para ver estados)
        User::create([
            'name'              => 'Cliente Empresa',
            'email'             => 'cliente@test.com',
            'password'          => Hash::make('password'),
            'role'              => 'Cliente',
            'phone'             => '966666666',
            'security_question' => 'Comida',
            'security_answer'   => 'Pizza',
        ]);
        
        // Mensaje en consola para saber que terminó
        $this->command->info('¡Usuarios de prueba creados correctamente!');
    }
}
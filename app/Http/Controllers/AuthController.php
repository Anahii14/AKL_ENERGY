<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function mostrarFormularioLogin()
    {
        return view('login.InicioSesion');
    }

    public function iniciarSesion(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirección según rol
            $user = Auth::user();
            if ($user->role === 'Cliente') {
                return redirect()->route('cliente.empresa');
            } elseif ($user->role === 'Administrador de Obra') {
                return redirect()->route('admin.empresa');
            } elseif ($user->role === 'Encargado de Almacén') {
                return redirect()->route('almacen.empresa'); // 👈 define esta ruta después
            } elseif ($user->role === 'Gerente General') {
                return redirect()->route('gerente.dashboard_gerencial'); // 👈 define esta ruta después
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->onlyInput('email');
    }


    public function registrarUsuario(Request $request)
    {
        $datos = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'             => ['nullable', 'string', 'max:30'],
            'role'              => ['required', 'in:Cliente'], 
            'password'          => ['required', 'confirmed', 'min:8'],
            'security_question' => ['required', 'string', 'max:255'],
            'security_answer'   => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name'              => $datos['name'],
            'email'             => $datos['email'],
            'phone'             => $datos['phone'] ?? null,
            'role'              => $datos['role'],
            'password'          => Hash::make($datos['password']), 
            'security_question' => $datos['security_question'],
            'security_answer'   => $datos['security_answer'],
        ]);

        return redirect()
            ->route('auth.login.form')
            ->with('status', 'Cuenta creada con éxito. Ahora puedes iniciar sesión.')
            ->withInput(['email' => $user->email]);
    }

    public function mostrarFormularioRecuperar()
    {
        return view('login.RecuperarPassword');
    }

    public function enviarCorreoRecuperar(Request $request)
    {

        return back()->with('status', 'Si el correo existe, se enviará un enlace de recuperación.');
    }

    public function mostrarFormularioRegistro()
    {
        return view('login.Registrarse'); 
    }

    public function crearDesdeAlmacen(Request $request)
    {
        $datos = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'role'     => ['required', Rule::in(['Administrador de Obra', 'Encargado de Almacén', 'Gerente General'])],
            'password' => ['required', 'string', 'min:8'],
            
        ]);

        \App\Models\User::create([
            'name'              => $datos['name'],
            'email'             => $datos['email'],
            'phone'             => $datos['phone'] ?? null,
            'role'              => $datos['role'],
            'password'          => Hash::make($datos['password']),
            'security_question' => 'Creado desde Almacén',
            'security_answer'   => 'N/A',
        ]);

        return back()->with('ok', 'Usuario creado correctamente.');
    }
}

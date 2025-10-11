<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CotizacionGruaController;
use App\Http\Controllers\CotizacionElectricoController;
use App\Http\Controllers\GuiaSalidaController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\DashboardGerencialController;

/*
|--------------------------------------------------------------------------
| Redirección raíz
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('auth.login.form'));

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'mostrarFormularioLogin')->name('auth.login.form');
    Route::post('/login', 'iniciarSesion')->name('auth.login');

    Route::get('/password/recuperar', 'mostrarFormularioRecuperar')->name('password.request');
    Route::post('/password/recuperar', 'enviarCorreoRecuperar')->name('auth.password.email');

    Route::get('/registrarse', 'mostrarFormularioRegistro')->name('auth.register.form');
    Route::post('/registrarse', 'registrarUsuario')->name('auth.register');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('auth.login.form');
    })->name('logout');

    // Creación de usuario desde Almacén
    Route::post('/almacen/usuarios', 'crearDesdeAlmacen')
        ->name('almacen.usuarios.store')
        ->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| CLIENTE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->controller(CotizacionGruaController::class)->group(function () {
    Route::get('/cliente/gruas', 'index')->name('cliente.gruas');
    Route::post('/cliente/gruas', 'store')->name('cotizaciones.gruas.store');
    Route::get('/cliente/estado', 'estado')->name('cliente.estado');
});

Route::middleware('auth')->controller(CotizacionElectricoController::class)->group(function () {
    Route::get('/cliente/electrico', 'index')->name('cliente.electrico');
    Route::post('/cliente/electrico', 'store')->name('cotizaciones.electrico.store');
});

Route::middleware('auth')->get('/DashboardCliente', fn() => view('DashboardCliente.cliente'))
    ->name('cliente.empresa');

/*
|--------------------------------------------------------------------------
| ADMINISTRADOR
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->controller(PedidoController::class)->group(function () {
    Route::get('/administrador/nuevoPedido', 'create')->name('admin.nuevoPedido');
    Route::get('/administrador/misPedidos', 'index')->name('admin.misPedidos');
    Route::get('/administrador/historial', 'historial')->name('admin.historial');
    Route::post('/administrador/pedidos', 'store')->name('pedidos.store');
});

Route::middleware('auth')->get('/administrador/empresa', fn() => view('DashboardAdminObra.empresa'))
    ->name('admin.empresa');

Route::middleware('auth')->get('/administrador/guias', fn() => view('DashboardAdminObra.guias'))
    ->name('admin.guias');

/*
|--------------------------------------------------------------------------
| ALMACÉN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/almacen/empresa', fn() => view('DashboardAlmacen.empresa'))->name('almacen.empresa');
    Route::get('/almacen/usuarios', fn() => view('DashboardAlmacen.usuarios'))->name('almacen.usuarios');

    // PEDIDOS
    Route::controller(PedidoController::class)->group(function () {
        Route::get('/almacen/pedidos', 'almacenIndex')->name('almacen.pedidos');
        Route::post('/almacen/pedidos/{pedido}/visto', 'marcarVisto')->name('pedidos.visto');
        Route::patch('/almacen/pedidos/{pedido}/cancelar', 'cancelar')->name('pedidos.cancel');
        Route::get('/almacen/pedidos/{pedido}', 'showAlmacen')->name('pedidos.show');
    });

    // GUIAS DE SALIDA
    Route::controller(GuiaSalidaController::class)->group(function () {
        Route::post('/almacen/pedidos/{pedido}/procesar', 'procesarPedido')->name('pedidos.procesar');
        Route::post('/almacen/guias', 'store')->name('guias.store');
        Route::get('/almacen/guias_orden', 'index')->name('almacen.guias_orden');
    });

    // Estado del pedido (selector en “En Proceso”)
    Route::patch('/almacen/pedidos/{pedido}/estado', [\App\Http\Controllers\PedidoController::class, 'actualizarEstado'])
        ->name('pedidos.update_estado');

    // ORDENES DE COMPRA
    Route::controller(OrdenCompraController::class)->group(function () {
        Route::post('/almacen/pedidos/{pedido}/generar-oc', 'generarDesdePedido')->name('pedidos.generar_oc');
        Route::get('/almacen/ordenes-compra', 'index')->name('almacen.ordenes_compra.index');
        Route::get('/almacen/ordenes-compra/{orden}', 'show')->name('almacen.ordenes_compra.show');
    });

    // COTIZACIONES (eléctrico y grúa)
    Route::controller(CotizacionElectricoController::class)->group(function () {
        Route::get('/almacen/cotizaciones', 'almacenIndex')->name('almacen.cotizaciones');
    });

    Route::post('/almacen/cotizaciones/decidir', [CotizacionGruaController::class, 'decidir'])
        ->name('almacen.cotizaciones.decidir');

    // PROVEEDORES
    Route::controller(ProveedorController::class)->group(function () {
        Route::get('/almacen/gestion', 'index')->name('almacen.gestion');
        Route::post('/almacen/proveedores', 'store')->name('proveedores.store');
        Route::put('/almacen/proveedores/{proveedor}', 'update')->name('proveedores.update');
        Route::delete('/almacen/proveedores/{proveedor}', 'destroy')->name('proveedores.destroy');
    });

    // OBRAS
    Route::controller(ObraController::class)->group(function () {
        Route::post('/almacen/obras', 'store')->name('obras.store');
        Route::put('/almacen/obras/{obra}', 'update')->name('obras.update');
        Route::delete('/almacen/obras/{obra}', 'destroy')->name('obras.destroy');
    });

    // MATERIALES
    Route::controller(MaterialController::class)->group(function () {
        Route::post('/almacen/materiales', 'store')->name('materiales.store');
        Route::put('/almacen/materiales/{material}', 'update')->name('materiales.update');
        Route::delete('/almacen/materiales/{material}', 'destroy')->name('materiales.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| GERENTE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->controller(DashboardGerencialController::class)->group(function () {
    Route::get('/gerente/dashboard_gerencial', 'index')->name('gerente.dashboard_gerencial');
});

/*
|--------------------------------------------------------------------------
| DOCUMENTOS (PDF)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/documentos/guia/{guia}', [GuiaSalidaController::class, 'pdf'])->name('guia.pdf');
    Route::get('/documentos/oc/{orden}', [OrdenCompraController::class, 'pdf'])->name('oc.pdf');
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(fn() => redirect()->route('auth.login.form'));

<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TareaController;
// use App\Http\Controllers\ProfileController; // <-- YA NO LO NECESITAMOS
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// --- 1. RUTA DASHBOARD GENÉRICA (FALLBACK) ---
// Es necesaria por si el login intenta redirigir aquí por defecto
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 2. RUTAS PARA EMPLEADO ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/empleado/dashboard', function () {
        if (Auth::user()->rol !== 'empleado') {
            abort(403, 'No autorizado.');
        }
        // CAMBIO AQUÍ: Apuntamos a la carpeta 'portal_empleado'
        return view('portal_empleado.dashboard'); 
    })->name('empleado.dashboard');
});


// --- 3. RUTAS PARA CLIENTE ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cliente/dashboard', function () {
        if (Auth::user()->rol !== 'cliente') {
            abort(403, 'No autorizado.');
        }
        // CAMBIO AQUÍ: Apuntamos a la carpeta 'portal_cliente'
        return view('portal_cliente.dashboard'); 
    })->name('cliente.dashboard');
});

// --- 4. RUTAS SOLO PARA ADMIN ---
Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // EMPLEADOS
    Route::resource('empleados', EmpleadoController::class)->except(['show']);
    Route::get('/empleados/search', [EmpleadoController::class, 'search'])->name('empleados.search');
    Route::patch('/empleados/{empleado}/reactivar', [EmpleadoController::class, 'reactivar'])->name('empleados.reactivar');

    // CLIENTES
    Route::resource('clientes', ClienteController::class)->except(['show']);
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
    Route::patch('/clientes/{cliente}/reactivar', [ClienteController::class, 'reactivar'])->name('clientes.reactivar');
    Route::delete('/clientes/{cliente}/borrar-fiscal', [ClienteController::class, 'destroyFiscal'])->name('clientes.destroyFiscal');
    Route::post('/verificar-password', [ClienteController::class, 'verificarPassword'])->name('password.verify');

    // COTIZACIONES
    Route::get('/cotizaciones/search', [CotizacionController::class, 'search'])->name('cotizaciones.search');
    Route::get('/cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'pdf'])->name('cotizaciones.pdf');
    Route::resource('cotizaciones', CotizacionController::class)->parameters(['cotizaciones' => 'cotizacion']);

    // CATEGORÍAS
    Route::resource('categorias', CategoriaController::class)->except(['show']);
    Route::get('/categorias/search', [CategoriaController::class, 'search'])->name('categorias.search');
    Route::patch('/categorias/{categoria}/reactivar', [CategoriaController::class, 'reactivar'])->name('categorias.reactivar');

    // TAREAS
    Route::resource('tareas', TareaController::class)->except(['show']);
    Route::get('/tareas/search', [TareaController::class, 'search'])->name('tareas.search');
    Route::get('/tareas/search-clientes', [TareaController::class, 'searchClientes'])->name('tareas.searchClientes');
    Route::get('/tareas/search-categorias', [TareaController::class, 'searchCategorias'])->name('tareas.searchCategorias');
});

require __DIR__.'/auth.php';
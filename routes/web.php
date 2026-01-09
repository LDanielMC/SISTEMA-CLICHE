<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CotizacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// este "dashboard" lo podemos dejar para usuarios normales
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// rutas generales autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔐 rutas solo para admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');


    // --- GRUPO EMPLEADOS ---
    Route::resource('empleados', EmpleadoController::class)->except(['show']);
    Route::get('/empleados/search', [EmpleadoController::class, 'search'])->name('empleados.search');
    Route::patch('/empleados/{empleado}/reactivar', [EmpleadoController::class, 'reactivar'])->name('empleados.reactivar');

    // --- GRUPO CLIENTES ---
    Route::resource('clientes', ClienteController::class)->except(['show']);
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
    Route::patch('/clientes/{cliente}/reactivar', [ClienteController::class, 'reactivar'])->name('clientes.reactivar');
    Route::delete('/clientes/{cliente}/borrar-fiscal', [App\Http\Controllers\ClienteController::class, 'destroyFiscal'])
    ->name('clientes.destroyFiscal');
    Route::post('/verificar-password', [App\Http\Controllers\ClienteController::class, 'verificarPassword'])
    ->name('password.verify');

    // --- GRUPO COTIZACIONES ---
    Route::get('/cotizaciones/search', [CotizacionController::class, 'search'])->name('cotizaciones.search');
    Route::get('/cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'pdf'])->name('cotizaciones.pdf');
    Route::resource('cotizaciones', CotizacionController::class)->parameters(['cotizaciones' => 'cotizacion']);



});



require __DIR__.'/auth.php';

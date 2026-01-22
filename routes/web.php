<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\MinutaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\AsignacionTareaController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\CalendarioConfigController;
use App\Http\Controllers\NotificacionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// --- 1. RUTA DASHBOARD GENÉRICA (FALLBACK) ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 2. RUTAS PARA EMPLEADO ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/empleado/dashboard', function () {
        if (Auth::user()->rol !== 'empleado') {
            abort(403, 'No autorizado.');
        }
        return view('portal_empleado.dashboard');
    })->name('empleado.dashboard');
});


// --- 3. RUTAS PARA CLIENTE ---
Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/cliente/dashboard', [\App\Http\Controllers\PortalClienteController::class, 'dashboard'])
        ->name('cliente.dashboard');

        Route::get('/cliente/briefs', [\App\Http\Controllers\PortalClienteController::class, 'briefs'])
        ->name('cliente.briefs');

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

    // MINUTAS
    Route::get('/minutas/search', [MinutaController::class, 'search'])->name('minutas.search');
    Route::resource('minutas', MinutaController::class)->parameters(['minutas' => 'minuta']);

    // CATEGORÍAS
    Route::resource('categorias', CategoriaController::class)->except(['show']);
    Route::get('/categorias/search', [CategoriaController::class, 'search'])->name('categorias.search');
    Route::patch('/categorias/{categoria}/reactivar', [CategoriaController::class, 'reactivar'])->name('categorias.reactivar');

    // TAREAS
    Route::resource('tareas', TareaController::class)->except(['show']);
    Route::get('/tareas/search', [TareaController::class, 'search'])->name('tareas.search');
    Route::get('/tareas/search-clientes', [TareaController::class, 'searchClientes'])->name('tareas.searchClientes');
    Route::get('/tareas/search-categorias', [TareaController::class, 'searchCategorias'])->name('tareas.searchCategorias');

    // ✅ ASIGNACIONES DE TAREAS (Admin)
    Route::resource('asignaciones', AsignacionTareaController::class)
        ->parameters(['asignaciones' => 'asignacion']) // ✅ FIX IMPORTANTE
        ->except(['show', 'edit']);

    Route::get('/asignaciones/search-tareas', [AsignacionTareaController::class, 'searchTareas'])->name('asignaciones.searchTareas');
    Route::get('/asignaciones/search-empleados', [AsignacionTareaController::class, 'searchEmpleados'])->name('asignaciones.searchEmpleados');
    Route::get('/asignaciones/empleado/{empleado}', [AsignacionTareaController::class, 'tareasEmpleado'])->name('asignaciones.tareasEmpleado');
    Route::get('/asignaciones/{asignacion}/evaluar', [AsignacionTareaController::class, 'evaluarTarea'])->name('asignaciones.evaluar');
    Route::post('/asignaciones/{asignacion}/actualizar-estado-admin', [AsignacionTareaController::class, 'actualizarEstadoAdmin'])->name('asignaciones.actualizarEstadoAdmin');

    // ✅ VER EVIDENCIA PDF (Admin también la puede ver)
    // (El control de permisos finos lo hace el método verEvidencia() en el controlador)
    Route::get('/asignaciones/{asignacion}/evidencia', [AsignacionTareaController::class, 'verEvidencia'])
        ->name('asignaciones.verEvidencia');

    // --- CALENDARIO DE PUBLICACIONES ---
    Route::get('/calendario', [PublicacionController::class, 'index'])->name('calendario.general');
    Route::get('/calendario/gestion/{cliente_id?}', [PublicacionController::class, 'gestionCliente'])->name('calendario.gestion');

    // CRUD Publicaciones
    Route::post('/publicaciones/masivo', [PublicacionController::class, 'storeMasivo'])->name('publicaciones.storeMasivo');
    
    // ✅ NUEVA RUTA: Actualización Masiva
    Route::put('/publicaciones/masivo', [PublicacionController::class, 'updateMasivo'])->name('publicaciones.updateMasivo');
    // Agrega esta línea en el grupo de rutas donde está PublicacionController
    Route::delete('/calendario/lote', [PublicacionController::class, 'destroyLote'])->name('calendario.destroyLote');

    Route::post('/publicaciones', [PublicacionController::class, 'store'])->name('publicaciones.store');
    Route::put('/publicaciones/{idPublicacion}', [PublicacionController::class, 'update'])->name('publicaciones.update');
    Route::delete('/publicaciones/{idPublicacion}', [PublicacionController::class, 'destroy'])->name('publicaciones.destroy');

    // CONFIGURACIÓN (Plataformas y Formatos)
    Route::get('/calendario/configuracion', [CalendarioConfigController::class, 'index'])->name('calendario.config');
    Route::post('/calendario/plataformas', [CalendarioConfigController::class, 'storePlataforma'])->name('plataformas.store');
    Route::delete('/calendario/plataformas/{id}', [CalendarioConfigController::class, 'destroyPlataforma'])->name('plataformas.destroy');
    Route::post('/calendario/formatos', [CalendarioConfigController::class, 'storeFormato'])->name('formatos.store');
    Route::delete('/calendario/formatos/{id}', [CalendarioConfigController::class, 'destroyFormato'])->name('formatos.destroy');


    // BRIEFS / FORMULARIOS
    Route::resource('briefs', \App\Http\Controllers\BriefController::class);
    // Rutas para asignación de Briefs
    Route::get('/briefs/{brief}/asignar', [\App\Http\Controllers\BriefController::class, 'assign'])->name('briefs.assign');
    Route::post('/briefs/{brief}/asignar', [\App\Http\Controllers\BriefController::class, 'storeAssignment'])->name('briefs.storeAssignment');
    Route::delete('/briefs/{brief}/asignar/{cliente}', [\App\Http\Controllers\BriefController::class, 'unassign'])->name('briefs.unassign');

    // RESPALDOS DE BASE DE DATOS
    Route::get('/admin/backups', [\App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
    Route::post('/admin/backups/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backups.create');
    Route::get('/admin/backups/{filename}/download', [\App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
    Route::delete('/admin/backups/{filename}', [\App\Http\Controllers\BackupController::class, 'delete'])->name('backups.delete');
    Route::post('/admin/backups/restore', [\App\Http\Controllers\BackupController::class, 'restore'])->name('backups.restore');

    // REPORTES
    Route::get('/admin/reportes/cumplimiento', [\App\Http\Controllers\ReporteController::class, 'cumplimiento'])->name('reportes.cumplimiento');
    Route::get('/admin/reportes/efectividad', [\App\Http\Controllers\ReporteController::class, 'efectividad'])->name('reportes.efectividad');
    Route::get('/admin/reportes/carga-trabajo', [\App\Http\Controllers\ReporteController::class, 'cargaTrabajo'])->name('reportes.carga_trabajo');
    Route::get('/admin/reportes/suscripciones', [\App\Http\Controllers\ReporteController::class, 'suscripciones'])->name('reportes.suscripciones');
    Route::post('/admin/reportes/suscripciones/pdf', [\App\Http\Controllers\ReporteController::class, 'suscripciones'])->name('reportes.suscripciones.pdf');
    Route::get('/admin/reportes/acuerdos-cliente', [\App\Http\Controllers\ReporteController::class, 'acuerdosCliente'])->name('reportes.acuerdos_cliente');
    Route::get('/admin/reportes/crecimiento-clientes', [\App\Http\Controllers\ReporteController::class, 'crecimientoClientes'])->name('reportes.crecimiento_clientes');

    // CATEGORÍAS DE SUSCRIPCIÓN
    Route::resource('categorias-suscripcion', \App\Http\Controllers\CategoriaSuscripcionController::class)
        ->parameters(['categorias-suscripcion' => 'categoriasSuscripcion']);
    Route::post('categorias-suscripcion/{categoriasSuscripcion}/reactivar', [\App\Http\Controllers\CategoriaSuscripcionController::class, 'reactivar'])
        ->name('categorias-suscripcion.reactivar');

    // SUSCRIPCIONES
    Route::resource('suscripciones', \App\Http\Controllers\SuscripcionController::class)
        ->parameters(['suscripciones' => 'suscripcion']);

    // RUTAS ADICIONALES PARA SUSCRIPCIONES
    Route::post('suscripciones/{suscripcion}/reactivar', [\App\Http\Controllers\SuscripcionController::class, 'reactivar'])
        ->name('suscripciones.reactivar');
    Route::get('suscripciones/{suscripcion}/renovar', [\App\Http\Controllers\SuscripcionController::class, 'renovarForm'])
        ->name('suscripciones.renovar.form');
    Route::post('suscripciones/{suscripcion}/renovar', [\App\Http\Controllers\SuscripcionController::class, 'renovar'])
        ->name('suscripciones.renovar');
});


// Rutas para usuarios autenticados (empleados / admin si aplica)
Route::middleware(['auth'])->group(function () {

    // MIS TAREAS ASIGNADAS (Empleado)
    Route::get('/mis-tareas', [AsignacionTareaController::class, 'misTareas'])->name('asignaciones.misTareas');
    Route::post('/mis-tareas/{asignacion}/actualizar-estado', [AsignacionTareaController::class, 'actualizarEstadoEmpleado'])->name('asignaciones.actualizarEstadoEmpleado');
    Route::post('/mis-tareas/{asignacion}/subir-evidencia', [AsignacionTareaController::class, 'subirEvidencia'])->name('asignaciones.subirEvidencia');
    Route::delete('/mis-tareas/{asignacion}/eliminar-evidencia', [AsignacionTareaController::class, 'eliminarEvidencia'])->name('asignaciones.eliminarEvidencia');

    // ✅ VER EVIDENCIA PDF (Empleado dueño también la puede ver)
    // (El control de permisos finos lo hace el método verEvidencia() en el controlador)
    Route::get('/asignaciones/{asignacion}/evidencia', [AsignacionTareaController::class, 'verEvidencia'])
        ->name('asignaciones.verEvidencia');

    // NOTIFICACIONES
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::get('/notificaciones/no-leidas', [NotificacionController::class, 'noLeidas'])->name('notificaciones.noLeidas');
    Route::post('/notificaciones/{notificacion}/marcar-leida', [NotificacionController::class, 'marcarComoLeida'])->name('notificaciones.marcarLeida');
    Route::post('/notificaciones/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasComoLeidas'])->name('notificaciones.marcarTodasLeidas');
    Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'eliminar'])->name('notificaciones.eliminar');

    // CALENDARIO/AGENDA
    Route::resource('eventos', \App\Http\Controllers\EventoController::class);
    Route::get('/calendario/eventos', [\App\Http\Controllers\EventoController::class, 'calendario'])->name('eventos.calendario');
    Route::get('/google/auth', [\App\Http\Controllers\EventoController::class, 'googleAuth'])->name('google.auth');
    Route::get('/google/callback', [\App\Http\Controllers\EventoController::class, 'googleCallback'])->name('google.callback');
    Route::get('/google/status', [\App\Http\Controllers\EventoController::class, 'googleStatus'])->name('google.status');
});



require __DIR__.'/auth.php';
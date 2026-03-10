<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTarea;
use App\Models\Tarea;
use App\Models\Empleado;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsignacionTareaController extends Controller
{
    public function index(Request $request)
    {
        $query = AsignacionTarea::with(['tarea.cliente', 'tarea.categoria', 'empleado'])
            ->whereHas('tarea');

        if ($request->filled('empleado')) {
            $query->where('empleado_id', $request->empleado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('estado_empleado')) {
            $query->where('estado_empleado', $request->estado_empleado);
        }

        if ($request->filled('estado_admin')) {
            $query->where('estado_admin', $request->estado_admin);
        }

        if ($request->filled('categoria')) {
            $query->whereHas('tarea', function($q) use ($request) {
                $q->where('categoria_id', $request->categoria);
            });
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->whereHas('tarea', function($subq) use ($buscar) {
                    $subq->where('titulo', 'like', "%{$buscar}%")
                         ->orWhere('descripcion', 'like', "%{$buscar}%");
                })
                ->orWhereHas('empleado', function($subq) use ($buscar) {
                    $subq->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                         ->orWhere('apellido_materno', 'like', "%{$buscar}%");
                })
                ->orWhereHas('tarea.cliente', function($subq) use ($buscar) {
                    $subq->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                         ->orWhere('empresa', 'like', "%{$buscar}%");
                });
            });
        }

        $asignaciones = $query->orderBy('fecha_limite', 'asc')->get();

        $empleados = Empleado::where('estatus', 'activo')->orderBy('nombre')->get();
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();

        return view('asignaciones.index', compact('asignaciones', 'empleados', 'categorias'));
    }

    public function create()
    {
        return view('asignaciones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tarea_id' => 'required|exists:tareas,id',
            'empleado_id' => 'required|exists:empleados,id_empleado',
            'prioridad' => 'required|in:baja,media,alta,urgente',
            'fecha_limite' => 'required|date|after_or_equal:today',
        ], [
            'tarea_id.required' => 'Debe seleccionar una tarea.',
            'empleado_id.required' => 'Debe seleccionar un empleado.',
            'prioridad.required' => 'Debe seleccionar una prioridad.',
            'fecha_limite.required' => 'La fecha límite es obligatoria.',
            'fecha_limite.after_or_equal' => 'La fecha límite no puede ser anterior a hoy.',
        ]);

        $asignacion = AsignacionTarea::create($validated);

        $empleado = Empleado::find($validated['empleado_id']);
        $tarea = Tarea::find($validated['tarea_id']);

        if ($empleado && $empleado->id_usuario) {
            Notificacion::create([
                'user_id' => $empleado->id_usuario,
                'asignacion_tarea_id' => $asignacion->id,
                'tipo' => 'tarea_asignada',
                'titulo' => 'Nueva tarea asignada',
                'mensaje' => "Se te ha asignado la tarea: {$tarea->titulo}",
            ]);
        }

        return redirect()->route('asignaciones.index')->with('success', 'Tarea asignada exitosamente.');
    }

    public function misTareas()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        $empleado = $user->empleado;
        
        if (!$empleado) {
            return redirect()->route('dashboard')->with('error', 'No tienes un perfil de empleado asociado.');
        }

        $asignaciones = AsignacionTarea::with(['tarea.cliente', 'tarea.categoria'])
            ->where('empleado_id', $empleado->id_empleado)
            ->orderBy('fecha_limite', 'asc')
            ->get();

        $asignadas = $asignaciones->where('estado_empleado', 'asignada');
        $enProceso = $asignaciones->where('estado_empleado', 'en_proceso');
        $terminadas = $asignaciones->where('estado_empleado', 'terminada');

        return view('asignaciones.mis-tareas', compact('asignadas', 'enProceso', 'terminadas'));
    }

    public function actualizarEstadoEmpleado(Request $request, AsignacionTarea $asignacion)
    {
        $validated = $request->validate([
            'estado_empleado' => 'required|in:asignada,en_proceso,terminada',
        ]);

        // ✅ VALIDACIÓN: No permitir cambiar a 'terminada' sin evidencia
        if ($validated['estado_empleado'] === 'terminada' && !$asignacion->evidencia_path) {
            return response()->json([
                'success' => false, 
                'message' => 'No puedes marcar la tarea como terminada sin subir la evidencia primero.'
            ], 422);
        }

        $estadoAnterior = $asignacion->estado_empleado;
        $asignacion->update($validated);

        if ($estadoAnterior !== $validated['estado_empleado'] && in_array($validated['estado_empleado'], ['en_proceso', 'terminada'])) {
            $adminUsers = \App\Models\User::where('rol', 'administrador')
                ->orWhere('rol', 'admin')
                ->get();
            
            $tipo = $validated['estado_empleado'] === 'en_proceso' ? 'tarea_en_proceso' : 'tarea_terminada';
            $titulo = $validated['estado_empleado'] === 'en_proceso' ? 'Tarea en proceso' : 'Tarea terminada';
            $empleadoNombre = $asignacion->empleado->nombre . ' ' . $asignacion->empleado->apellido_paterno;
            $mensaje = "{$empleadoNombre} ha marcado la tarea '{$asignacion->tarea->titulo}' como " . 
                      ($validated['estado_empleado'] === 'en_proceso' ? 'en proceso' : 'terminada');

            foreach ($adminUsers as $admin) {
                Notificacion::create([
                    'user_id' => $admin->id,
                    'asignacion_tarea_id' => $asignacion->id,
                    'tipo' => $tipo,
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente.']);
    }

    public function subirEvidencia(Request $request, AsignacionTarea $asignacion)
    {
        $request->validate([
            'evidencia' => 'required|file|mimes:pdf|max:10240',
        ], [
            'evidencia.required' => 'Debe seleccionar un archivo.',
            'evidencia.mimes' => 'El archivo debe ser un PDF.',
            'evidencia.max' => 'El archivo no debe superar 10MB.',
        ]);

        if ($asignacion->evidencia_path && Storage::exists($asignacion->evidencia_path)) {
            Storage::delete($asignacion->evidencia_path);
        }

        $path = $request->file('evidencia')->store('evidencias', 'public');

        $asignacion->update([
            'evidencia_path' => $path,
            'fecha_entrega' => now(),
            'estado_empleado' => 'terminada',
        ]);

        $adminUsers = \App\Models\User::where('rol', 'administrador')
            ->orWhere('rol', 'admin')
            ->get();
        
        $empleadoNombre = $asignacion->empleado->nombre . ' ' . $asignacion->empleado->apellido_paterno;
        $mensaje = "{$empleadoNombre} ha subido evidencia y marcado la tarea '{$asignacion->tarea->titulo}' como terminada";

        foreach ($adminUsers as $admin) {
            Notificacion::create([
                'user_id' => $admin->id,
                'asignacion_tarea_id' => $asignacion->id,
                'tipo' => 'tarea_terminada',
                'titulo' => 'Tarea terminada',
                'mensaje' => $mensaje,
            ]);
        }

        return redirect()->back()->with('success', 'Evidencia subida exitosamente. Tarea marcada como terminada.');
    }


    // Sirve el archivo de evidencia (PDF) de una asignación de tarea.
    // Entrada: $asignacion (AsignacionTarea) - modelo que contiene el path físico
    // del archivo en disco ($asignacion->evidencia_path), utilizado para validar
    // su existencia y servirlo al navegador.
    public function verEvidencia(AsignacionTarea $asignacion)
    {
        $user = Auth::user();

        // Lógica de permisos:
        if ($user->rol === 'admin') {
            // Admin tiene acceso total
        } elseif ($user->rol === 'empleado') {
            // Empleado solo puede ver evidencia de sus propias asignaciones
            if (!$user->empleado || (int)$user->empleado->id_empleado !== (int)$asignacion->empleado_id) {
                abort(403, 'No autorizado para ver esta evidencia.');
            }
        } else {
            abort(403, 'No autorizado.');
        }

        // Validar evidencia
        if (!$asignacion->evidencia_path) {
            abort(404, 'Esta asignación no tiene evidencia.');
        }

        // verificación en el sistema de archivos (Disk Public)
        if (!Storage::disk('public')->exists($asignacion->evidencia_path)) {
            abort(404, 'No se encontró el archivo de evidencia.');
        }

        $fullPath = Storage::disk('public')->path($asignacion->evidencia_path);

        // Retorna el archivo con cabeceras correctas
        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="evidencia.pdf"',
        ]);
    }


    public function eliminarEvidencia(AsignacionTarea $asignacion)
    {
        if ($asignacion->evidencia_path && Storage::exists($asignacion->evidencia_path)) {
            Storage::delete($asignacion->evidencia_path);
        }

        $asignacion->update([
            'evidencia_path' => null,
            'fecha_entrega' => null,
            'estado_empleado' => 'en_proceso',
        ]);

        return redirect()->back()->with('success', 'Evidencia eliminada. La tarea ha sido movida a "En Proceso".');
    }

    public function tareasEmpleado(Empleado $empleado)
    {
        $asignaciones = AsignacionTarea::with(['tarea.cliente', 'tarea.categoria'])
            ->where('empleado_id', $empleado->id_empleado)
            ->orderBy('fecha_limite', 'asc')
            ->get();

        $pendientes = $asignaciones->whereIn('estado_admin', [null, 'pendiente']);
        $completas = $asignaciones->where('estado_admin', 'completa');
        $parcialmenteCompletas = $asignaciones->where('estado_admin', 'parcialmente_completa');
        $incompletas = $asignaciones->where('estado_admin', 'incompleta');

        return view('asignaciones.tareas-empleado', compact('empleado', 'pendientes', 'completas', 'parcialmenteCompletas', 'incompletas'));
    }

    /**
     * Mostrar una tarea específica en vista Kanban para evaluación individual
     */
    public function evaluarTarea(AsignacionTarea $asignacion)
    {
        // Cargar relaciones necesarias
        $asignacion->load(['tarea.cliente', 'tarea.categoria', 'empleado']);
        $empleado = $asignacion->empleado;

        // Crear una colección con solo esta asignación
        $asignaciones = collect([$asignacion]);

        // Clasificar la tarea única en su columna correspondiente
        $pendientes = $asignaciones->whereIn('estado_admin', [null, 'pendiente']);
        $completas = $asignaciones->where('estado_admin', 'completa');
        $parcialmenteCompletas = $asignaciones->where('estado_admin', 'parcialmente_completa');
        $incompletas = $asignaciones->where('estado_admin', 'incompleta');

        // Reutilizar la vista Kanban existente
        return view('asignaciones.tareas-empleado', compact('empleado', 'pendientes', 'completas', 'parcialmenteCompletas', 'incompletas'));
    }

    public function actualizarEstadoAdmin(Request $request, AsignacionTarea $asignacion)
    {
        $validated = $request->validate([
            'estado_admin' => 'required|in:pendiente,completa,parcialmente_completa,incompleta',
            'notas_admin' => 'nullable|string',
        ]);

        $estadoAnterior = $asignacion->estado_admin;
        $asignacion->update($validated);

        if ($estadoAnterior !== $validated['estado_admin'] && $asignacion->empleado && $asignacion->empleado->id_usuario) {
            $estadosTexto = [
                'pendiente' => 'pendiente de revisión',
                'completa' => 'completa',
                'parcialmente_completa' => 'parcialmente completa',
                'incompleta' => 'incompleta',
            ];

            $tipoNotificacion = match($validated['estado_admin']) {
                'completa' => 'tarea_evaluada_completa',
                'parcialmente_completa' => 'tarea_evaluada_parcial',
                'incompleta' => 'tarea_evaluada_incompleta',
                default => 'tarea_evaluada',
            };

            $mensaje = "El administrador ha evaluado tu tarea '{$asignacion->tarea->titulo}' como {$estadosTexto[$validated['estado_admin']]}";
            
            if (!empty($validated['notas_admin'])) {
                $mensaje .= ". Notas: {$validated['notas_admin']}";
            }

            Notificacion::create([
                'user_id' => $asignacion->empleado->id_usuario,
                'asignacion_tarea_id' => $asignacion->id,
                'tipo' => $tipoNotificacion,
                'titulo' => 'Tarea evaluada',
                'mensaje' => $mensaje,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Evaluación actualizada correctamente.']);
    }

    

    public function destroy(AsignacionTarea $asignacion)
    {
        // Eliminar evidencia si existe
        if ($asignacion->evidencia_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($asignacion->evidencia_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($asignacion->evidencia_path);
        }

        // Eliminar notificaciones relacionadas (opcional pero recomendado)
        \App\Models\Notificacion::where('asignacion_tarea_id', $asignacion->getKey())->delete();

        $asignacion->delete();

        return redirect()->route('asignaciones.index')->with('success', 'Asignación eliminada exitosamente.');
    }




    public function searchTareas(Request $request)
    {
        $query = $request->get('query', '');

        $tareas = Tarea::with(['cliente', 'categoria'])
            ->where('titulo', 'like', "%{$query}%")
            ->orWhereHas('cliente', function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('apellido_paterno', 'like', "%{$query}%")
                  ->orWhere('empresa', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();

        return response()->json($tareas);
    }

    public function searchEmpleados(Request $request)
    {
        $query = $request->get('query', '');

        $empleadosQuery = Empleado::where('estatus', 'activo');

        if (!empty($query)) {
            $empleadosQuery->where(function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('apellido_paterno', 'like', "%{$query}%")
                  ->orWhere('apellido_materno', 'like', "%{$query}%");
            });
        }

        $empleados = $empleadosQuery->limit(20)->get();

        return response()->json($empleados);
    }
}

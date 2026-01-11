<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTarea;
use App\Models\Tarea;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsignacionTareaController extends Controller
{
    public function index(Request $request)
    {
        $query = AsignacionTarea::with(['tarea.cliente', 'tarea.categoria', 'empleado']);

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

        AsignacionTarea::create($validated);

        return redirect()->route('asignaciones.index')->with('success', 'Tarea asignada exitosamente.');
    }

    public function misTareas()
    {
        $empleado = auth()->user()->empleado;
        
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

        $asignacion->update($validated);

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

        return redirect()->back()->with('success', 'Evidencia subida exitosamente. Tarea marcada como terminada.');
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

    public function actualizarEstadoAdmin(Request $request, AsignacionTarea $asignacion)
    {
        $validated = $request->validate([
            'estado_admin' => 'required|in:pendiente,completa,parcialmente_completa,incompleta',
            'notas_admin' => 'nullable|string',
        ]);

        $asignacion->update($validated);

        return response()->json(['success' => true, 'message' => 'Evaluación actualizada correctamente.']);
    }

    public function destroy(AsignacionTarea $asignacion)
    {
        if ($asignacion->evidencia_path && Storage::exists($asignacion->evidencia_path)) {
            Storage::delete($asignacion->evidencia_path);
        }

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

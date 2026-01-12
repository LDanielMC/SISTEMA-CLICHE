<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::with(['asignacionTarea.tarea', 'asignacionTarea.empleado'])
            ->where('user_id', auth()->id())
            ->orderBy('leida', 'asc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'user_id' => $notif->user_id,
                    'asignacion_tarea_id' => $notif->asignacion_tarea_id,
                    'tipo' => $notif->tipo,
                    'titulo' => $notif->titulo,
                    'mensaje' => $notif->mensaje,
                    'leida' => $notif->leida,
                    'created_at' => $notif->created_at,
                    'asignacion_tarea' => $notif->asignacionTarea ? [
                        'id' => $notif->asignacionTarea->id,
                        'empleado_id' => $notif->asignacionTarea->empleado_id,
                        'empleado' => $notif->asignacionTarea->empleado ? [
                            'id_empleado' => $notif->asignacionTarea->empleado->id_empleado,
                            'nombre' => $notif->asignacionTarea->empleado->nombre,
                            'apellido_paterno' => $notif->asignacionTarea->empleado->apellido_paterno,
                        ] : null,
                        'tarea' => $notif->asignacionTarea->tarea ? [
                            'id' => $notif->asignacionTarea->tarea->id,
                            'titulo' => $notif->asignacionTarea->tarea->titulo,
                        ] : null,
                    ] : null,
                ];
            });

        return response()->json($notificaciones);
    }

    public function noLeidas()
    {
        $count = Notificacion::where('user_id', auth()->id())
            ->where('leida', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function marcarComoLeida(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->marcarComoLeida();

        return response()->json(['success' => true]);
    }

    public function marcarTodasComoLeidas()
    {
        Notificacion::where('user_id', auth()->id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    public function eliminar(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->delete();

        return response()->json(['success' => true]);
    }
}
